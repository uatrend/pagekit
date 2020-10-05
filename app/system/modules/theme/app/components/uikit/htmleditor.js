import UIkit from 'uikit';
import { $, on, css, attr, addClass, append, removeClass, hasClass, before, data, removeAttr, find, findAll, empty, trigger, closest, width, height, html, isVisible, parents, toggleClass, offset, Promise, scrollTop } from 'uikit-util';

const editors = [];

UIkit.component('Htmleditor', {

    props: {
        lblPreview: String,
        lblCodeview: String
    },

    data() {
        return {
            iframe: false,
            mode: 'split',
            markdown: false,
            autocomplete: true,
            enablescripts: false,
            height: 500,
            maxsplitsize: 1000,
            codemirror: { mode: 'htmlmixed', lineWrapping: true, dragDrop: false, autoCloseTags: true, matchTags: true, autoCloseBrackets: true, matchBrackets: true, indentUnit: 4, indentWithTabs: false, tabSize: 4, hintOptions: { completionSingle: false } },
            toolbar: ['bold', 'italic', 'strike', 'link', 'image', 'blockquote', 'listUl', 'listOl'],
            lblPreview: 'Preview',
            lblCodeview: 'HTML',
            lblMarkedview: 'Markdown',
            template: [
                '<div class="uk-htmleditor uk-clearfix" data-mode="split">',
                '<div class="uk-htmleditor-navbar">',
                '<div class="uk-flex uk-flex-between">',
                '<div>',
                '<ul class="uk-htmleditor-navbar-nav uk-htmleditor-toolbar"></ul>',
                '</div>',
                '<div class="uk-htmleditor-navbar-flip">',
                '<ul class="uk-htmleditor-navbar-nav uk-flex-inline">',
                '<li class="uk-htmleditor-button-code"><a>{:lblCodeview}</a></li>',
                '<li class="uk-htmleditor-button-preview"><a>{:lblPreview}</a></li>',
                '<li><a data-htmleditor-button="fullscreen"><i uk-icon="expand"></i></a></li>',
                '</ul>',
                '</div>',
                '</div>',
                '</div>',
                '<div class="uk-htmleditor-content">',
                '<div class="uk-htmleditor-code"></div>',
                '<div class="uk-htmleditor-preview"><div></div></div>',
                '</div>',
                '</div>'
            ].join(''),
            connected: false
        };
    },

    computed: {

        isConnect: {
            get() {
                return this.connected && find('textarea', this.code);
            },

            watch() {
                if (this.isConnect) {
                    this.create();
                }
            }
        }
    },

    created() {
        const $this = this;
        let tpl = this.template;

        this.CodeMirror = this.CodeMirror || CodeMirror;
        this.buttons = {};

        tpl = tpl.replace(/\{:lblPreview}/g, this.lblPreview);
        tpl = tpl.replace(/\{:lblCodeview}/g, this.lblCodeview);

        this.htmleditor = $(tpl);
        this.content = find('.uk-htmleditor-content', this.htmleditor);
        this.$toolbar = find('.uk-htmleditor-toolbar', this.htmleditor);
        this.preview = find('.uk-htmleditor-preview', this.htmleditor).children[0];
        this.code = find('.uk-htmleditor-code', this.htmleditor);

        this.debouncedRedraw = debounce(() => { $this.redraw(); }, 5);
    },

    beforeConnect() {},

    connected() {
        if (!this.isConnect) {
            before(this.$el, this.htmleditor);
            append(this.code, this.$el);
            this.connected = !this.connected;
        } else {
            this.create();
        }
    },

    dconnected() {
        this.observer && this.observer.disconnect();
    },

    methods: {
        create() {
            Promise.all([
                this.init(),
                this.initBase(),
                this.initMarked(),
                this.debouncedRedraw()
            ]).then(()=>{
                setTimeout(()=>{trigger(this.$el, 'init', [this])}, 5);
            })
        },

        init() {
            const $this = this;

            this.editor = this.CodeMirror.fromTextArea(this.$el, this.codemirror);
            this.editor.htmleditor = this;
            this.editor.on('change', debounce(() => { $this.render(); }, 150));
            this.editor.on('change', () => {
                $this.editor.save();
                trigger($this.$el, 'input');
            });
            css(find('.CodeMirror', this.code), 'height', this.height);

            // iframe mode?
            if (this.iframe) {
                this.$iframe = $('<iframe class="uk-htmleditor-iframe" frameborder="0" scrolling="auto" height="100" width="100%"></iframe>');
                append(this.preview, this.$iframe);

                // must open and close document object to start using it!
                this.$iframe.contentWindow.document.open();
                this.$iframe.contentWindow.document.close();

                this.preview.container = find('body', $(this.$iframe.contentWindow.document));

                // append custom stylesheet
                if (typeof (this.iframe) === 'string') {
                    append(this.preview.container.parentElement, `<link rel="stylesheet" href="${this.iframe}">`);
                }
            } else {
                this.preview.container = this.preview;
            }

            on(window, 'resize load', debounce(() => { $this.fit(); }, 200));

            const previewContainer = this.iframe ? this.preview.container : $this.preview.parentNode;
            const codeContent = find('.CodeMirror-sizer', this.code);
            var codeScroll = find('.CodeMirror-scroll', this.code);

            on(codeScroll, 'scroll', debounce(() => {
                if ($this.htmleditor.getAttribute('data-mode') === 'tab') return;

                // calc position
                const codeHeight = height(codeContent) - height(codeScroll);
                const previewHeight = previewContainer.scrollHeight - ($this.iframe ? height($this.$iframe) : height(previewContainer));
                const ratio = previewHeight / codeHeight;
                const previewPosition = codeScroll.scrollTop * ratio;

                // apply new scroll
                scrollTop(previewContainer, previewPosition);
            }, 10));

            on(this.htmleditor, 'click', '.uk-htmleditor-button-code, .uk-htmleditor-button-preview', (e) => {
                e.preventDefault();

                if ($this.htmleditor.getAttribute('data-mode') == 'tab') {
                    removeClass(findAll('.uk-htmleditor-button-code, .uk-htmleditor-button-preview', $this.htmleditor), 'uk-active');
                    addClass(findAll('.uk-htmleditor-button-code, .uk-htmleditor-button-preview', $this.htmleditor).filter((el) => el === e.target.parentNode), 'uk-active');

                    $this.activetab = hasClass($(e.target.parentNode), 'uk-htmleditor-button-code') ? 'code' : 'preview';
                    attr($this.htmleditor, 'data-active-tab', $this.activetab);
                    $this.editor.refresh();
                }
            });

            // toolbar actions
            on(this.htmleditor, 'click', 'a[data-htmleditor-button]', (e) => {
                const el = closest(e.target, 'a[data-htmleditor-button]');
                if (Array.isArray(el)) return;

                if (!isVisible($this.code)) return;

                trigger($this.$el, `action.${data(el, 'htmleditor-button')}`, [$this.editor]);
            });

            css(this.preview.parentNode, 'height', height(this.code));

            // autocomplete
            if (this.autocomplete && this.CodeMirror.showHint && this.CodeMirror.hint && this.CodeMirror.hint.html) {
                this.editor.on('inputRead', debounce(() => {
                    const doc = $this.editor.getDoc(); const POS = doc.getCursor(); const
                        mode = $this.CodeMirror.innerMode($this.editor.getMode(), $this.editor.getTokenAt(POS).state).mode.name;

                    if (mode == 'xml') { // html depends on xml
                        const cur = $this.editor.getCursor(); const
                            token = $this.editor.getTokenAt(cur);

                        if (token.string.charAt(0) == '<' || token.type == 'attribute') {
                            $this.CodeMirror.showHint($this.editor, $this.CodeMirror.hint.html, { completeSingle: false });
                        }
                    }
                }, 100));
            }

            this.observe(closest(this.$el, 'li'));

            editors.push(this);
        },

        observe(el) {
            const $this = this;

            if (!el) return;

            this.observer = new MutationObserver(() => {
                if (el.style.display !== 'none' && el.classList.contains('uk-active')) {
                    $this.debouncedRedraw();
                }
            });

            this.observer.observe(el, { attributes: true, childList: true });
        },

        addButton(name, button) {
            this.buttons[name] = button;
        },

        addButtons(buttons) {
            Object.assign(this.buttons, buttons);
        },

        replaceInPreview(regexp, callback) {
            const { editor } = this;
            const results = [];
            const value = editor.getValue();
            let offset = -1;
            let index = 0;

            this.currentvalue = this.currentvalue.replace(regexp, function () {
                offset = value.indexOf(arguments[0], ++offset);

                var match = {
                    matches: arguments,
                    from: translateOffset(offset),
                    to: translateOffset(offset + arguments[0].length),
                    replace(value) {
                        editor.replaceRange(value, match.from, match.to);
                    },
                    inRange(cursor) {
                        if (cursor.line === match.from.line && cursor.line === match.to.line) {
                            return cursor.ch >= match.from.ch && cursor.ch < match.to.ch;
                        }

                        return (cursor.line === match.from.line && cursor.ch >= match.from.ch)
                                || (cursor.line > match.from.line && cursor.line < match.to.line)
                                || (cursor.line === match.to.line && cursor.ch < match.to.ch);
                    }
                };

                const result = typeof (callback) === 'string' ? callback : callback(match, index);

                if (!result && result !== '') {
                    return arguments[0];
                }

                index++;

                results.push(match);
                return result;
            });

            function translateOffset(offset) {
                const result = editor.getValue().substring(0, offset).split('\n');
                return { line: result.length - 1, ch: result[result.length - 1].length };
            }

            return results;
        },

        addToolbarButton(item) {
            if (this.toolbar.indexOf(item) === -1) this.toolbar.push(item);
        },

        _buildtoolbar() {
            if (!(this.toolbar && this.toolbar.length)) return;

            const $this = this; const
                bar = [];

            empty(this.$toolbar);

            this.toolbar.forEach((button) => {
                if (!$this.buttons[button]) return;

                const title = $this.buttons[button].title ? $this.buttons[button].title : button;

                bar.push(`<li><a data-htmleditor-button="${button}" title="${title}" data-uk-tooltip>${$this.buttons[button].label}</a></li>`);
            });

            html(this.$toolbar, bar.join('\n'));
        },

        fit() {
            let { mode } = this;

            if (mode == 'split' && width(this.htmleditor) < this.maxsplitsize) {
                mode = 'tab';
            }

            if (mode == 'tab') {
                if (!this.activetab) {
                    this.activetab = 'code';
                    attr(this.htmleditor, 'data-active-tab', this.activetab);
                }

                removeClass(findAll('.uk-htmleditor-button-code, .uk-htmleditor-button-preview', this.htmleditor), 'uk-active');
                addClass(findAll('.uk-htmleditor-button-code, .uk-htmleditor-button-preview', this.htmleditor).filter((el) => hasClass(el, this.activetab == 'code' ? 'uk-htmleditor-button-code' : 'uk-htmleditor-button-preview')), 'uk-active');
            }

            this.editor.refresh();

            css(this.preview.parentNode, 'height', height(this.code) || css($('.CodeMirror', this.code), 'height'));
            attr(this.htmleditor, 'data-mode', mode);
        },

        redraw() {
            this._buildtoolbar();
            this.render();
            this.fit();
        },

        getMode() {
            return this.editor.getOption('mode');
        },

        getCursorMode() {
            const param = { mode: 'html' };
            trigger(this.$el, 'cursorMode', [param]);

            return param.mode;
        },

        render() {
            this.currentvalue = this.editor.getValue();

            if (!this.enablescripts) {
                this.currentvalue = this.currentvalue.replace(/<(script|style)\b[^<]*(?:(?!<\/(script|style)>)<[^<]*)*<\/(script|style)>/img, '');
            }

            // empty code
            if (!this.currentvalue) {
                this.$el.value = '';
                html(this.preview.container, '');

                return;
            }

            trigger(this.$el, 'render', [this]);
            trigger(this.$el, 'renderLate', [this]);

            html(this.preview.container, this.currentvalue);
        },

        addShortcut(name, callback) {
            const map = {};
            if (!Array.isArray(name)) {
                name = [name];
            }

            name.forEach((key) => {
                map[key] = callback;
            });

            this.editor.addKeyMap(map);

            return map;
        },

        addShortcutAction(action, shortcuts) {
            const editor = this;
            this.addShortcut(shortcuts, () => {
                trigger(editor.$el, `action.${action}`, [editor.editor]);
            });
        },

        replaceSelection(replace) {
            let text = this.editor.getSelection();

            if (!text.length) {
                const cur = this.editor.getCursor();
                const curLine = this.editor.getLine(cur.line);
                let start = cur.ch;
                let end = start;

                while (end < curLine.length && /[\w$]+/.test(curLine.charAt(end))) ++end;
                while (start && /[\w$]+/.test(curLine.charAt(start - 1))) --start;

                const curWord = start != end && curLine.slice(start, end);

                if (curWord) {
                    this.editor.setSelection({ line: cur.line, ch: start }, { line: cur.line, ch: end });
                    text = curWord;
                }
            }

            const html = replace.replace('$1', text);

            this.editor.replaceSelection(html, 'end');
            this.editor.focus();
        },

        replaceLine(replace) {
            const pos = this.editor.getDoc().getCursor();
            const text = this.editor.getLine(pos.line);
            const html = replace.replace('$1', text);

            this.editor.replaceRange(html, { line: pos.line, ch: 0 }, { line: pos.line, ch: text.length });
            this.editor.setCursor({ line: pos.line, ch: html.length });
            this.editor.focus();
        },

        save() {
            this.editor.save();
        },

        initBase() {
            const editor = this; const
                $this = this;

            this.transformed = [];

            editor.addButtons({

                fullscreen: {
                    title: 'Fullscreen',
                    label: '<i uk-icon="expand"></i>'
                },
                bold: {
                    title: 'Bold',
                    label: '<i uk-icon="bold"></i>'
                },
                italic: {
                    title: 'Italic',
                    label: '<i uk-icon="italic"></i>'
                },
                strike: {
                    title: 'Strikethrough',
                    label: '<i uk-icon="strikethrough"></i>'
                },
                blockquote: {
                    title: 'Blockquote',
                    label: '<i uk-icon="quote-right"></i>'
                },
                link: {
                    title: 'Link',
                    label: '<i uk-icon="link"></i>'
                },
                image: {
                    title: 'Image',
                    label: '<i uk-icon="image"></i>'
                },
                listUl: {
                    title: 'Unordered List',
                    label: '<i uk-icon="menu"></i>'
                },
                listOl: {
                    title: 'Ordered List',
                    label: '<i uk-icon="list"></i>'
                }

            });

            addAction('bold', '<strong>$1</strong>');
            addAction('italic', '<em>$1</em>');
            addAction('strike', '<del>$1</del>');
            addAction('blockquote', '<blockquote><p>$1</p></blockquote>', 'replaceLine');
            addAction('link', '<a href="http://">$1</a>');
            addAction('image', '<img src="http://" alt="$1">');

            const listfn = function (tag) {
                if (editor.getCursorMode() == 'html') {
                    tag = tag || 'ul';

                    const cm = editor.editor;
                    const doc = cm.getDoc();
                    const pos = doc.getCursor(true);
                    const posend = doc.getCursor(false);
                    const im = CodeMirror.innerMode(cm.getMode(), cm.getTokenAt(cm.getCursor()).state);
                    const inList = im && im.state && im.state.context && ['ul', 'ol'].indexOf(im.state.context.tagName) != -1;

                    for (let i = pos.line; i < (posend.line + 1); i++) {
                        cm.replaceRange(`<li>${cm.getLine(i)}</li>`, { line: i, ch: 0 }, { line: i, ch: cm.getLine(i).length });
                    }

                    if (!inList) {
                        cm.replaceRange(`<${tag}>` + `\n${cm.getLine(pos.line)}`, { line: pos.line, ch: 0 }, { line: pos.line, ch: cm.getLine(pos.line).length });
                        cm.replaceRange(`${cm.getLine((posend.line + 1))}\n` + `</${tag}>`, { line: (posend.line + 1), ch: 0 }, { line: (posend.line + 1), ch: cm.getLine((posend.line + 1)).length });
                        cm.setCursor({ line: posend.line + 1, ch: cm.getLine(posend.line + 1).length });
                    } else {
                        cm.setCursor({ line: posend.line, ch: cm.getLine(posend.line).length });
                    }

                    cm.focus();
                }
            };

            on(editor.$el, 'action.listUl', () => {
                listfn('ul');
            });

            on(editor.$el, 'action.listOl', () => {
                listfn('ol');
            });

            on(editor.htmleditor, 'click', 'a[data-htmleditor-button="fullscreen"]', (e) => {
                const el = closest(e.target, 'a[data-htmleditor-button]');
                if (Array.isArray(el)) return;

                const offsetTop = offset(window).top;

                toggleFullscreen();
                toggleClass(editor.htmleditor, 'uk-htmleditor-resize');
                toggleClass(editor.htmleditor, 'uk-htmleditor-fullscreen');

                const wrap = editor.editor.getWrapperElement();

                if (hasClass(editor.htmleditor, 'uk-htmleditor-fullscreen')) {
                    let fixedParent = false;
                    const $parents = parents(editor.htmleditor, '*');

                    $parents.forEach((el) => {
                        if (css(el, 'position') === 'fixed' && el.tagName !== 'html') {
                            fixedParent = el;
                        }
                    });

                    attr(editor.htmleditor, 'data-fixed-parents', false);

                    if (fixedParent) {

                        fixedParent = $parents.filter((el) => (fixedParent.tagName !== 'html' && fixedParent.parentNode.outerHTML.indexOf(el.outerHTML) !== -1 && fixedParent.parentNode !== el));

                        fixedParent.forEach((el) => {
                            if (css(el, 'transform') != 'none') {
                                attr(el, 'data-transform-reset', JSON.stringify({
                                    transform: el.style.transform,
                                    '-webkit-transform': el.style.webkitTransform,
                                    '-webkit-transition': el.style.webkitTransition,
                                    transition: el.style.transition
                                }));
                                css(el, {
                                    transform: 'none',
                                    '-webkit-transform': 'none',
                                    '-webkit-transition': 'none',
                                    transition: 'none'
                                });
                                $this.transformed.push(el);
                            }
                        });

                        attr(editor.htmleditor, 'data-fixed-parents', $this.transformed);
                    }

                    editor.editor.state.fullScreenRestore = { scrollTop: window.pageYOffset, scrollLeft: window.pageXOffset, width: wrap.style.width, height: wrap.style.height };
                    editor.editor.state.fullScreenRestore.scrollTop = offsetTop;
                    wrap.style.width = '';
                    wrap.style.height = `${height(editor.content)}px`;
                } else {
                    document.documentElement.style.overflow = '';
                    const info = editor.editor.state.fullScreenRestore;
                    wrap.style.width = info.width; 
                    wrap.style.height = info.height;

                    window.scrollTo(info.scrollLeft, info.scrollTop);

                    if (data(editor.htmleditor, 'fixed-parents') !== 'false') {
                        $this.transformed.forEach((parent) => {
                            css(parent, JSON.parse(data(parent, 'transform-reset')));
                            removeAttr(parent, 'data-transform-reset');
                        });

                        $this.transformed = [];
                        removeAttr(editor.htmleditor, 'data-fixed-parents');
                    }
                }

                setTimeout(() => {
                    editor.fit();
                    trigger(window, 'resize');
                }, 50);

                setTimeout(() => {
                    toggleClass(editor.htmleditor, 'uk-htmleditor-resize');
                }, 100);

                function toggleFullscreen() {
                    toggleClass(document.documentElement, 'uk-htmleditor-fullscreen-wrap');
                    toggleClass(document.body, 'uk-htmleditor-fullscreen-wrap');
                }
            });

            editor.addShortcut(['Ctrl-S', 'Cmd-S'], () => {
                trigger(editor.$el, 'htmleditor-save', [editor]);
            });
            editor.addShortcutAction('bold', ['Ctrl-B', 'Cmd-B']);

            function addAction(name, replace, mode) {
                on(editor.$el, `action.${name}`, (e) => {
                    if (editor.getCursorMode() == 'html') {
                        editor[mode == 'replaceLine' ? 'replaceLine' : 'replaceSelection'](replace);
                    }
                });
            }
        },

        initMarked() {
            const editor = this;

            const parser = editor.mdparser || window.marked || null;

            if (!parser) return;

            if (editor.markdown) {
                enableMarkdown();
            }

            addAction('bold', '**$1**');
            addAction('italic', '*$1*');
            addAction('strike', '~~$1~~');
            addAction('blockquote', '> $1', 'replaceLine');
            addAction('link', '[$1](http://)');
            addAction('image', '![$1](http://)');

            on(editor.$el, 'action.listUl', () => {
                if (editor.getCursorMode() == 'markdown') {
                    const cm = editor.editor;
                    const pos = cm.getDoc().getCursor(true);
                    const posend = cm.getDoc().getCursor(false);

                    for (let i = pos.line; i < (posend.line + 1); i++) {
                        cm.replaceRange(`* ${cm.getLine(i)}`, { line: i, ch: 0 }, { line: i, ch: cm.getLine(i).length });
                    }

                    cm.setCursor({ line: posend.line, ch: cm.getLine(posend.line).length });
                    cm.focus();
                }
            });

            on(editor.$el, 'action.listOl', () => {
                if (editor.getCursorMode() == 'markdown') {
                    const cm = editor.editor;
                    const pos = cm.getDoc().getCursor(true);
                    const posend = cm.getDoc().getCursor(false);
                    let prefix = 1;

                    if (pos.line > 0) {
                        const prevline = cm.getLine(pos.line - 1); let
                            matches;

                        if (matches = prevline.match(/^(\d+)\./)) {
                            prefix = Number(matches[1]) + 1;
                        }
                    }

                    for (let i = pos.line; i < (posend.line + 1); i++) {
                        cm.replaceRange(`${prefix}. ${cm.getLine(i)}`, { line: i, ch: 0 }, { line: i, ch: cm.getLine(i).length });
                        prefix++;
                    }

                    cm.setCursor({ line: posend.line, ch: cm.getLine(posend.line).length });
                    cm.focus();
                }
            });

            on(editor.$el, 'renderLate', () => {
                if (editor.editor.options.mode == 'gfm') {
                    editor.currentvalue = parser(editor.currentvalue);
                }
            });

            on(editor.$el, 'cursorMode', (e, param) => {
                if (editor.editor.options.mode == 'gfm') {
                    const pos = editor.editor.getDoc().getCursor();
                    if (!editor.editor.getTokenAt(pos).state.base.htmlState) {
                        param.mode = 'markdown';
                    }
                }
            });

            Object.assign(editor, {

                enableMarkdown() {
                    enableMarkdown();
                    this.render();
                },
                disableMarkdown() {
                    this.editor.setOption('mode', 'htmlmixed');
                    html(find('.uk-htmleditor-button-code a', this.htmleditor), this.lblCodeview);
                    this.render();
                }

            });

            // switch markdown mode on event
            on(editor.$el, 'enableMarkdown', () => { editor.enableMarkdown(); });
            on(editor.$el, 'disableMarkdown', () => { editor.disableMarkdown(); });

            function enableMarkdown() {
                editor.editor.setOption('mode', 'gfm');
                html(find('.uk-htmleditor-button-code a', editor.htmleditor), editor.lblMarkedview);
            }

            function addAction(name, replace, mode) {
                on(editor.$el, `action.${name}`, () => {
                    if (editor.getCursorMode() == 'markdown') {
                        editor[mode == 'replaceLine' ? 'replaceLine' : 'replaceSelection'](replace);
                    }
                });
            }
        }

    }

});

function debounce(func, wait, immediate) {
    let timeout;
    return function () {
        const context = this; const
            args = arguments;
        const later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

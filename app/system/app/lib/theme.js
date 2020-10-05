/**
 * Theme - render various theme elements.
 *
 * @author     uatrend
 * @url        https://github.com/uatrend/pagekit
 */

import { $, $$, addClass, getStyle, on } from 'uikit-util';

const $helper = '[ThemeHelper]';

const ElementTypes = ['dropdown', 'button', 'iconnav', 'caption', 'search', 'pagination'];
const Protected = ['sidemenu.additem', 'sidemenu.menuitem'];

const isString = function (value) {
    return typeof value === 'string';
};

const isFunction = function (obj) {
    return typeof obj === 'function';
};

const isObject = function (obj) {
    return obj !== null && typeof obj === 'object';
};

const isUndefined = function (value) {
    return value === undefined;
};

const isHtml = function (str) {
    return str[0] === '<' || str.match(/^\s*</);
};

const objLength = function (obj) {
    return isObject(obj) ? Object.keys(obj).length ? Object.keys(obj).length : '' : '';
};

const isElement = function (type) {
    return type && ElementTypes.indexOf(type) !== -1;
};

const actionIcons = function (name) {
    const self = this;
    const icons = {
        publish: { icon: 'check', label: 'Publish', class: 'uk-text-success' },
        unpublish: { icon: 'ban', label: 'Unpublish', class: 'uk-text-danger' },
        move: { icon: 'move', label: 'Move' },
        copy: { icon: 'copy', label: 'Copy' },
        trash: { icon: 'trash', label: 'Trash' },
        rename: { icon: 'file-edit', label: 'Rename' },
        remove: { icon: 'trash', label: 'Remove' },
        table: { icon: 'table', label: 'Table View' },
        thumbnails: { icon: 'thumbnails', label: 'Thumbnails View' },
        delete: { icon: 'trash', label: 'Delete' },
        spam: { icon: 'hand', label: 'Mark as spam' },
        get icon() {
            const item = this[name];
            const element = {
                'uk-icon': item.icon,
                'uk-tooltip': self.$trans(item.label)
            };
            if (item.class) element.class = item.class;
            return element;
        }
    };

    return objLength(icons[name]) && icons.icon;
};

const log = function (type, key, value) {
    const name = this.name && `[${this.name.charAt(0).toUpperCase()}${this.name.slice(1)}]`;
    const messages = {
        exist: `${$helper} - ${name} Can't create element '${value}' - already exist, extend if needed.`,
        empty: `${$helper} - ${name} The '${value}' is not an element or has empty items.`,
        protected: `${$helper} - ${name} The element '${value}' can't be change, extend if needed.`,
        tab_ele_uniq: `${$helper}${name} Unable to create tabs, element '${value}' not found or not unique on page.`,
        tab_connect: `${$helper} - ${name} Unable to create tabs, connected tabs '${value}' not found.`,
        watch_fn: `${$helper} - ${name} Watch property of object - '${value}' -  must be a function.`
    };

    return console[type] ? console[type](messages[key] ? messages[key] : 'Error') : ''; // eslint-disable-line no-console
};

function renderElement(h, element, _vue) {
    const $this = _vue;
    const { $trans } = _vue;
    const { $theme } = _vue.$parent;
    const get = $theme.get.bind($theme);
    const Icons = actionIcons.bind(_vue);
    const addProps = function (ele, item) {
        if (!item.type) item.type = 'button';
        if (get(ele, 'actionDropdown')) {
            item.class = `${item.class} uk-dropdown-close`;
        }
        if (get(ele, 'actionIcons')) {
            item.icon = {
                attrs: Icons(item.name),
                class: ['uk-margin-small-right', Icons(item.name).class],
                internal: true
            };
            item.class = item.type !== 'dropdown' ? item.class ? `${item.class} uk-dropdown-close` : 'uk-dropdown-close' : '';
            item.actionDropdown = item.type === 'dropdown';
            item.caption = $trans(get(item, 'caption')) || Icons(item.name)['uk-tooltip'];
        }
        return item;
    };

    const render = {
        dropdown() {
            const dropdownOptions = () => {
                const options = get(this, 'dropdown.options');

                if (!/topmenu/.test(this.scope)) return options;
                return `${options}; offset: ${$this.offset}`;
            };
            return [
                render.button.call(this),
                h('div', {
                    attrs: { 'uk-dropdown': dropdownOptions() },
                    class: get(this, 'dropdown.class'),
                    style: get(this, 'dropdown.style')
                }, [
                    h('ul', { class: 'uk-nav uk-dropdown-nav' }, [
                        $this.orderBy(get(this, 'items'), 'priority').map((item, name) => {
                            item = addProps(this, item);
                            return get(item, 'vif') && h('li', { class: { 'has-dropdown': item.actionDropdown } }, [
                                render[item.type].call(item)
                            ]);
                        })

                    ])
                ])
            ];
        },
        button() {
            const spinner = get(this, 'spinner');
            const icon = _.has(this, 'icon') && get(this, 'icon.vif');
            const internal = icon && get(this, 'icon.internal');
            const getText = (text) => {
                let output; let content;
                let replace;

                if (isHtml(text)) {
                    output = $(`<div>${text}</div>`);
                    content = output.textContent.trim();
                    replace = $trans(content);
                    output = Vue.compile(`<div>${output.innerHTML.replace(content, replace)}</div>`);
                    if (typeof output.staticRenderFns[0] === 'function') {
                        output = output.staticRenderFns[0].call($this, h);
                        return output.children;
                    }
                    return text;
                }
                return $trans(text);
            };
            const elements = (h) => { // eslint-disable-line no-shadow
                const elms = [];

                spinner && elms.push([
                    render.spinner(h)
                ]);
                icon && internal && elms.push([
                    h('span', {
                        attrs: get(this, 'icon.attrs'),
                        class: get(this, 'icon.class')
                    })
                ]);
                if (get(this, 'caption')) {
                    if ((icon || spinner) && !internal) {
                        elms.push([
                            h('span', { class: 'uk-text-middle' }, getText(get(this, 'caption')))
                        ]);
                    } else if (icon && internal) {
                        elms.push([
                            h('span', { class: 'uk-text-middle' }, getText(get(this, 'caption')))
                        ]);
                    } else if (!icon) {
                        elms.push([
                            getText(get(this, 'caption'))
                        ]);
                    }
                }
                return elms;
            };

            return h('a', {
                attrs: internal ? get(this, 'attrs') : _.merge({}, get(this, 'attrs'), get(this, 'icon.attrs')),
                class: get(this, 'class'),
                style: get(this, 'style'),
                on: { click: (e) => get(this, 'on.click', e) },
                directives: Array.isArray(get(this, 'directives')) ? get(this, 'directives') : []
            }, elements(h));
        },
        iconnav() {
            const create = function (h, item) { // eslint-disable-line no-shadow
                const ele = {
                    type: 'button',
                    icon: { attrs: Icons(item.name) },
                    class: Icons(item.name).class
                };

                _.merge(ele, item);
                return render[ele.type].call(ele);
            };
            return h('ul', { class: 'uk-iconnav' }, get(this, 'items').map((item) => get(item, 'vif') && h('li', { class: { 'uk-active': get(item, 'active') } }, [create(h, item)])));
        },
        caption() {
            if (get(this, 'scope') === 'breadcrumbs') {
                return h('span', { class: 'tm-breadcrumbs-item' }, [get(this, 'caption')]);
            }
            if (get(this, 'class')) {
                return h('span', { class: get(this, 'class') }, [get(this, 'caption')]);
            }
            return get(this, 'caption');
        },
        pagination() {
            return h('div', {
                class: 'uk-flex uk-flex-middle'
            }, [
                h('span', {
                    class: 'uk-margin-small-right uk-text-small'
                }, $trans(get(this, 'caption'))),
                h('v-pagination', {
                    props: get(this, 'props'),
                    attrs: get(this, 'attrs'),
                    class: 'uk-margin-remove',
                    on: {
                        input: (e) => get(this, 'on.input', e)
                    }
                }, [])
            ]);
        },
        search() {
            Object.assign($this, { SearchElement: get(element, 'domProps.value') });
            return h('form', {
                class: ['uk-search uk-search-default', get(this, 'class')]
            }, [
                h('span', {
                    attrs: { 'uk-search-icon': true },
                    class: ['uk-search-icon-flip', !$this.SearchElement ? '' : 'uk-hidden']
                }, []),
                h('input', {
                    attrs: _.extend({
                        type: 'search',
                        placeholder: `${$trans('Search')}...`
                    }, get(this, 'attrs')),
                    class: 'uk-search-input',
                    domProps: {
                        value: get(this, 'domProps.value')
                    },
                    on: {
                        input: (e) => {
                            $this.SearchElement = e.target.value;
                            return get(this, 'on.input', e);
                        }
                    },
                    ref: 'SearchElement'
                }),
                h('a', {
                    attrs: { 'uk-icon': 'close' },
                    class: ['uk-form-icon uk-form-icon-flip', $this.SearchElement ? '' : 'uk-hidden'],
                    on: {
                        click: () => {
                            const event = document.createEvent('Event');
                            const input = $this.$refs.SearchElement;

                            event.initEvent('input', true, true);
                            input.value = '';
                            input.dispatchEvent(event);
                        }
                    }
                }, [])
            ]);
        },
        spinner() {
            return h('span', {
                class: 'tm-spinner-bounce uk-margin-small-right'
            }, [
                h('span', { class: 'tm-bounce1' }),
                h('span', { class: 'tm-bounce2' }),
                h('span', { class: 'tm-bounce3' })
            ]);
        }
    };

    return (element.type && typeof render[element.type] === 'function') ? render[element.type].call(element) : '';
}

const renderScope = function (h, scope, dir) {
    const $theme = this.$parent.$theme;
    const get = $theme.get.bind($theme);
    const getScope = $theme.getScope.bind($theme);
    const isScopeEmpty = $theme.isScopeEmpty.bind($theme);
    const isElementEmpty = $theme.isElementEmpty.bind($theme);
    const items = getScope(dir || scope);
    const isEmpty = isScopeEmpty(items);

    const render = {
        topmenu: () => h('div', {
            class: [`tm-${dir}`]
        }, [
            h('div', { class: ['tm-topmenu-item'] }, [
                h('ul', { class: 'tm-action-buttons uk-visible@m' },
                    this.orderBy(items, 'priority').map((element) => !isElementEmpty(element) && get(element, 'vif')
                            && h('li', { class: { 'uk-disabled': get(element, 'disabled') } }, [renderElement(h, element, this)])))
            ])
        ]),
        sidemenu: () => {
            const sb = this.$parent.sb;
            const breakpoint = this.$parent.breakpoint;

            return h('ul', { class: ['tm-action-buttons', !sb ? 'uk-nav uk-nav-default' : !breakpoint ? 'uk-nav uk-nav-default' : 'uk-iconnav', { 'uk-flex-nowrap': sb && isEmpty }] }, [
                h('li', { class: ['tm-toggle-sidebar', { 'uk-flex-last': sb }] }, [
                    h('a', {
                        on: { click: () => this.$parent.toggleSidebar() },
                        attrs: { 'uk-tooltip': !sb ? this.$trans('Show') : false, pos: !sb ? 'right' : false }
                    }, [h('span', { attrs: { 'uk-icon': !sb ? 'more' : 'more-vertical' }, class: 'tm-menu-image' })])
                ]),
                !isEmpty && this.$parent.sbReady
                && this.orderBy(items, 'priority').map((element, id) => !isElementEmpty(element) && get(element, 'vif')
                    && h('li', { class: [{ 'uk-disabled': get(element, 'disabled') }, { 'uk-flex-first': !id }] }, [renderElement(h, element, this)])),
                sb && h('li', { class: 'uk-width-expand' }, [
                    isEmpty
                    && h('div', { class: ['uk-height-1-1 uk-flex uk-flex-middle uk-light', { 'uk-hidden': !breakpoint }] }, [h('span', this.$trans('Extensions'))])
                ])
            ]);
        },
        breadcrumbs: () => (items.length === 1) && this.orderBy(items, 'priority').map((element) => !isElementEmpty(element) && get(element, 'vif')
                && h('li', {
                    class: [{ 'uk-disabled': get(element, 'disabled') }]
                }, [renderElement(h, element, this)])),
        navbar: () => h('div', { class: [dir === 'navbar-right' ? 'uk-margin-right' : 'uk-navbar-item', 'uk-visible@m'] },
            this.orderBy(items, 'priority').map((element) => !isElementEmpty(element) && get(element, 'vif')
                    && renderElement(h, element, this)))
    };

    return (typeof render[scope] === 'function') ? !isEmpty && render[scope].call() : '';
};

const ThemeTopMenu = {
    render(h) {
        const $theme = this.$parent.$theme;
        const scopes = ['topmenu-left', 'topmenu-center', 'topmenu-right'];
        const isScopes = scopes.map((scope) => $theme.isScopeEmpty($theme.getScope(scope))).filter((item) => !item).length;

        return isScopes && h('div', { class: 'tm-topmenu uk-visible@m' }, scopes.map((scope) => renderScope.call(this, h, 'topmenu', scope)));
    },
    created() {
        // define dropdown top offset
        this.offset = parseInt(getStyle($('.uk-navbar-container'), 'paddingBottom'));
    }
};

const ThemeSideMenu = {
    render(h) {
        return renderScope.call(this, h, 'sidemenu');
    }
};

const ThemeBreadcrumbs = {
    render(h) {
        return renderScope.call(this, h, 'breadcrumbs');
    }
};

const ThemeNavbarItems = {
    props: ['dir'],
    render(h) {
        const scope = `navbar-${this.dir}`;

        return renderScope.call(this, h, 'navbar', scope);
    }
};

const ThemeMixins = Object.freeze({
    Helper: {
        created() {
            if (window.Theme && isFunction(window.Theme.$helper)) {
                const Helper = window.Theme.$helper;

                this.$theme = new Helper(this);
            }
        },
        methods: {
            objLength
        }
    },
    Components: {
        components: {
            ThemeTopMenu,
            ThemeSideMenu,
            ThemeBreadcrumbs,
            ThemeNavbarItems
        }
    },
    UIElements: {
        data: () => ({ $UIElements: {} }),
        created() {
            if (window.Theme && isFunction(window.Theme.$ui)) {
                const UIElements = window.Theme.$ui;

                this.$ui = new UIElements(this);
            }
        }
    }
});

const Theme = function () {
    this._init();
    this._initVM();
    this._initMixins();
};

Theme.prototype._init = function () {
    window.Theme && Object.assign(this, window.Theme);
};

Theme.prototype._initVM = function () {
    this.$vm = new Vue({ data: () => ({ Elements: {} }) });
};

Theme.prototype._initMixins = function () {
    Object.assign(this, { Mixins: ThemeMixins });
};

Theme.prototype._mountMenu = function (menus) {
    const data = menus;

    Vue.Promise.all(data.map((item) => {
        item.$instance = Vue.extend(item.object);
        return new item.$instance();
    })).then((Components) => {
        this.$vm.$emit('theme-menu-created');
        Components.forEach((Component, idx) => {
            Component.$mount(data[idx].target);
        });
    });
};

Theme.prototype.$mount = function (data) {
    const menus = data.filter((item) => item.object.type === 'theme-menu');

    menus.length && this._mountMenu(menus);
};

Object.defineProperties(Theme.prototype, {
    $helper: {
        get() {
            return ThemeHelper;
        },
        enumerable: false
    },
    $ui: {
        get() {
            return UIElements;
        },
        enumerable: false
    }
});

const ThemeHelper = function (_vue) {
    if (!_vue._isVue) {
        return;
    }
    this._init(_vue);
};

ThemeHelper.prototype._init = function (_vue) {
    this.$vm = _vue;
    this._initEvents();
};

ThemeHelper.prototype._initEvents = function () {
    this.theme.$on('theme-menu-created', () => this.createElements().then(() => this.theme.$emit('theme-menu-items-ready')));
    this.theme.$on('theme-menu-items-ready', () => this.extendElements());
    this.$vm.$on('hook:mounted', () => this.hideDomEls());
};

ThemeHelper.prototype.createElements = function () {
    return Promise.all([this.addElements(), this.callWatches()]);
};

ThemeHelper.prototype.addElements = function () {
    const elements = this.elementsOption;

    elements && isObject(elements) && !Array.isArray(elements)
    && _.forEach(elements, (element, name) => {
        if (!element.scope) return;
        const path = [element.scope, name].join('.');

        if (isObject(element) && isElement(element.type) && !this.isProtected(path)) {
            try {
                if (!this.hasProp(path) || (this.hasProp(path) && !isUndefined(element.watch))) {
                    this.setProp(path, element);
                }
            } catch (e) {
                console.error(`${$helper} ${e}`);
            }
        } else {
            this.isProtected(path) && this.log('warn', 'protected', path);
            (!isObject(element) || !isElement(element.type)) && !this.isProtected(path) && this.log('warn', 'empty', path);
        }
    });
};

ThemeHelper.prototype.extendElements = function () {};

ThemeHelper.prototype.callWatches = function () {
    const elements = this.elementsOption;
    const theme = this;

    elements
    && _.forEach(elements, (element, key) => {
        if (!isUndefined(element.watch)) {
            if (isFunction(element.watch)) {
                this.$vm.$watch(element.watch, this.addElements.bind(theme));
            } else {
                this.log('error', 'watch_fn', key);
            }
        }
    });
};

ThemeHelper.prototype.getScope = function (prop) {
    return Object.values(this.getProp(prop));
};

ThemeHelper.prototype.getProp = function (prop) {
    return !_.isEmpty(this.elements) && this.hasProp(prop) && _.get(this.elements, prop);
};

ThemeHelper.prototype.setProp = function (prop, value) {
    return _.set(this.elements, prop, value);
};

ThemeHelper.prototype.hasProp = function (prop) {
    return _.has(this.elements, prop);
};

ThemeHelper.prototype.test = function (value, prop, e) {
    if (isUndefined(value)) return false;
    if (isFunction(value)) {
        return this.testValue(value.call(this, e), prop);
    }
    if (isObject(value)) {
        if (this.testProp(prop, 'items')) {
            if (!Array.isArray(value)) {
                value = _.forEach(value, (item, key) => {
                    item.name = key;
                    return item;
                });
                return Object.values(value);
            }
        }
        return value;
    }
    return value;
};

ThemeHelper.prototype.get = function (item, prop, e) {
    if (!isObject(item)) return;

    const value = _.get(item, prop);

    if (this.testProp(prop, 'vif')) {
        return isUndefined(value) ? true : !!this.testValue(value);
    }
    if (this.testProp(prop, 'disabled')) return isUndefined(value) ? false : this.testValue(value);

    if (this.testProp(prop, 'attrs') || this.testProp(prop, 'props')) {
        const attrs = this.testValue(value, prop);
        _.forEach(attrs, (attr_value, attr) => {
            attrs[attr] = this.testValue(attr_value, attr);
        });
        return attrs;
    }

    if (this.testProp(prop, 'items') && !value) {
        return [];
    }

    return this.testValue(value, prop, e);
};

ThemeHelper.prototype.testValue = function (value, prop, e) {
    if (isUndefined(value)) return false;
    if (isFunction(value)) {
        return this.testValue(value.call(this, e), prop);
    }
    if (isObject(value)) {
        if (this.testProp(prop, 'items')) {
            if (!Array.isArray(value)) {
                value = _.forEach(value, (item, key) => {
                    item.name = key;
                    return item;
                });
                return Object.values(value);
            }
        }
        return value;
    }
    return value;
};

ThemeHelper.prototype.testProp = function (prop, attr) {
    return prop && prop.indexOf(attr) !== -1;
};

ThemeHelper.prototype.isElementEmpty = function (element) {
    const type = element.type;

    if (!isElement(type)) return true;

    if (type === 'dropdown') {
        const items = this.get(element, 'items');
        return !(Array.isArray(items) && objLength(items));
    }

    return false;
};

ThemeHelper.prototype.isScopeEmpty = function (menu) {
    return !(objLength(menu) && Object.values(menu).map((ele) => !this.isElementEmpty(ele)).filter((val) => val !== false).length);
};

ThemeHelper.prototype.isProtected = function (path) {
    const q = (Protected.indexOf(path) !== -1);

    if (q && !this.hasProp(path)) {
        return false;
    }
    return path && q;
};

ThemeHelper.prototype.getActiveTab = function (name, current) {
    let vue = this.$vm.$parent;
    const path = [name, 'activeTab'].join('.');

    if (current && current._isVue) {
        vue = current;
    }
    if (current && typeof current === 'boolean') {
        return _.get(vue.$data.$UIElements, path) === this.name;
    }
    return _.get(vue.$data.$UIElements, path);
};

ThemeHelper.prototype.actionIcons = function (name) {
    return actionIcons.call(this.$vm, name);
};

ThemeHelper.prototype.hideDomEls = function () {
    if (this.option && this.option.hideEls) {
        let elements = this.option.hideEls;

        if (isFunction(elements)) {
            elements = elements.call(this.$vm);
        }

        const hide = function (el) {
            const nodes = $$(el);
            nodes.forEach((node) => addClass($(node), 'uk-hidden@m'));
        };

        if (Array.isArray(elements)) {
            elements.forEach((element) => hide(element));
        } else if (isString(elements) || elements instanceof NodeList || elements instanceof Element) {
            hide(elements);
        }
    }
};

ThemeHelper.prototype.getDomElement = function (el) {
    let ele = '';

    if (!el || !$$(el).length) return;

    if (typeof el === 'object') {
        ele = ele + el.tagName.toLowerCase();
        ele = ele + (el.getAttribute('id') ? `#${el.getAttribute('id')} ` : ' ');
        ele = ele + (el.getAttribute('class') ? el.getAttribute('class') : '');

        return ele.split(' ').join('.');
    }

    return el;
};

Object.defineProperties(ThemeHelper.prototype, {
    theme: {
        get() {
            const theme = window.Theme;
            return (theme && theme.$vm) ? theme.$vm : null;
        }
    },
    elements: {
        get() {
            return (this.theme && this.theme.Elements) ? this.theme.Elements : null;
        }
    },
    option: {
        get() {
            return (this.$vm && isObject(this.$vm.$options.theme)) ? this.$vm.$options.theme : null;
        }
    },
    elementsOption: {
        get() {
            return (this.option && isFunction(this.option.elements)) ? this.option.elements.bind(this.$vm).call() : null;
        }
    },
    extendOption: {
        get() {
            return (this.option && isFunction(this.option.elements)) ? this.option.elements.bind(this.$vm).call() : null;
        }
    },
    name: {
        get() {
            return this.$vm.$options.name || this.$vm.$options._componentTag;
        }
    },
    log: {
        get() {
            return log.bind(this);
        }
    }
});

const UITab = function (name, ele, option) {
    if (!$(ele) && ($$(ele).length > 1)) {
        this.$theme.log('warn', 'tab_ele_uniq', ele);
        return;
    }

    if (!option.connect || !$$(option.connect).length) {
        this.$theme.log('warn', 'tab_connect', option.connect);
        return;
    }

    if (!_.has(this.$data, '$UIElements')) return;

    const elements = this.$data.$UIElements;

    this.$set(elements, name, { tab: {}, type: 'tab', activeTab: '' });

    const element = elements[name];
    let stateName;

    if (option.state) {
        stateName = `${this.$theme.name.replace(/\-|\//g, '')}.${name}`;
        element.activeIndex = this.$session.get(stateName, 0);
    }

    this.$on('hook:mounted', function () {
        const self = this;

        option.connect = this.$theme.getDomElement(option.connect);

        element.tab = UIkit.switcher(ele, option);
        on(element.tab.connects, 'show', (e, tab) => {
            if (tab !== element.tab) return false;
            let baseComponent = self;
            if (_.has(self.$children[0], '$_veeObserver')) {
                baseComponent = self.$children[0];
            }
            baseComponent.$children.forEach((component, idx) => {
                if (component.$el === e.target.firstChild) {
                    const tabName = component.$options.name || component.$options._componentTag;
                    self.$set(element, 'activeIndex', idx);
                    self.$set(element, 'activeTab', tabName);
                    if (option.state) {
                        self.$session.set(stateName, idx);
                    }
                }
            });
        });

        element.tab.toggles.forEach((item, idx) => {
            if (item.parentNode.classList.contains('uk-active')) {
                element.activeIndex = idx;
            }
        });

        element.tab.show(element.activeIndex);
    });
};

const UIElements = function (_vue) {
    this._init(_vue);
};

UIElements.prototype._init = function (_vue) {
    this.tab = UITab.bind(_vue);
};

export default {
    install(Vue) {
        window.Theme = new Theme();
    }
};

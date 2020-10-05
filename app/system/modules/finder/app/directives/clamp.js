// Bases on https://jsfiddle.net/8cj045t7/1/

import { on, css, width } from 'uikit-util';

const isEllipsisExist = function (lines, ellipsis) {
    return lines.join('').indexOf(ellipsis) >= 0;
};

const getContext = function (el, canvas) {
    const fontParams = css(el, ['font-style', 'font-variant', 'font-weight', 'font-size', 'font-family']);
    const font = Object.values(fontParams).join(' ').replace(/ +/g, ' ').trim();
    const context = canvas.getContext('2d');
    context.font = font;
    return context;
};

const getContextWidth = function (text, context) {
    return context.measureText(text).width;
};

// TODO
const middlePosition = function (lines, options) {
    const { string, lineCount, elWidth, ellipsis, context } = options;

    let lastLine = string.replace(lines.filter((line, id) => id < lineCount - 1).join(''), '');

    if (!lastLine) return lines;

    let prevent = 0;
    let middle = 0;
    let lastLineWidth = getContextWidth(lastLine, context);

    if ((lastLineWidth > elWidth)) {
        lines = lines.slice(0, lineCount - 1);
        while (lastLineWidth > elWidth) {
            prevent++;
            middle = Number.parseInt(lastLine.length / 2, 0);
            lastLine = `${lastLine.slice(0, -middle).slice(0, -ellipsis.length)}${ellipsis}${lastLine.slice(middle).slice(ellipsis.length)}`;
            lastLineWidth = getContextWidth(lastLine, context);
            if (prevent === 1000) { break; } // Prevent Loop
        }
        lines.push(lastLine);
    }

    return lines;
};

const getLines = function (options) {
    const { string, elWidth, lineCount, middle, ellipsis, context } = options;
    const lines = [];
    let { word } = options;
    let symbols = word ? string.split(' ') : Array.from(string);
    let piece = '';
    let pieceWidth = 0;
    let processed = true;

    if ((symbols.length === 1) && word) {
        word = false;
        symbols = Array.from(string);
    }

    let line = symbols[0];

    for (let i = 1; i < symbols.length && processed; i++) {
        piece = word ? `${line} ${symbols[i]}` : line + symbols[i];
        pieceWidth = getContextWidth(piece, context);

        if (pieceWidth <= elWidth) {
            line = piece;
        } else if (lines.length < lineCount - 1) {
            lines.push(line);
            line = symbols[i];
        } else {
            if (!middle) {
                piece = line + ellipsis;
            }
            pieceWidth = getContextWidth(piece, context);
            if (pieceWidth <= elWidth) {
                line = piece;
            } else if (!middle) {
                line = line.slice(0, -ellipsis.length) + ellipsis;
            }
            lines.push(piece);
            line = '';
            processed = false;
        }

        if (i === symbols.length - 1 && processed) {
            lines.push(line);
            processed = false;
        }
    }

    return lines;
};

const getSiblings = function (elem) {
    const siblings = [];
    let sibling = elem.parentNode.firstChild;
    for (; sibling; sibling = sibling.nextSibling) {
        if ((sibling.nodeType === 1) && (sibling !== elem)) {
            siblings.push(sibling);
        }
    }

    return siblings;
};

const getSiblingsWidth = function (siblings) {
    return siblings.reduce((a, b) => a + b.offsetWidth, 0);
};

const getContainerWidth = function (options) {
    const { el, container } = options;
    const containerEl = el.closest(container) || el.offsetParent;
    const containerWidth = width(containerEl);
    const siblings = getSiblings(el.parentNode);
    let elWidth = width(el);

    if (containerEl && siblings.length) {
        elWidth = containerWidth - getSiblingsWidth(siblings);
    } else if (!siblings.length) {
        elWidth = containerWidth;
    }

    return elWidth;
};

const formatSting = function (options) {
    const { el, string, canvas, ellipsis, middle, word } = options;
    const elWidth = getContainerWidth(options);
    const context = getContext(el, canvas);
    const stringWidth = getContextWidth(string, context);

    let lines = [];

    // Prevent, if string already match container width.
    if (stringWidth <= elWidth) {
        if (el.innerHTML.trim().length !== string.length) {
            el.innerHTML = el.$dataOriginal;
        }
        return;
    }

    options.elWidth = elWidth;
    options.context = context;

    lines = getLines(options);

    if (middle) {
        lines = middlePosition(lines, options);
    }

    // Prevent, if we have whole line with ellipsis (reason - cannot calculate element container width).
    const match = lines.filter((line) => line === ellipsis).length;

    if (match) {
        return;
    }

    if (!word) {
        el.innerHTML = isEllipsisExist(lines, ellipsis) ? lines.join(' ') : lines.join('');
    } else {
        el.innerHTML = lines.join(' ');
    }
};

const getOptions = function (binding) {
    const canvas = getOptions.canvas || (getOptions.canvas = document.createElement('canvas'));
    const { arg, el, modifiers, value } = binding;
    let params = {}; let
        lineCount = 1;

    if (typeof value === 'object') {
        params = value;
    } else {
        lineCount = Number.parseInt(value, 0);
    }

    return _.extend({
        el,
        canvas,
        ellipsis: '...',
        lineCount,
        middle: arg === 'middle',
        string: el.$dataOriginal,
        word: modifiers.word
    }, params);
};

const doformat = function (binding) {
    const options = getOptions(binding);
    setTimeout(() => {
        formatSting(options);
    }, 0);
};

let off;

export default {
    bind(el, binding, vnode) {
        el.style.display = 'inline-flex';
        el.$dataOriginal = el.innerHTML.trim();
        el.innerHTML = '';
        binding.el = el;
    },

    inserted(el, binding, vnode) {
        const $this = vnode.context;

        $this.$nextTick(() => {
            doformat(binding);
            off = on(window, 'focus load resize', (e) => {
                doformat(binding);
            });
        });
    },

    componentUpdated(el, binding, vnode) {
        binding.el = el;
        const options = getOptions(binding);
        formatSting(options);
    },

    unbind() {
        off();
    }
};

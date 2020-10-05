import LazyBackground from '@system/app/directives/lazy-background';
import FinderHelperMixin from './finder-helper-mixin';
import LightBoxMixin from './lightbox-mixin';
import Clamp from '../directives/clamp';

export default {

    props: ['value', 'searched', 'modal'],

    mixins: [FinderHelperMixin, LightBoxMixin],

    inject: ['toggleSelect', 'isSelected', 'setPath'],

    data() {
        return {
            selected: this.value,
            clickCount: 0,
            clickTimer: 0,
            delay: 210,
            meta: []
        };
    },

    watch: {
        value(val) {
            this.selected = val;
        },

        selected(val) {
            this.$emit('input', val);
        },

        'searched.length': function (val, old) {
            if ((val !== old) && this.lightbox.enable) {
                this.meta = [];
            }
        }
    },

    created() {
        this.lightbox.enable = !this.modal;
    },

    methods: {
        isLightBoxPreview(url) {
            return this.lightbox.enable && (!!this.isImage(url) || !!this.isVideo(url));
        },

        getLightBoxItems() {
            const media = this.searched.filter((item) => (item.mime === 'application/file' && this.isLightBoxPreview(item.path)));

            if (this.meta.length && (this.meta.length === media.length)) {
                media.forEach((item, id) => {
                    const find = this.meta.filter((i) => i.url === this.$url(item.url))[0];
                    if (find) {
                        media[id].width = find.width;
                        media[id].height = find.height;
                    }
                });
            }
            const items = media.map((item) => ({
                url: item.url,
                source: this.$url(item.url),
                caption: `<span>${item.name}</span>${item.size ? `, ${item.size}` : ''}${item.width && item.height ? `, <span>${item.width}x${item.height}</span>` : ''}`
            }));
            return items;
        },

        handler(file) {
            let index;
            let items = [];

            if (this.modal) {
                this.toggleSelect(file.name);
                return;
            }

            this.clickCount++;

            if (this.clickCount === 1) {
                this.clickTimer = setTimeout(() => {
                    this.clickCount = 0;
                    this.toggleSelect(file.name);
                }, this.delay);
            } else if (this.clickCount === 2) {
                clearTimeout(this.clickTimer);
                this.clickCount = 0;
                if (this.isLightBoxPreview(file.path)) {
                    items = this.getLightBoxItems();
                    index = _.findIndex(items, (item) => item.url === file.url);
                    if (index === -1) return;
                    this.lightboxShow(items, index);
                }
            }
        }
    },

    directives: {
        Clamp,
        LazyBackground
    }
};

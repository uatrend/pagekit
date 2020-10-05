<template>
    <div>
        <div v-if="!source" :class="['uk-inline-clip', className]" @click.prevent="pick">
            <a class="uk-placeholder uk-display-block uk-text-muted uk-text-center uk-margin-remove">
                <span uk-icon="image" ratio="3" />
                <p class="uk-text-small uk-margin-small-top">{{ title | trans }}</p>
            </a>
        </div>

        <div v-else :class="['uk-inline-clip uk-transition-toggle uk-visible-toggle', className]">
            <img :data-src="source.indexOf('blob:') !== 0 ? $url(source) : source" uk-img>
            <div class="uk-transition-fade uk-position-cover uk-overlay uk-overlay-default" />
            <a class="uk-position-cover" @click.prevent="pick" />
            <div class="uk-invisible-hover uk-position-top-right pk-panel-badge">
                <ul class="uk-iconnav">
                    <li><a v-confirm="'Reset image?'" class="uk-icon-link" uk-icon="trash" :title="'Delete' | trans" uk-tooltip="delay: 500" @click.prevent="remove" /></li>
                </ul>
            </div>
        </div>

        <div v-if="inputField" class="uk-margin-small-top">
            <div :class="['uk-inline', className]">
                <a class="uk-form-icon" uk-icon="icon: image" @click.prevent="pick" />
                <a v-if="source" v-confirm="'Reset image?'" class="uk-form-icon uk-form-icon-flip" uk-icon="icon: close" @click.prevent="source=''" />
                <input v-model="source" type="text" class="uk-input">
            </div>
        </div>

        <v-modal ref="modal" large :options="{bgClose: false}">
            <panel-finder ref="finder" :root="storage" :modal="true" @finder-select="selectFinder" />

            <div class="uk-modal-footer">
                <div class="uk-flex uk-flex-middle uk-flex-between">
                    <div>
                        <div v-if="isFinder">
                            <span v-if="!finder.selected.length" class="uk-text-meta">{{ '{0} %count% Files|{1} %count% File|]1,Inf[ %count% Files' | transChoice(finder.count, {count: finder.count}) }}</span>
                            <span v-else class="uk-text-meta">{{ '{1} %count% File selected|]1,Inf[ %count% Files selected' | transChoice(finder.selected.length, {count:finder.selected.length}) }}</span>
                        </div>
                    </div>
                    <div>
                        <button class="uk-button uk-button-text uk-margin-right uk-modal-close" type="button">
                            {{ 'Cancel' | trans }}
                        </button>
                        <button class="uk-button uk-button-primary" type="button" :disabled="!choice" @click.prevent="select">
                            {{ 'Select' | trans }}
                        </button>
                    </div>
                </div>
            </div>
        </v-modal>
    </div>
</template>

<script>

import FinderHelperMixin from '@system/modules/finder/app/mixins/finder-helper-mixin';

const InputImage = {

    mixins: [FinderHelperMixin],

    props: {
        className: { default: '' },
        value: { default: '' },
        title: { default: 'Select Image' },
        inputField: { default: true, type: Boolean }
    },

    data() {
        return _.merge({
            source: this.value,
            choice: '',
            finder: {}
        }, $pagekit);
    },

    computed: {
        isFinder() {
            return !!((this.finder.hasOwnProperty('selected') && this.finder.selected));
        }
    },

    watch: {
        source(src) {
            this.$emit('input', src);
        }
    },

    mounted() {
        const vm = this;
        UIkit.util.on(this.$refs.modal.$el, 'shown', () => {
            vm.finder = vm.$refs.finder;
        });
    },

    methods: {
        selectFinder(val) {
            this.choice = this.hasSelection();
        },

        pick() {
            this.$refs.modal.open();
        },

        select() {
            const old_source = this.source;
            this.source = decodeURIComponent(this.$refs.finder.getSelected()[0]);
            this.$emit('input', this.source);
            this.$emit('image-selected', this.source, old_source);
            this.$refs.finder.removeSelection();
            this.$refs.modal.close();
        },

        remove() {
            this.source = '';
            this.$emit('input', this.source);
            this.$emit('image-removed');
        },

        hasSelection() {
            const selected = this.$refs.finder.getSelected();
            return selected.length === 1 && this.$refs.finder.isImage(selected[0]);
        }
    }

};

Vue.component('InputImage', (resolve, reject) => {
    Vue.asset({
        js: [
            'app/system/modules/finder/app/bundle/panel-finder.js'
        ]
    }).then(() => {
        resolve(InputImage);
    });
});

export default InputImage;

</script>

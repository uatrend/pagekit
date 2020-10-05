<template>
    <div v-show="items" class="storage">
        <template v-if="!modal">
            <div class="uk-flex uk-flex-between uk-flex-wrap">
                <div class="uk-flex uk-flex-middle uk-flex-wrap">
                    <h2 class="uk-h3 uk-margin-remove">
                        <span v-if="!selected.length">{{ '{0} %count% Files|{1} %count% File|]1,Inf[ %count% Files' | transChoice(count, {count:count}) }}</span>
                        <span v-else>{{ '{1} %count% File selected|]1,Inf[ %count% Files selected' | transChoice(selected.length,{count:selected.length}) }}</span>
                    </h2>
                    <div v-if="isWritable && selected.length" class="uk-margin-left">
                        <ul class="uk-iconnav">
                            <li :class="selected.length !== 1 ? 'uk-disabled' : ''">
                                <a uk-icon="file-edit" :title="'Rename' | trans" uk-tooltip="delay: 500" @click.prevent="rename" />
                            </li>
                            <li :class="!selected.length ? 'uk-disabled' : ''">
                                <a v-confirm="'Delete file(s)?'" uk-icon="trash" :title="'Delete' | trans" uk-tooltip="delay: 500" @click.prevent="remove" />
                            </li>
                        </ul>
                    </div>

                    <div class="uk-search uk-search-default pk-search">
                        <span uk-search-icon />
                        <input v-model="search" class="uk-search-input" type="search">
                    </div>
                </div>
                <div class="uk-flex uk-flex-middle uk-flex-wrap">
                    <div class="uk-margin-right">
                        <ul class="uk-iconnav">
                            <li :class="{'uk-active': view == 'template-table'}">
                                <a uk-icon="table" :title="'Table View' | trans" uk-tooltip="delay: 500" @click.prevent="view = 'template-table'" />
                            </li>
                            <li :class="{'uk-active': view == 'template-thumbnail'}">
                                <a uk-icon="thumbnails" :title="'Thumbnails View' | trans" uk-tooltip="delay: 500" @click.prevent="view = 'template-thumbnail'" />
                            </li>
                        </ul>
                    </div>

                    <div class="uk-iconnav">
                        <li>
                            <a uk-icon="folder" :title="'Add Folder' | trans" uk-tooltip="delay: 500" @click.prevent="createFolder()" />
                        </li>
                        <li>
                            <a class="files-upload uk-icon uk-flex uk-flex-middle" :title="'Upload' | trans" uk-tooltip="delay: 500">
                                <div uk-form-custom>
                                    <input type="file" name="files[]" multiple="multiple">
                                    <span uk-icon="upload" />
                                </div>
                            </a>
                        </li>
                    </div>
                </div>
            </div>

            <div class="uk-flex uk-flex-middle">
                <i uk-icon="icon: database; ratio: 1.25" class="uk-margin-small-right" />
                <span class="uk-text-small uk-text-bolder" style="margin-right: 2px;">{{ 'Folder' | trans }}:</span>
                <ul class="uk-breadcrumb uk-margin-small">
                    <li v-for="(bc, key) in breadcrumbs" :key="key" :class="{'uk-active': bc.current}">
                        <span v-if="bc.current">{{ bc.title }}</span>
                        <a v-else @click.prevent="setPath(bc.path)">{{ bc.title }}</a>
                    </li>
                </ul>
            </div>

            <progress id="js-progressbar" class="uk-progress" value="0" max="100" hidden />

            <div class="uk-flex uk-overflow-auto tm-overflow-container" uk-height-viewport="offset-top: true; offset-bottom: 120px">
                <component :is="view" v-show="count" v-model="selected" :searched="searched" :modal="modal" />
                <h3 v-show="!count" class="uk-h2 uk-text-muted uk-margin-auto uk-margin-auto-vertical">
                    {{ 'No files found.' | trans }}
                </h3>
            </div>
        </template>

        <template v-else>
            <div class="uk-modal-header">
                <div class="uk-position-relative uk-flex uk-flex-between">
                    <div class="uk-flex uk-flex-middle uk-flex-nowrap">
                        <h1 class="uk-h4 uk-flex uk-flex-middle uk-flex-nowrap uk-margin-remove-bottom">
                            <span class="uk-visible@s uk-margin-small-right" uk-icon="database" ratio="1.1" /><span>{{ 'Storage' | trans }}</span>
                        </h1>
                        <div v-if="isWritable && selected.length" class="uk-margin-left">
                            <ul class="uk-iconnav uk-flex-nowrap">
                                <li :class="selected.length !== 1 ? 'uk-disabled' : ''">
                                    <a uk-icon="file-edit" :title="'Rename' | trans" uk-tooltip="delay: 500" @click.prevent="rename" />
                                </li>
                                <li :class="!selected.length ? 'uk-disabled' : ''">
                                    <a v-confirm="'Delete file(s)?'" uk-icon="trash" :title="'Remove' | trans" uk-tooltip="delay: 500" @click.prevent="remove" />
                                </li>
                            </ul>
                        </div>
                        <div class="uk-search uk-search-default pk-search">
                            <span uk-search-icon ratio="1.1" />
                            <input v-model="search" class="uk-search-input" type="search">
                        </div>
                    </div>
                    <div class="uk-position-center-right uk-flex uk-flex-middle">
                        <ul class="uk-iconnav">
                            <li :class="{'uk-active': view == 'template-table'}">
                                <a uk-icon="table" :title="'Table View' | trans" uk-tooltip="delay: 500" @click.prevent="view = 'template-table'" />
                            </li>
                            <li :class="{'uk-active': view == 'template-thumbnail'}">
                                <a uk-icon="thumbnails" :title="'Thumbnails View' | trans" uk-tooltip="delay: 500" @click.prevent="view = 'template-thumbnail'" />
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="uk-flex uk-flex-middle uk-flex-nowrap">
                    <span class="uk-text-small uk-text-bolder" style="margin-right: 2px;">{{ 'Folder' | trans }}:</span>
                    <ul class="uk-breadcrumb uk-flex-nowrap uk-margin-remove-vertical">
                        <li v-for="(bc, key) in breadcrumbs" :key="key" :class="{'uk-active': bc.current}">
                            <span v-if="bc.current">{{ bc.title }}</span>
                            <a v-else @click.prevent="setPath(bc.path)">{{ bc.title }}</a>
                        </li>
                    </ul>
                </div>

                <progress v-show="upload.running" id="progressbar" class="uk-progress" value="0" max="100" />
            </div>

            <div class="uk-modal-body uk-padding-remove">
                <div class="tm-finder-modal-container">
                    <div class="uk-overflow-auto" uk-overflow-auto :class="{'uk-flex uk-flex-center uk-flex-middle': !count}">
                        <div class="tm-overflow-container">
                            <component :is="view" v-show="count" v-model="selected" :searched="searched" :modal="modal" />
                            <h3 v-show="!count" class="uk-h2 uk-text-muted uk-text-center">
                                {{ 'No files found.' | trans }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>

import TableTemplate from '../templates/table.html';
import ThumbnailTemplate from '../templates/thumbnail.html';
import TemplateMixin from '../mixins/template-mixin';

const PanelFinder = {

    name: 'PanelFinder',

    mixins: [Theme.Mixins.Helper],

    provide() {
        return {
            toggleSelect: this.toggleSelect,
            isSelected: this.isSelected,
            setPath: this.setPath
        };
    },

    props: {
        root: { type: String, default: '/' },
        mode: { type: String, default: 'write' },
        modal: Boolean
    },

    data() {
        return {
            upload: {},
            selected: [],
            items: false,
            search: '',
            path: '',
            view: ''
        };
    },

    theme: {
        hideEls: ['.storage > div:not(.uk-modal-header):first-child'],
        elements() {
            const vm = this;
            return {
                addfolder: {
                    scope: 'topmenu-left',
                    type: 'button',
                    caption: 'Add Folder',
                    class: 'uk-button uk-button-primary',
                    on: { click: () => vm.createFolder() },
                    priority: 0,
                    vif: () => !vm.modal
                },
                upload: {
                    scope: 'topmenu-left',
                    type: 'button',
                    caption: 'Upload',
                    class: 'uk-button uk-button-primary',
                    on: { click: () => UIkit.util.$('input', UIkit.util.$('.files-upload')).click() },
                    priority: 0,
                    vif: () => !vm.modal
                },
                actions: {
                    scope: 'topmenu-left',
                    type: 'dropdown',
                    caption: 'Actions',
                    class: 'uk-button uk-button-text',
                    icon: { attrs: { 'uk-icon': 'triangle-down' } },
                    dropdown: { options: () => 'mode:click' },
                    actionIcons: true,
                    items: () => ({
                        rename: {
                            type: 'button',
                            on: { click: (e) => vm.rename(e) },
                            class: vm.selected.length > 1 ? 'uk-disabled uk-text-lighter' : ''
                        },
                        remove: {
                            on: { click: (e) => vm.remove(e) },
                            directives: [
                                {
                                    name: 'confirm',
                                    value: vm.$trans('Delete file(s)?')
                                }
                            ]
                        }
                    }),
                    priority: 2,
                    disabled: () => !vm.selected.length,
                    vif: () => !vm.modal
                },
                selected: {
                    scope: 'topmenu-right',
                    type: 'caption',
                    caption: () => {
                        if (!vm.selected.length) return vm.$transChoice('{0} %count% Files|{1} %count% File|]1,Inf[ %count% Files', vm.count, { count: vm.count });
                        return vm.$transChoice('{1} %count% File selected|]1,Inf[ %count% Files selected', vm.selected.length, { count: vm.selected.length });
                    },
                    class: 'uk-text-small',
                    priority: 0
                },
                views: {
                    scope: 'topmenu-right',
                    type: 'iconnav',
                    dropdown: { options: () => 'mode:click' },
                    items: () => ({
                        table: {
                            active: () => vm.view === 'template-table',
                            icon: { attrs: { ratio: '1.1' } },
                            on: {
                                click: () => {
                                    vm.view = 'template-table';
                                }
                            }
                        },
                        thumbnails: {
                            active: () => vm.view === 'template-thumbnail',
                            icon: { attrs: { ratio: '1.1' } },
                            on: {
                                click: () => {
                                    vm.view = 'template-thumbnail';
                                }
                            }
                        }
                    }),
                    priority: 1,
                    vif: () => !vm.modal
                },
                search: {
                    scope: 'navbar-right',
                    type: 'search',
                    class: 'uk-text-small',
                    domProps: { value: () => vm.search || '' },
                    on: {
                        input(e) {
                            !vm.search && vm.$set(vm, 'search', '');
                            vm.search = e.target.value;
                        }
                    }
                }
            };
        }
    },

    computed: {

        breadcrumbs() {
            const path = '';
            const crumbs = [{ path: '/', title: this.getRoot() }]
                .concat(this.path.substr(1).split('/')
                    .filter((str) => str.length)
                    .map((part) => ({ path: `${path}/${part}`, title: part })));

            crumbs[crumbs.length - 1].current = true;

            return crumbs;
        },

        searched() {
            const vm = this;
            return _.filter(this.items, (file) => !vm.search || file.name.toLowerCase().indexOf(vm.search.toLowerCase()) !== -1);
        },

        count() {
            return this.searched.length;
        }

    },

    watch: {

        view(view) {
            this.$session.set(`finder.${this.root}.view`, view);
        },

        selected() {
            this.$emit('finder-select', this.getSelected(), this);
            this.$trigger('finder-select', this.getSelected(), this);
        },

        search() {
            const vm = this;
            this.$set(this, 'selected', _.filter(this.selected, (name) => _.find(vm.searched, { name })));
        }

    },

    created() {
        if (!this.path) {
            this.path = this.$session.get(`finder.${this.root}.path`, '/');
        }

        if (!this.view) {
            this.view = this.$session.get(`finder.${this.root}.view`, 'template-table');
        }

        this.$watch('path', function (path) {
            this.load();
            this.$session.set(`finder.${this.root}.path`, path);
        });

        this.$on('hook:mounted', this.initUpload);
    },

    mounted() {
        this.modaloptions = {
            labels: {
                ok: this.$trans('Ok'),
                cancel: this.$trans('Cancel')
            },
            stack: true
        };

        this.resource = this.$resource('system/finder{/cmd}');

        this.load().then(function () {
            this.$emit('finder-ready', this);
            this.$trigger('finder-ready', this);
        });
    },

    methods: {
        /**
         * API
        */

        setPath(path) {
            this.$set(this, 'path', path);
        },

        getPath() {
            return this.path;
        },

        getFullPath() {
            return `${(this.getRoot() + this.getPath()).replace(/^\/+|\/+$/g, '')}/`;
        },

        getRoot() {
            return this.root.replace(/^\/+|\/+$/g, '');
        },

        getSelected() {
            return this.selected.map(function (name) {
                return _.find(this.items, { name }).url;
            }, this);
        },

        removeSelection() {
            this.selected = [];
        },

        toggleSelect(name) {
            const index = this.selected.indexOf(name);
            index === -1 ? this.selected.push(name) : this.selected.splice(index, 1);
        },

        isSelected(name) {
            return this.selected.indexOf(name.toString()) !== -1;
        },

        createFolder() {
            UIkit.modal.prompt(this.$trans('Folder Name'), '', (name) => {
                if (!name) return;
                this.command('createfolder', { name });
            }, this.modaloptions);
        },

        rename(oldname) {
            if (oldname.target) {
                oldname = this.selected[0];
            }

            if (!oldname) return;

            UIkit.modal.prompt(this.$trans('Name'), oldname, (newname) => {
                if (!newname) return;
                this.command('rename', { oldname, newname });
            }, _.extend({ title: this.$trans('Rename') }, this.modaloptions));
        },

        remove(names) {
            if (names.target) {
                names = this.selected;
            }

            if (names) {
                this.command('removefiles', { names });
            }
        },

        /**
             * Helper functions
             */

        encodeURI(url) {
            return encodeURI(url).replace(/'/g, '%27');
        },

        isWritable() {
            return this.mode === 'w' || this.mode === 'write';
        },

        isImage(url) {
            return url.match(/\.(?:gif|jpe?g|png|bpm|webp|svg|ico)$/i);
        },

        isVideo(url) {
            return url.match(/\.(mpeg|ogv|mp4|m4v|webm|wmv)$/i);
        },

        isFileExt(name, ext) {
            const regex = `(?:${ext})$`;
            return name.match(new RegExp(regex, 'i'));
        },

        command(cmd, params) {
            return this.resource.save({ cmd }, _.extend({ path: this.path, root: this.getRoot() }, params)).then(function (res) {
                this.load();
                this.$notify(res.data.message, res.data.error ? 'danger' : '');
            }, function (res) {
                this.$notify(res.status === 500 ? 'Unknown error.' : res.data, 'danger');
            });
        },

        load() {
            return this.resource.get({ path: this.path, root: this.getRoot() }).then(function (res) {
                this.$set(this, 'items', res.data.items || []);
                this.$set(this, 'selected', []);
                this.$emit('finder-ready', this.getFullPath(), this);
                this.$trigger('finder-ready', this.getFullPath(), this);
            }, function () {
                this.$notify('Unable to access directory.', 'danger');
            });
        },

        initUpload() {
            const bar = document.getElementById('js-progressbar');

            const finder = this;
            const settings = {

                url: this.$url.route('system/finder/upload'),
                multiple: true,

                beforeAll(data) {
                    _.extend(data.params, { path: finder.path, root: finder.getRoot(), _csrf: $pagekit.csrf });
                },

                loadStart(e) {
                    bar.removeAttribute('hidden');
                    bar.max = e.total;
                    bar.value = e.loaded;
                },

                progress(e) {
                    bar.max = e.total;
                    bar.value = e.loaded;
                },

                loadEnd(e) {
                    bar.max = e.total;
                    bar.value = e.loaded;
                },

                completeAll(response) {
                    let message;
                    let data = response;

                    try {
                        data = JSON.parse(data.responseText);
                    } catch (e) {
                        try {
                            data = JSON.parse(data.responseText.substring(data.responseText.lastIndexOf('{'), data.responseText.lastIndexOf('}') + 1));
                            message = data.message;
                        } catch (e1) {
                            message = 'Unable load file(s).';
                        }

                        finder.$notify(message, 'danger');
                        return;
                    }

                    finder.load();

                    finder.$notify(data.message, data.error ? 'danger' : '');

                    setTimeout(() => {
                        bar.setAttribute('hidden', 'hidden');
                    }, 1000);
                }

            };

            UIkit.upload(this.$el.querySelector('.files-upload'), settings);
            UIkit.upload(UIkit.util.parents(this.$el, '.uk-modal').length ? this.$el : UIkit.util.$('html'), settings);
        }

    },

    components: {
        'template-table': { mixins: [TemplateMixin], template: TableTemplate },
        'template-thumbnail': { mixins: [TemplateMixin], template: ThumbnailTemplate }
    }

};

Vue.component('PanelFinder', (resolve) => {
    resolve(PanelFinder);
});

export default PanelFinder;

</script>

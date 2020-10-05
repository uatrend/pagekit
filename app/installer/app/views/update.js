import Version from '../lib/version';

const Update = {

    name: 'update',

    el: '#update',

    data() {
        return _.extend({
            view: 'index',
            status: 'success',
            finished: false,
            update: false,
            output: '',
            progress: 0,
            releases: [],
            errorsPagekit: []
        }, window.$data);
    },

    created() {
        this.getVersions();
    },

    computed: {

        hasUpdate() {
            return this.update && Version.compare(this.update.version, this.version, '>');
        }

    },

    methods: {

        getVersions() {
            this.$http.get(`${this.api}/api/update`, { params: { version: this.version } }).then((res) => {
                const { data } = res;
                const channel = data[this.channel === 'nightly' ? 'nightly' : 'latest'];

                if (channel) {
                    this.update = channel;
                    this.releases = data.versions;
                } else {
                    this.error(this.$trans('Cannot obtain versions. Please try again later.'));
                }
            }, () => {
                this.error(this.$trans('Cannot connect to the server. Please try again later.'));
            });
        },

        install() {
            this.$set(this, 'view', 'installation');
            this.doDownload(this.update);
        },

        doDownload(update) {
            this.$set(this, 'progress', 33);
            this.$http.post('admin/system/update/download', { url: update.url }).then(this.doInstall, this.error);
        },

        doInstall() {
            const vm = this;

            this.$set(this, 'progress', 66);
            this.$http.get('admin/system/update/update', null, {
                xhr: {
                    onprogress() {
                        vm.setOutput(this.responseText);
                    }
                }
            }).then(this.doMigration, this.error);
        },

        doMigration() {
            this.$set(this, 'progress', 100);
            if (this.status === 'success') {
                this.$http.get('admin/system/migration/migrate').then((res) => {
                    const { data } = res;
                    this.output = `${this.output}\n\n${data.message}`;
                    this.finished = true;
                }, this.error);
            } else {
                this.error();
            }
        },

        setOutput(output) {
            const lines = output.split('\n');
            const match = lines[lines.length - 1].match(/^status=(success|error)$/);

            if (match) {
                this.status = match[1];
                delete lines[lines.length - 1];
                this.output = lines.join('\n');
            } else {
                this.output = output;
            }
        },

        error(error) {
            this.errorsPagekit.push(error.data || this.$trans('Whoops, something went wrong.'));

            this.status = 'error';
            this.finished = true;
        },

        showChangelog(version) {
            return Version.compare(version, this.version, '>');
        },

        changelog(md) {
            const renderer = new marked.Renderer();
            let section;

            renderer.heading = function (text) {
                section = text;
                return '';
            };

            renderer.listitem = function (text) {
                switch (section) {
                    case 'Added':
                        return `<li class="uk-flex uk-flex-middle"><span class="uk-label pk-badge-justify uk-label-success uk-margin-right">${section}</span> <span>${text}</span></li>`;
                    case 'Deprecated':
                        return `<li class="uk-flex uk-flex-middle"><span class="uk-label pk-badge-justify uk-label-warning uk-margin-right">${section}</span> <span>${text}</span></li>`;
                    case 'Removed':
                        return `<li class="uk-flex uk-flex-middle"><span class="uk-label pk-badge-justify uk-label-warning uk-margin-right">${section}</span> <span>${text}</span></li>`;
                    case 'Fixed':
                        return `<li class="uk-flex uk-flex-middle"><span class="uk-label pk-badge-justify uk-label-danger uk-margin-right">${section}</span> <span>${text}</span></li>`;
                    case 'Security':
                        return `<li class="uk-flex uk-flex-middle"><span class="uk-label pk-badge-justify uk-label-danger uk-margin-right">${section}</span> <span>${text}</span></li>`;
                    default:
                        return `<li class="uk-flex uk-flex-middle"><span class="uk-label pk-badge-justify uk-margin-right">${section}</span> ${text}</li>`;
                }
            };

            renderer.list = function (text) {
                return text;
            };

            return marked(md, { renderer });
        }

    },

    filters: {}

};

export default Update;

Vue.ready(Update);

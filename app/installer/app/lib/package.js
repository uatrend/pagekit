import InstallInstance from './install.vue';
import UninstallInstance from './uninstall.vue';
import UpdateInstance from './update.vue';

const Install = Vue.extend(InstallInstance);
const Uninstall = Vue.extend(UninstallInstance);
const Update = Vue.extend(UpdateInstance);

export default {

    methods: {

        queryUpdates(packages, success) {
            const pkgs = {}; const
                options = { emulateJSON: true };

            _.each(packages, (pkg) => {
                pkgs[pkg.name] = pkg.version;
            });

            return this.$http.post(`${this.api}/api/package/update`, { packages: JSON.stringify(pkgs) }, options).then(success, this.error);
        },

        enable(pkg) {
            return this.$http.post('admin/system/package/enable', { name: pkg.name }).then(() => {
                this.$notify(this.$trans('"%title%" enabled.', { title: pkg.title }));
                Vue.set(pkg, 'enabled', true);
                document.location.assign(this.$url(`admin/system/package/${pkg.type === 'pagekit-theme' ? 'themes' : 'extensions'}`));
            }, this.error);
        },

        disable(pkg) {
            return this.$http.post('admin/system/package/disable', { name: pkg.name }).then(() => {
                this.$notify(this.$trans('"%title%" disabled.', { title: pkg.title }));
                Vue.set(pkg, 'enabled', false);
                document.location.reload();
            }, this.error);
        },

        install(pkg, packages, onClose, packagist) {
            const install = new Install({ parent: this });

            return install.install(pkg, packages, onClose, packagist);
        },

        update(pkg, updates, onClose, packagist) {
            const update = new Update({ parent: this });

            return update.update(pkg, updates, onClose, packagist);
        },

        uninstall(pkg, packages) {
            const uninstall = new Uninstall({ parent: this });

            return uninstall.uninstall(pkg, packages);
        },

        error(message) {
            this.$notify(message.data, 'danger');
        }

    }

};

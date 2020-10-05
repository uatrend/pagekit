<template>
    <div class="tm-background uk-height-viewport">
        <installer-steps :steps="steps" :current="step">
            <template #start="{ step }">
                <div :step="step" class="tm-slide">
                    <div class="tm-container">
                        <div class="uk-panel uk-text-center">
                            <a id="next" @click="gotoStep('language')">
                                <img src="/app/system/assets/images/pagekit-logo-large.svg" alt="Pagekit">
                                <div class="uk-margin">
                                    <svg class="tm-arrow" width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                                        <line fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" x1="2" y1="18" x2="36" y2="18" />
                                        <polyline fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="26.071,6.5 37.601,18.03 26,29.631 " />
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </template>
            <template #language="{ step }">
                <div :step="step" class="tm-slide">
                    <div class="tm-container">
                        <div class="uk-card uk-card-default">
                            <div class="uk-card-header">
                                <h1 class="uk-card-title uk-text-center">
                                    {{ 'Choose language' | trans }}
                                </h1>
                            </div>
                            <div class="uk-card-body">
                                <div class="uk-margin uk-text-muted uk-text-center">
                                    {{ "Select your site language." | trans }}
                                </div>
                                <select id="selectbox" v-model="locale" class="uk-select" size="10">
                                    <option v-for="(lang, key) in locales" :key="key" :value="key">
                                        {{ lang }}
                                    </option>
                                </select>
                            </div>
                            <buttons :next="stepLanguage" />
                        </div>
                    </div>
                </div>
            </template>
            <template #database="{ step, passes, valid }">
                <div :step="step" class="tm-slide">
                    <div class="tm-container">
                        <div class="uk-card uk-card-default">
                            <div class="uk-card-header">
                                <h1 class="uk-card-title uk-text-center">
                                    {{ 'Connect database' | trans }}
                                </h1>
                            </div>
                            <form class="uk-card-body uk-form-horizontal tm-form-horizontal">
                                <div class="uk-margin uk-text-muted uk-text-center">
                                    {{ 'Enter your database connection details.' | trans }}
                                </div>
                                <div v-show="message" class="uk-alert uk-alert-danger uk-margin uk-text-center">
                                    <p>{{ message }}</p>
                                </div>
                                <div class="uk-margin">
                                    <label for="form-dbdriver" class="uk-form-label">{{ 'Driver' | trans }}</label>
                                    <div class="uk-form-controls">
                                        <select id="form-dbdriver" v-model="config.database.default" class="uk-select" name="dbdriver">
                                            <option v-if="sqlite" value="sqlite">
                                                SQLite
                                            </option>
                                            <option value="mysql">
                                                MySQL
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <template v-if="config.database.default === 'mysql'">
                                    <div class="uk-margin">
                                        <label for="form-mysql-dbhost" class="uk-form-label">{{ 'Hostname' | trans }}</label>
                                        <v-input id="form-mysql-dbhost" v-model="config.database.connections.mysql.host" view="class: uk-input uk-form-width-large" type="text" name="host" rules="required" message="Host cannot be blank." />
                                    </div>
                                    <div class="uk-margin">
                                        <label for="form-mysql-dbuser" class="uk-form-label">{{ 'User' | trans }}</label>
                                        <v-input id="form-mysql-dbuser" v-model="config.database.connections.mysql.user" view="class: uk-input uk-form-width-large" type="text" name="user" rules="required" message="User cannot be blank." />
                                    </div>
                                    <div class="uk-margin">
                                        <label for="form-mysql-dbpassword" class="uk-form-label">{{ 'Password' | trans }}</label>
                                        <v-input id="form-mysql-dbpassword" v-model="config.database.connections.mysql.password" :type="hidePassword ? 'password' : 'text'" name="password" :view="{type: 'icon', icon: () => hidePassword ? 'eye-closed' : 'eye', class: 'uk-input uk-form-width-large', containerClass: 'uk-form-controls', iconTag: 'a', iconDir: 'right', iconClick: () => { hidePassword = !hidePassword }}" autocomplete="off" />
                                    </div>
                                    <div class="uk-margin">
                                        <label for="form-mysql-dbname" class="uk-form-label">{{ 'Database Name' | trans }}</label>
                                        <v-input id="form-mysql-dbname" v-model="config.database.connections.mysql.dbname" view="class: uk-input uk-form-width-large" type="text" name="dbname" rules="required" message="Database name cannot be blank." />
                                    </div>
                                    <div class="uk-margin">
                                        <label for="form-mysql-dbprefix" class="uk-form-label">{{ 'Table Prefix' | trans }}</label>
                                        <v-input id="form-mysql-dbprefix" v-model="config.database.connections.mysql.prefix" view="class: uk-input uk-form-width-large" type="text" name="mysqlprefix" :rules="{required: true, regex: /^[a-zA-Z][a-zA-Z0-9._\-]*$/}" message="Prefix must start with a letter and can only contain alphanumeric characters (A-Z, 0-9) and underscore (_)" />
                                    </div>
                                </template>
                                <div v-show="config.database.default == 'sqlite'" class="uk-margin">
                                    <label for="form-sqlite-dbprefix" class="uk-form-label">{{ 'Table Prefix' | trans }}</label>
                                    <v-input id="form-sqlite-dbprefix" v-model="config.database.connections.sqlite.prefix" view="class: uk-input uk-form-width-large" type="text" name="sqliteprefix" :rules="{required: true, regex: /^[a-zA-Z][a-zA-Z0-9._\-]*$/}" message="Prefix must start with a letter and can only contain alphanumeric characters (A-Z, 0-9) and underscore (_)" />
                                </div>
                            </form>
                            <buttons :prev="gotoStep.bind(vm, 'language')" :next="passes.bind(vm, stepDatabase)" :valid="valid" />
                        </div>
                    </div>
                </div>
            </template>
            <template #site="{ step, passes, valid }">
                <div :step="step" class="tm-slide">
                    <div class="tm-container">
                        <div class="uk-card uk-card-default">
                            <div class="uk-card-header">
                                <h1 class="uk-card-title uk-text-center">
                                    {{ 'Setup your site' | trans }}
                                </h1>
                            </div>
                            <form class="uk-card-body uk-form-horizontal tm-form-horizontal">
                                <div class="uk-margin uk-text-muted uk-text-center">
                                    {{ 'Choose a title and create the administrator account.' | trans }}
                                </div>

                                <div class="uk-margin">
                                    <label for="form-sitename" class="uk-form-label">{{ 'Site Title' | trans }}</label>
                                    <v-input id="form-sitename" v-model="option['system/site'].title" view="class: uk-input uk-form-width-large" type="text" name="name" rules="required" message="Site title cannot be blank." />
                                </div>
                                <div class="uk-margin">
                                    <label for="form-username" class="uk-form-label">{{ 'Username' | trans }}</label>
                                    <v-input id="form-username" v-model="user.username" view="class: uk-input uk-form-width-large" type="text" name="user" :rules="{required: true, regex: /^[a-zA-Z0-9._\-]{3,}$/}" message="Username cannot be blank and may only contain alphanumeric characters (A-Z, 0-9) and some special characters (&quot;._-&quot;)" />
                                </div>
                                <div class="uk-margin">
                                    <label for="form-password" class="uk-form-label">{{ 'Password' | trans }}</label>
                                    <v-input id="form-password" v-model="user.password" :type="hidePassword ? 'password' : 'text'" name="password" :view="{type: 'icon', icon: () => hidePassword ? 'eye-closed' : 'eye', class: 'uk-input uk-form-width-large', containerClass: 'uk-form-controls', iconTag: 'a', iconDir: 'right', iconClick: () => { hidePassword = !hidePassword }}" rules="required" message="Password cannot be blank." autocomplete="off" />
                                </div>
                                <div class="uk-margin">
                                    <label for="form-email" class="uk-form-label">{{ 'Email' | trans }}</label>
                                    <v-input id="form-email" v-model="user.email" view="class: uk-input uk-form-width-large" type="email" name="email" rules="required|email" message="Field must be a valid email address." />
                                </div>
                            </form>
                            <buttons :options="optionSite.bind(vm)" :prev="gotoStep.bind(vm, 'database')" :next="passes.bind(vm, stepSite)" :title-next="'Install'" :icon-options="'settings'" :title-options="'Settings'" :valid="valid" />
                            <v-modal ref="optionSite" center>
                                <div class="uk-modal-header">
                                    <h1 class="uk-h4">
                                        <span uk-icon="settings" ratio="1.1" /><span class="uk-text-middle uk-margin-small-left">{{ 'Settings' | trans }}</span>
                                    </h1>
                                </div>
                                <div class="uk-modal-body">
                                    <div class="uk-margin">
                                        <label><input v-model="option['demo_content']" class="uk-checkbox" type="checkbox"> {{ 'Install demo content' }}</label>
                                    </div>
                                </div>
                                <div class="uk-modal-footer uk-text-right">
                                    <button class="uk-button uk-button-default uk-modal-close">
                                        {{ 'Close' | trans }}
                                    </button>
                                </div>
                            </v-modal>
                        </div>
                    </div>
                </div>
            </template>
            <template #finish="{ step }">
                <div :step="step" class="tm-slide">
                    <div class="tm-container">
                        <div v-show="status == 'install'" class="uk-text-center">
                            <svg class="tm-loader" width="150" height="150" viewBox="0 0 150 150" xmlns="http://www.w3.org/2000/svg">
                                <g><circle cx="0" cy="0" r="70" fill="none" stroke-width="2" /></g>
                            </svg>
                        </div>

                        <div v-show="status == 'finished'" class="uk-panel uk-padding-small uk-text-center">
                            <a :href="$url.route('admin')">
                                <svg class="tm-checkmark" width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                    <polyline fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="5.125,63.25 27.375,89.375 95.25,18.875" />
                                </svg>
                            </a>
                        </div>

                        <div v-show="status == 'failed'" class="uk-card uk-card-default">
                            <div class="uk-card-header">
                                <h1 class="uk-card-title uk-text-danger uk-text-center">
                                    {{ 'Installation failed!' | trans }}
                                </h1>
                            </div>
                            <div class="uk-card-body" uk-overflow-auto>
                                <div class="uk-text-break">
                                    {{ message }}
                                </div>
                            </div>
                            <buttons :prev="gotoStep.bind(vm, 'site')" :next="stepInstall" :title-next="'Retry'" />
                        </div>
                    </div>
                </div>
            </template>
        </installer-steps>
    </div>
</template>

<script>

import { $, find, on, offset, remove, scrollTop } from 'uikit-util';
import { ValidationObserver, VInput } from '@system/app/components/validation.vue';

const Installer = {

    name: 'installer',

    el: '#installer',

    data() {
        return _.merge({
            step: 'start',
            status: '',
            message: '',
            config: {
                database: {
                    connections: {
                        mysql: {
                            user: '',
                            host: 'localhost',
                            dbname: 'pagekit'
                        },
                        sqlite: {}
                    },
                    default: ''
                }
            },
            defaultConfig: {},
            option: { system: { admin: {}, site: {} }, 'system/site': { title: '' } },
            user: { username: 'admin' },
            hidePassword: true,
            editingPassword: false,
            steps: ['start', 'language', 'database', 'site', 'finish'],
            vm: this
        }, window.$installer);
    },

    created() {
        // set default db
        this.config.database.default = this.sqlite ? 'sqlite' : 'mysql';
        // setup default table prefix
        _.forEach(this.config.database.connections, (connection) => { _.extend(connection, { prefix: 'pk_' }); });
        // Backup the current db configuration
        this.defaultConfig = { ...this.config };

        on(window, 'keyup', this.handler);
    },

    watch: {
        step(newStep, oldStep) {
            // Set config defaults from backup
            if (oldStep === 'site' && newStep === 'database') {
                this.config = { ...this.defaultConfig };
            }
            // Clear message
            if (oldStep === 'finish' && newStep === 'site') {
                this.$set(this, 'status', '');
                this.$set(this, 'message', '');
            }
        }
    },

    methods: {

        resource(action, body) {
            return this.$http.post(`installer/${action}`, body);
        },

        gotoStep(step) {
            this.$set(this, 'step', step);
            if (step === 'language') this.focuslang();
        },

        focuslang() {
            this.$nextTick(() => {
                // Focusing on selected language option
                const select = $('select');
                if (!select) return;
                const option = find(`[value=${this.locale}]`, select);
                const optionTop = offset(option).top;
                const selectTop = offset(select).top;
                const middle = offset(select).height / 2;
                scrollTop(select, select.scrollTop + (optionTop - selectTop - middle));
                select.focus();
            });
        },

        stepLanguage() {
            // Prevent re-load the same language script, load only new.
            if (this.removeLocaleScript()) {
                this.$http.get('/api/system/intl/locales', { params: { locale: this.locale } }).then((res) => {
                    this.locales = res.data.locales;
                }).then(() => {
                    this.$asset({ js: [this.$url.route(`system/intl/${this.locale}`)] }).then(() => {
                        this.proceedLocale();
                    });
                });
            } else {
                this.proceedLocale();
            }
        },

        proceedLocale() {
            this.$set(this.option.system.admin, 'locale', this.locale);
            this.$set(this.option.system.site, 'locale', this.locale);
            this.$locale = window.$locale;
            this.gotoStep('database');
        },

        removeLocaleScript() {
            // Check if script already loaded, if not remove current and return result.
            const localeScript = find('[src*="system/intl/"]', document);
            const localeURL = this.$url.route(`system/intl/${this.locale}`);

            if (localeScript.src.includes(localeURL)) return false;

            this.$asset({ clearCache: [this.$url.route(`system/intl/${this.locale}`)] });
            if (typeof window.$locale !== 'undefined') window.$locale = {};
            remove(localeScript, document.head);

            return true;
        },

        stepDatabase() {
            const config = _.cloneDeep(this.config);

            _.forEach(config.database.connections, (connection, name) => {
                if (name !== config.database.default) {
                    delete (config.database.connections[name]);
                } else if (connection.host) {
                    connection.host = connection.host.replace(/:(\d+)$/, (match, port) => {
                        connection.port = port;
                        return '';
                    });
                }
            });

            this.resource('check', { config, locale: this.locale }).then((res) => {
                let { data } = res;

                if (!this.isPlainObject(data)) {
                    data = { message: 'Whoops, something went wrong' };
                }

                if (data.status === 'no-tables') {
                    this.gotoStep('site');
                    this.config = config;
                } else {
                    this.$set(this, 'status', data.status);
                    this.$set(this, 'message', data.message);
                }
            });
        },

        stepSite() {
            this.gotoStep('finish');
            this.stepInstall();
        },

        stepInstall() {
            const vm = this;

            this.$set(this, 'status', 'install');

            this.resource('install', { config: this.config, option: this.option, user: this.user, locale: this.locale }).then((res) => {
                let { data } = res;

                setTimeout(() => {
                    if (!vm.isPlainObject(data)) {
                        data = { message: 'Whoops, something went wrong' };
                    }

                    if (data.status === 'success') {
                        this.$set(this, 'status', 'finished');

                        // redirect to login after 3s
                        setTimeout(() => {
                            location.href = this.$url.route('admin');
                        }, 3000);
                    } else {
                        this.$set(this, 'status', 'failed');
                        this.$set(this, 'message', data.message);
                    }
                }, 2000);
            });
        },

        isPlainObject(o) {
            return !!o && typeof o === 'object' && Object.prototype.toString.call(o) === '[object Object]';
        },

        optionSite() {
            this.$refs.optionSite.open();
        },

        handler(e) {
            if (e.target.tagName === 'INPUT' || this.step === 'start') return;
            switch (e.keyCode) {
                case 13:
                    if (e.target.id === 'prev') {
                        this.go('prev', true);
                        return;
                    }
                    this.go('next');
                    break;
                case 8:
                    this.go('prev');
                    break;
                default:
            }
        },

        go(dir, focus) {
            const $dir = $(`#${dir}`);
            if (!$dir) return;
            if (!focus) {
                $dir.focus();
                setTimeout(() => { $dir.click(); }, 200);
            } else {
                $dir.click();
            }
        }

    },

    components: {

        buttons: {
            props: ['options', 'prev', 'next', 'title-next', 'title-back', 'icon-options', 'title-options', 'valid'],

            template: `
                <div class="uk-card-footer">
                    <ul class="uk-iconnav uk-position-relative uk-flex-center uk-text-center uk-text-small">
                        <li v-if="options" class="uk-position-center-left"><a id="options" @click.prevent="options()" tabindex="0"><span class="uk-icon-button" :uk-icon="iconOptions" ratio="1.25"></span><div>{{ titleOptions | trans }}</div></a></li>
                        <li v-if="prev"><a id="prev" @click.prevent="prev()" tabindex="0"><span class="uk-icon-button" uk-icon="arrow-left" ratio="2"></span><div>{{ titleBack || 'Back' | trans }}</div></a></li>
                        <li v-if="next"><a id="next" @click.prevent="next()" :class="{'passed': passed}" tabindex="0"><span class="uk-icon-button" uk-icon="arrow-right" ratio="2"></span><div>{{ titleNext || 'Next' | trans }}</div></a></li>
                    </ul>
                </div>`,

            computed: {
                passed() {
                    return this.valid === undefined ? true : this.valid;
                },
                isvalid() {
                    return this.valid;
                }
            }

        },

        'installer-steps': {
            props: ['current', 'steps'],

            template: `
                <validation-observer tag="div" class="tm-slider" v-slot="{ valid, passes }" slim>
                    <template v-for="(step, index) in steps">
                        <transition name="slide">
                            <slot :name="step" :step="step" :valid="valid" :passes="passes" v-if="step === current" />
                        </transition>
                    </template>
                </validation-observer>`,

            components: { ValidationObserver }

        },
        VInput
    }

};

export default Installer;

Vue.ready(Installer);

</script>

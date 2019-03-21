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
                            dbname: 'pagekit',
                        },
                        sqlite: {},
                    },
                },
            },
            option: { system: { admin: {}, site: {} }, 'system/site': {} },
            user: {
                username: 'admin',
            },
            hidePassword: true,
            editingPassword: false,
        }, window.$installer);
    },

    mounted() {
        Object.assign(this.config.database, { default: this.sqlite ? 'sqlite' : 'mysql' });
    },

    methods: {

        resource(action, body) {
            return this.$http.post(`installer/${action}`, body);
        },

        gotoStep(step) {
            this.$set(this, 'step', step);
            if (step == 'language') this.focuslang();
        },

        focuslang() {
            this.$nextTick(() => {
                document.getElementById('selectbox').focus();
            });
        },

        stepLanguage() {
            this.$asset({ js: [this.$url.route(`system/intl/${this.locale}`)] }).then(function () {
                this.$set(this.option.system.admin, 'locale', this.locale);
                this.$set(this.option.system.site, 'locale', this.locale);
                this.$locale = window.$locale;
                this.gotoStep('database');
            });
        },

        stepDatabase() {
            const config = _.cloneDeep(this.config);

            _.forEach(config.database.connections, (connection, name) => {
                if (name != config.database.default) {
                    delete (config.database.connections[name]);
                } else if (connection.host) {
                    connection.host = connection.host.replace(/:(\d+)$/, (match, port) => {
                        connection.port = port;
                        return '';
                    });
                }
            });

            this.resource('check', { config, locale: this.locale }).then(function (res) {
                let { data } = res;

                if (!this.isPlainObject(data)) {
                    data = { message: 'Whoops, something went wrong' };
                }

                if (data.status == 'no-tables') {
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

            this.resource('install', {
                config: this.config, option: this.option, user: this.user, locale: this.locale,
            }).then(function (res) {
                let { data } = res;

                setTimeout(() => {
                    if (!vm.isPlainObject(data)) {
                        data = { message: 'Whoops, something went wrong' };
                    }

                    if (data.status == 'success') {
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
            return !!o
            && typeof o === 'object'
            && Object.prototype.toString.call(o) === '[object Object]';
        },

    },

    components: {
        start: {
            props: ['vm'],
            template: `
                <div class="uk-text-center">

                    <div class="uk-panel uk-padding-small">
                        <a @click="vm.gotoStep('language')">
                            <img src="app/system/assets/images/pagekit-logo-large.svg" alt="Pagekit">
                            <p>
                                <svg class="tm-arrow" width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                                    <line fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" x1="2" y1="18" x2="36" y2="18"/>
                                    <polyline fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="26.071,6.5 37.601,18.03 26,29.631 "/>
                                </svg>
                            </p>
                        </a>
                    </div>

                </div>`,
        },

        language: {
            props: ['vm'],
            template: `
                <div class="uk-card uk-card-default uk-card-body">

                    <h1 class="uk-card-title uk-text-center">{{ 'Choose language' | trans }}</h1>
                    <div class="uk-margin uk-text-muted uk-text-center">{{ "Select your site language." | trans }}</div>

                    <form @submit.prevent="vm.stepLanguage">
                        <select id="selectbox" class="uk-width-1-1 uk-select" size="10" v-model="vm.locale">
                            <option v-for="(lang, key) in vm.locales" :value="key">{{ lang }}</option>
                        </select>

                        <p class="uk-text-right uk-margin-remove-bottom">
                            <button class="uk-button uk-button-primary" type="submit">
                                <span class="uk-text-middle">{{ 'Next' | trans }}</span>
                                <span class="uk-margin-small-left">
                                    <svg width="18" height="11" viewBox="0 0 18 11" xmlns="http://www.w3.org/2000/svg">
                                        <line fill="none" stroke="#FFFFFF" stroke-linecap="round" stroke-miterlimit="10" x1="3" y1="5.5" x2="15" y2="5.5"/>
                                        <path fill="#FFFFFF" d="M10.5,10.9c-0.128,0-0.256-0.049-0.354-0.146c-0.195-0.195-0.195-0.512,0-0.707l4.597-4.597l-4.597-4.597
                                        c-0.195-0.195-0.195-0.512,0-0.707s0.512-0.195,0.707,0l4.95,4.95c0.195,0.195,0.195,0.512,0,0.707l-4.95,4.95
                                        C10.756,10.852,10.628,10.9,10.5,10.9z"/>
                                    </svg>
                                </span>
                            </button>
                        </p>
                    </form>

                </div>`,
        },

        database: {
            props: ['vm'],
            data() {
                return {
                    db: this.vm.config.database.default,
                    prefix: 'pk_',
                };
            },
            template: `
                <div class="uk-card uk-card-default uk-card-body">

                    <h1 class="uk-card-title uk-text-center">{{ 'Connect database' | trans }}</h1>
                    <div class="uk-margin uk-text-muted uk-text-center">{{ 'Enter your database connection details.' | trans }}</div>

                    <div class="uk-alert uk-alert-danger uk-margin uk-text-center" v-show="vm.message"><p>{{ vm.message }}</p></div>

                    <form class="uk-form-horizontal tm-form-horizontal" @submit.prevent="submit">
                        <div class="uk-margin">
                            <label for="form-dbdriver" class="uk-form-label">{{ 'Driver' | trans }}</label>
                            <div class="uk-form-controls">
                                <select id="form-dbdriver" class="uk-width-1-1 uk-select" name="dbdriver" v-model="vm.config.database.default" @change="change">
                                    <option value="sqlite" v-if="vm.sqlite">SQLite</option>
                                    <option value="mysql">MySQL</option>
                                </select>
                            </div>
                        </div>
                        <div class="uk-margin" v-if="db == 'mysql'">
                            <div class="uk-margin">
                                <label for="form-mysql-dbhost" class="uk-form-label">{{ 'Hostname' | trans }}</label>
                                <div class="uk-form-controls">
                                    <input id="form-mysql-dbhost" class="uk-width-1-1 uk-input" type="text" name="host" v-model="vm.config.database.connections.mysql.host" v-validate="'required'">
                                    <div class="uk-text-meta uk-text-danger" v-show="errors.first('host')">{{ 'Host cannot be blank.' | trans }}</div>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <label for="form-mysql-dbuser" class="uk-form-label">{{ 'User' | trans }}</label>
                                <div class="uk-form-controls">
                                    <input id="form-mysql-dbuser" class="uk-width-1-1 uk-input" type="text" name="user" v-model="vm.config.database.connections.mysql.user" v-validate="'required'">
                                    <div class="uk-text-meta uk-text-danger" v-show="errors.first('user')">{{ 'User cannot be blank.' | trans }}</div>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <label for="form-mysql-dbpassword" class="uk-form-label">{{ 'Password' | trans }}</label>
                                <div class="uk-form-controls">
                                    <div class="uk-inline uk-width-1-1">
                                        <a class="uk-form-icon uk-form-icon-flip" :uk-icon="vm.hidePassword ? 'lock' : 'unlock'" @click.prevent="vm.hidePassword = !vm.hidePassword"></a>
                                        <input id="form-password" class="uk-width-1-1 uk-input" :type="vm.hidePassword ? 'password' : 'text'" name="password" v-model="vm.config.database.connections.mysql.password">
                                    </div>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <label for="form-mysql-dbname" class="uk-form-label">{{ 'Database Name' | trans }}</label>
                                <div class="uk-form-controls">
                                    <input id="form-mysql-dbname" class="uk-width-1-1 uk-input" type="text" name="dbname" v-model="vm.config.database.connections.mysql.dbname" v-validate="'required'">
                                    <div class="uk-text-meta uk-text-danger" v-show="errors.first('dbname')">{{ 'Database name cannot be blank.' | trans }}</div>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <label for="form-mysql-dbprefix" class="uk-form-label">{{ 'Table Prefix' | trans }}</label>
                                <div class="uk-form-controls">
                                    <input id="form-mysql-dbprefix" class="uk-width-1-1 uk-input" type="text" name="mysqlprefix" v-model="vm.config.database.connections.mysql.prefix" v-validate="{required: true, regex: /^[a-zA-Z][a-zA-Z0-9._\-]*$/}">
                                    <div class="uk-text-meta uk-text-danger" v-show="errors.first('mysqlprefix')">{{ 'Prefix must start with a letter and can only contain alphanumeric characters (A-Z, 0-9) and underscore (_)' | trans }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-margin" v-show="vm.config.database.default == 'sqlite'">
                            <div class="uk-margin">
                                <label for="form-sqlite-dbprefix" class="uk-form-label">{{ 'Table Prefix' | trans }}</label>
                                <div class="uk-form-controls">
                                    <input id="form-sqlite-dbprefix" class="uk-width-1-1 uk-input" type="text" name="sqliteprefix" v-model="vm.config.database.connections.sqlite.prefix" v-validate="{required: true, regex: /^[a-zA-Z][a-zA-Z0-9._\-]*$/}">
                                    <div class="uk-text-meta uk-text-danger" v-show="errors.first('sqliteprefix')">{{ 'Prefix must start with a letter and can only contain alphanumeric characters (A-Z, 0-9) and underscore (_)' | trans }}</div>
                                </div>
                            </div>
                        </div>
                        <p class="uk-text-right uk-margin-remove-bottom">
                            <button class="uk-button uk-button-primary" type="submit">
                                <span class="uk-text-middle">{{ 'Next' | trans }}</span>
                                <span class="uk-margin-small-left">
                                    <svg width="18" height="11" viewBox="0 0 18 11" xmlns="http://www.w3.org/2000/svg">
                                        <line fill="none" stroke="#FFFFFF" stroke-linecap="round" stroke-miterlimit="10" x1="3" y1="5.5" x2="15" y2="5.5"/>
                                        <path fill="#FFFFFF" d="M10.5,10.9c-0.128,0-0.256-0.049-0.354-0.146c-0.195-0.195-0.195-0.512,0-0.707l4.597-4.597l-4.597-4.597
                                        c-0.195-0.195-0.195-0.512,0-0.707s0.512-0.195,0.707,0l4.95,4.95c0.195,0.195,0.195,0.512,0,0.707l-4.95,4.95
                                        C10.756,10.852,10.628,10.9,10.5,10.9z"/>
                                    </svg>
                                </span>
                            </button>
                        </p>
                    </form>

                </div>`,

            created() {
                this.default(this.db);
            },

            methods: {
                submit() {
                    const vm = this;
                    this.$validator.validateAll().then((res) => {
                        if (res) { vm.vm.stepDatabase(); }
                    });
                },
                change(e) {
                    this.db = e.target.value;
                },
                default(val) {
                    if (!this.vm.config.database.connections[val].prefix) {
                        this.vm.config.database.connections[val].prefix = this.prefix;
                    }
                },
            },

            watch: {
                db(val) {
                    this.default(val);
                },
            },
        },

        site: {
            props: ['vm'],
            template: `
                <div class="uk-card uk-card-default uk-card-body">

                    <h1 class="uk-card-title uk-text-center">{{ 'Setup your site' | trans }}</h1>
                    <div class="uk-margin uk-text-muted uk-text-center">{{ 'Choose a title and create the administrator account.' | trans }}</div>

                    <form class="uk-form-horizontal tm-form-horizontal" @submit.prevent="submit">
                        <div class="uk-margin">
                            <label for="form-sitename" class="uk-form-label">{{ 'Site Title' | trans }}</label>
                            <div class="uk-form-controls">
                                <input id="form-sitename" class="uk-width-1-1 uk-input" type="text" name="name" v-model="vm.option['system/site'].title" v-validate="'required'">
                                <div class="uk-text-meta uk-text-danger" v-show="errors.first('name')">{{ 'Site title cannot be blank.' | trans }}</div>
                            </div>
                        </div>

                        <div class="uk-margin">
                            <label for="form-username" class="uk-form-label">{{ 'Username' | trans }}</label>
                            <div class="uk-form-controls">
                                <input id="form-username" class="uk-width-1-1 uk-input" type="text" name="user" v-model="vm.user.username" v-validate="{required: true, regex: /^[a-zA-Z0-9._\-]{3,}$/}">
                                <div class="uk-text-meta uk-text-danger" v-show="errors.first('user')">{{ 'Username cannot be blank and may only contain alphanumeric characters (A-Z, 0-9) and some special characters ("._-")' | trans }}</div>
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label for="form-password" class="uk-form-label">{{ 'Password' | trans }}</label>
                            <div class="uk-form-controls">
                                <div class="uk-inline uk-width-1-1">
                                    <a class="uk-form-icon uk-form-icon-flip" :uk-icon="vm.hidePassword ? 'lock' : 'unlock'" @click.prevent="vm.hidePassword = !vm.hidePassword"></a>
                                    <input id="form-password" class="uk-width-1-1 uk-input" :type="vm.hidePassword ? 'password' : 'text'" name="password" v-model="vm.user.password" v-validate="'required'">
                                </div>
                                <div class="uk-text-meta uk-text-danger" v-show="errors.first('password')">{{ 'Password cannot be blank.' | trans }}</div>
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label for="form-email" class="uk-form-label">{{ 'Email' | trans }}</label>
                            <div class="uk-form-controls">
                                <input id="form-email" class="uk-width-1-1 uk-input" type="email" name="email" v-model="vm.user.email" v-validate="'required|email'">
                                <div class="uk-text-meta uk-text-danger" v-show="errors.first('email')">{{ 'Field must be a valid email address.' | trans }}</div>
                            </div>
                        </div>
                        <p class="uk-text-right uk-margin-remove-bottom">
                            <button class="uk-button uk-button-primary" type="submit">
                                <span class="uk-text-middle">{{ 'Install' | trans }}</span>
                                <span class="uk-margin-small-left">
                                    <svg width="18" height="11" viewBox="0 0 18 11" xmlns="http://www.w3.org/2000/svg">
                                        <line fill="none" stroke="#FFFFFF" stroke-linecap="round" stroke-miterlimit="10" x1="3" y1="5.5" x2="15" y2="5.5"/>
                                        <path fill="#FFFFFF" d="M10.5,10.9c-0.128,0-0.256-0.049-0.354-0.146c-0.195-0.195-0.195-0.512,0-0.707l4.597-4.597l-4.597-4.597
                                        c-0.195-0.195-0.195-0.512,0-0.707s0.512-0.195,0.707,0l4.95,4.95c0.195,0.195,0.195,0.512,0,0.707l-4.95,4.95
                                        C10.756,10.852,10.628,10.9,10.5,10.9z"/>
                                    </svg>
                                </span>
                            </button>
                        </p>
                    </form>

                </div>`,

            methods: {
                submit() {
                    const vm = this;
                    this.$validator.validateAll().then((res) => {
                        if (res) { vm.vm.stepSite(); }
                    });
                },
            },
        },

        finish: {
            props: ['vm'],
            template: `
                <div>
                    <div class="uk-text-center" v-show="vm.status == 'install'">
                        <svg class="tm-loader" width="150" height="150" viewBox="0 0 150 150" xmlns="http://www.w3.org/2000/svg">
                            <g><circle cx="0" cy="0" r="70" fill="none" stroke-width="2"/></g>
                        </svg>
                    </div>

                    <div class="uk-panel uk-padding-small uk-text-center" v-show="vm.status == 'finished'">
                        <a :href="$url.route('admin')">
                            <svg class="tm-checkmark" width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                <polyline fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="5.125,63.25 27.375,89.375 95.25,18.875"/>
                            </svg>
                        </a>
                    </div>

                    <div class="uk-card uk-card-default uk-card-body" v-show="vm.status == 'failed'">
                        <h1>{{ 'Installation failed!' | trans }}</h1>
                        <div class="uk-text-break">{{ vm.message }}</div>
                        <p class="uk-text-right uk-margin-remove-bottom">
                            <button type="button" class="uk-button uk-button-primary" @click="vm.stepInstall">{{ 'Retry' | trans }}</button>
                        </p>
                    </div>
                </div>`,
        },
    },

};

Vue.ready(Installer);

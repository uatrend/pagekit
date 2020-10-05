<template>
    <div>
        <div class="uk-margin uk-flex uk-flex-middle uk-flex-between uk-flex-wrap">
            <div>
                <h2 class="uk-h3 uk-margin-remove">
                    {{ 'Mail' | trans }}
                </h2>
            </div>
            <div class="uk-margin-small">
                <button class="uk-button uk-button-primary" type="submit">
                    {{ 'Save' | trans }}
                </button>
            </div>
        </div>

        <div class="uk-margin">
            <label for="form-emailaddress" class="uk-form-label">{{ 'From Email' | trans }}</label>
            <div class="uk-form-controls">
                <input id="form-emailaddress" v-model="moduleOptions.from_address" class="uk-form-width-large uk-input" type="text">
            </div>
        </div>

        <div class="uk-margin">
            <label for="form-fromname" class="uk-form-label">{{ 'From Name' | trans }}</label>
            <div class="uk-form-controls">
                <input id="form-fromname" v-model="moduleOptions.from_name" class="uk-form-width-large uk-input" type="text">
            </div>
        </div>

        <div class="uk-margin">
            <label for="form-mailer" class="uk-form-label">{{ 'Mailer' | trans }}</label>
            <div class="uk-form-controls">
                <select id="form-mailer" v-model="moduleOptions.driver" class="uk-form-width-large uk-select">
                    <option value="mail">
                        {{ 'PHP Mailer' | trans }}
                    </option>
                    <option value="smtp">
                        {{ 'SMTP Mailer' | trans }}
                    </option>
                </select>
            </div>
        </div>

        <div v-show="'smtp' == moduleOptions.driver" class="uk-margin">
            <div class="uk-margin">
                <label for="form-smtpport" class="uk-form-label">{{ 'SMTP Port' | trans }}</label>
                <div class="uk-form-controls">
                    <input id="form-smtpport" v-model="moduleOptions.port" class="uk-form-width-large uk-input" type="text">
                </div>
            </div>

            <div class="uk-margin">
                <label for="form-smtphost" class="uk-form-label">{{ 'SMTP Host' | trans }}</label>
                <div class="uk-form-controls">
                    <input id="form-smtphost" v-model="moduleOptions.host" class="uk-form-width-large uk-input" type="text">
                </div>
            </div>

            <div class="uk-margin">
                <label for="form-smtpuser" class="uk-form-label">{{ 'SMTP User' | trans }}</label>
                <div class="uk-form-controls">
                    <input id="form-smtpuser" v-model="moduleOptions.username" class="uk-form-width-large uk-input" type="text" autocomplete="username">
                </div>
            </div>

            <div class="uk-margin">
                <label for="form-smtppassword" class="uk-form-label">{{ 'SMTP Password' | trans }}</label>
                <div class="uk-form-controls js-password">
                    <div class="uk-form-password">
                        <div class="uk-inline">
                            <a :uk-tooltip="hidePassword ? 'Show' : 'Hide' | trans" delay="500" pos="right" class="uk-form-icon uk-form-icon-flip" href="#" :uk-icon="hidePassword ? 'eye-closed': 'eye'" @click.prevent="hidePassword = !hidePassword" />
                            <input id="form-smtppassword" v-model="moduleOptions.password" autocomplete="current-password" class="uk-form-width-large uk-input" :type="hidePassword ? 'password' : 'text'">
                        </div>
                    </div>
                </div>
            </div>

            <div class="uk-margin">
                <label for="form-smtpencryption" class="uk-form-label">{{ 'SMTP Encryption' | trans }}</label>
                <div class="uk-form-controls">
                    <select id="form-smtpencryption" v-model="moduleOptions.encryption" class="uk-form-width-large uk-select">
                        <option value="">
                            {{ 'None' | trans }}
                        </option>
                        <option value="ssl" :disabled="!ssl">
                            {{ 'SSL' | trans }}
                        </option>
                        <option value="tls" :disabled="!ssl">
                            {{ 'TLS' | trans }}
                        </option>
                    </select>
                    <p v-if="!ssl" class="uk-margin-small-top uk-text-meta">
                        {{ 'Please enable the PHP Open SSL extension.' | trans }}
                    </p>
                </div>
            </div>
        </div>

        <div class="uk-margin">
            <div class="uk-form-controls">
                <button v-show="'smtp' == moduleOptions.driver" class="uk-button uk-button-default uk-text-truncate" type="button" @click="test('smtp')">
                    {{ 'Check Connection' | trans }}
                </button>
                <button class="uk-button uk-button-default uk-text-truncate" type="button" @click="test('email')">
                    {{ 'Send Test Email' | trans }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>

import SettingsMixin from '@system/modules/settings/app/mixins/settings-mixin';

const Mail = {

    mixins: [SettingsMixin, Theme.Mixins.Helper],

    section: {
        label: 'Mail',
        icon: 'mail',
        priority: 40
    },

    data() {
        return _.extend({
            hidePassword: true,
            processing: {
                smtp: false,
                email: false
            }
        }, window.$mail);
    },

    theme: {
        hideEls() {
            return this.$el.querySelectorAll('.uk-button-default');
        },
        elements() {
            const vm = this;
            return {
                testsmtp: {
                    scope: 'topmenu-left',
                    type: 'button',
                    caption: 'Check Connection',
                    class: 'uk-button tm-button-success',
                    spinner: () => vm.processing.smtp,
                    on: { click: () => vm.test('smtp') },
                    vif: () => vm.$theme.getActiveTab('leftTab', true) && vm.moduleOptions.driver === 'smtp',
                    watch: () => vm.$theme.getActiveTab('leftTab'),
                    priority: 0
                },
                testmail: {
                    scope: 'topmenu-left',
                    type: 'button',
                    caption: 'Send Test Email',
                    class: 'uk-button tm-button-success',
                    spinner: () => vm.processing.email,
                    on: { click: () => vm.test('email') },
                    vif: () => vm.$theme.getActiveTab('leftTab', true),
                    watch: () => vm.$theme.getActiveTab('leftTab'),
                    priority: 1
                }
            };
        }
    },

    watch: {
        'moduleOptions.driver': function (driver) {
            if ((driver === 'smtp') && (this.moduleOptions.encryption === null)) {
                this.moduleOptions.encryption = '';
            }
        }
    },

    methods: {

        test(driver) {
            this.processing[driver] = true;
            if (driver === 'email') this.processing.email = true;
            this.$http.post(`admin/system/${driver}`, { option: this.moduleOptions }).then(function (res) {
                const { data } = res;
                this.$notify(data.message, data.success ? '' : 'danger');
            }, function () {
                this.$notify('Ajax request to server failed.', 'danger');
            }).then(function () {
                setTimeout(() => {
                    this.processing[driver] = false;
                }, 200);
            });
        }

    }

};

export default Mail;

window.Settings.components['system-mail'] = Mail;

</script>

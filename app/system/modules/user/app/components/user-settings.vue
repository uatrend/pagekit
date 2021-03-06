<template>
    <div uk-grid class="">
        <div class="uk-width-3-4@m uk-width-2-3">
            <div class="uk-margin">
                <label for="form-username" class="uk-form-label">{{ 'Username' | trans }}</label>
                <v-input id="form-username" v-model="user.username" name="username" type="text" view="class: uk-form-width-large uk-input" :rules="{required: true, regex: /^[a-zA-Z0-9._\-]+$/}" autocomplete="new-username" message="Username cannot be blank and may only contain alphanumeric characters (A-Z, 0-9) and some special characters (&quot;._-&quot;)" />
            </div>

            <div class="uk-margin">
                <label for="form-name" class="uk-form-label">{{ 'Name' | trans }}</label>
                <v-input id="form-name" v-model="user.name" name="name" type="text" view="class: uk-form-width-large uk-input" :rules="{required: true}" autocomplete="new-name" message="Name cannot be blank." />
            </div>

            <div class="uk-margin">
                <label for="form-email" class="uk-form-label">{{ 'Email' | trans }}</label>
                <v-input id="form-email" v-model.lazy="user.email" name="email" type="email" view="class: uk-form-width-large uk-input" :rules="{required: true, email: true}" autocomplete="new-email" message="Field must be a valid email address." />
            </div>

            <div class="uk-margin">
                <label for="form-password" class="uk-form-label">{{ 'Password' | trans }}</label>
                <div v-show="user.id && !editingPassword" class="uk-form-controls uk-form-controls-text">
                    <a href="#" class="uk-text-small" @click.prevent="editingPassword = true">{{ 'Change password' | trans }}</a>
                </div>
                <div class="uk-form-controls" :class="{'uk-hidden' : (user.id && !editingPassword)}">
                    <div class="uk-form-password">
                        <div class="uk-inline">
                            <a :uk-tooltip="hidePassword ? 'Show' : 'Hide' | trans" delay="500" pos="right" class="uk-form-icon uk-form-icon-flip" href="#" :uk-icon="hidePassword ? 'eye-closed': 'eye'" @click.prevent="hidePassword = !hidePassword" tabindex="-1" />
                            <input id="form-password" v-model="password" autocomplete="new-password" class="uk-form-width-large uk-input" :type="hidePassword ? 'password' : 'text'">
                        </div>
                    </div>
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label">{{ 'Status' | trans }}</label>
                <div class="uk-form-controls uk-form-controls-text">
                    <p v-for="(status, key) in config.statuses" :key="key" class="uk-margin-small">
                        <label><input v-model="user.status" class="uk-radio" type="radio" :value="parseInt(key)" :disabled="config.currentUser == user.id"> {{ status }}</label>
                    </p>
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label">{{ 'Roles' | trans }}</label>
                <div class="uk-form-controls uk-form-controls-text">
                    <p v-for="role in config.roles" :key="role.id" class="uk-margin-small">
                        <label><input v-model="user.roles" class="uk-checkbox" type="checkbox" :value="role.id" :disabled="role.disabled"> {{ role.name }}</label>
                    </p>
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label">{{ 'Last login' | trans }}</label>
                <div class="uk-form-controls uk-form-controls-text">
                    <p>{{ $trans('%date%', { date: user.login ? $date(user.login) : $trans('Never') }) }}</p>
                </div>
            </div>

            <div class="uk-margin">
                <label class="uk-form-label">{{ 'Registered since' | trans }}</label>
                <div class="uk-form-controls uk-form-controls-text">
                    {{ user.registered ? $trans('%date%', { date: $date(user.registered) }) : '' }}
                </div>
            </div>
        </div>

        <div class="uk-width-1-4@m uk-width-1-3" v-show="user.email">
            <div class="uk-card uk-card-default uk-text-center uk-text-truncate">

                <div class="uk-card-media-top">
                    <img v-gravatar="user.email" height="280" width="280" :alt="user.name" class="uk-width-expand">
                </div>

                <div class="uk-card-footer uk-padding-small">
                    <div class="uk-flex uk-flex-center uk-flex-middle">
                        <h3 class="uk-card-title uk-margin-remove-bottom uk-text-truncate">{{ user.name }}</h3>
                        <i class="uk-preserve-width">
                            <i :title="(isNew ? 'New' : config.statuses[user.status]) | trans" :class="{
                                    'pk-icon-circle-primary': isNew,
                                    'pk-icon-circle-success': user.login && user.status,
                                    'pk-icon-circle-danger': !user.status
                                }"
                            />
                        </i>
                    </div>

                    <a class="uk-flex uk-flex-center uk-flex-middle uk-text-small" :href="'mailto:'+user.email">
                        <span class="uk-text-truncate">{{ user.email }}</span>
                        <i class="uk-preserve-width uk-text-success" v-show="config.emailVerification && user.data.verified" uk-icon="icon: check" ratio="1.1" :title="'Verified email address' | trans" />
                    </a>
                </div>

            </div>
        </div>
    </div>
</template>

<script>

import UserMixin from '../mixins/user-mixin';

export default {

    mixins: [UserMixin],

    section: { label: 'User' },

    data() {
        return {
            password: '',
            hidePassword: true,
            editingPassword: false
        };
    },

    computed: {

        isNew() {
            return !this.user.login && this.user.status;
        }

    },

    events: {

        'user-save': function (e, data) {
            data.password = this.password;
        }

    }

};

</script>

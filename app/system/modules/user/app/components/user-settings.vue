<template>
    <div uk-grid class="uk-grid-small">
        <div class="uk-width-2-3@s">
            <div class="uk-margin">
                <label for="form-username" class="uk-form-label">{{ 'Username' | trans }}</label>
                <div class="uk-form-controls">
                    <input
                        id="form-username"
                        v-model="user.username"
                        v-validate="{required: true, regex: /^[a-zA-Z0-9._\-]+$/}"
                        autocomplete="new-username"
                        class="uk-form-width-large uk-input"
                        type="text"
                        name="username"
                    >
                    <div v-show="errors.first('username')" class="uk-text-meta uk-text-danger">
                        {{ 'Username cannot be blank and may only contain alphanumeric characters (A-Z, 0-9) and some special characters ("._-")' | trans }}
                    </div>
                </div>
            </div>

            <div class="uk-margin">
                <label for="form-name" class="uk-form-label">{{ 'Name' | trans }}</label>
                <div class="uk-form-controls">
                    <input
                        id="form-name"
                        v-model="user.name"
                        v-validate="'required'"
                        autocomplete="new-name"
                        class="uk-form-width-large uk-input"
                        type="text"
                        name="name"
                    >
                    <div v-show="errors.first('name')" class="uk-text-meta uk-text-danger">
                        {{ 'Name cannot be blank.' | trans }}
                    </div>
                </div>
            </div>

            <div class="uk-margin">
                <label for="form-email" class="uk-form-label">{{ 'Email' | trans }}</label>
                <div class="uk-form-controls">
                    <input
                        id="form-email"
                        v-model.lazy="user.email"
                        v-validate="'required|email'"
                        autocomplete="new-email"
                        class="uk-form-width-large uk-input"
                        type="text"
                        name="email"
                    >
                    <div v-show="errors.first('email')" class="uk-text-meta uk-text-danger">
                        {{ 'Field must be a valid email address.' | trans }}
                    </div>
                </div>
            </div>

            <div class="uk-margin">
                <label for="form-password" class="uk-form-label">{{ 'Password' | trans }}</label>
                <div v-show="user.id && !editingPassword" class="uk-form-controls uk-form-controls-text">
                    <a href="#" @click.prevent="editingPassword = true">{{ 'Change password' | trans }}</a>
                </div>
                <div class="uk-form-controls" :class="{'uk-hidden' : (user.id && !editingPassword)}">
                    <div class="uk-form-password">
                        <input id="form-password" v-model="password" autocomplete="new-password" class="uk-form-width-large uk-input" :type="hidePassword ? 'password' : 'text'">
                        <a href="#" class="uk-form-password-toggle uk-text-small uk-display-block" @click.prevent="hidePassword = !hidePassword">{{ hidePassword ? 'Show' : 'Hide' | trans }}</a>
                    </div>
                </div>
            </div>

            <div class="uk-margin">
                <span class="uk-form-label">{{ 'Status' | trans }}</span>
                <div class="uk-form-controls uk-form-controls-text">
                    <p v-for="(status, key) in config.statuses" :key="key" class="uk-margin-small">
                        <label><input v-model="user.status" class="uk-radio" type="radio" :value="parseInt(key)" :disabled="config.currentUser == user.id"> {{ status }}</label>
                    </p>
                </div>
            </div>

            <div class="uk-margin">
                <span class="uk-form-label">{{ 'Roles' | trans }}</span>
                <div class="uk-form-controls uk-form-controls-text">
                    <p v-for="role in config.roles" :key="role.id" class="uk-margin-small">
                        <label><input v-model="user.roles" class="uk-checkbox" type="checkbox" :value="role.id" :disabled="role.disabled"> {{ role.name }}</label>
                    </p>
                </div>
            </div>

            <div class="uk-margin">
                <span class="uk-form-label">{{ 'Last login' | trans }}</span>
                <div class="uk-form-controls uk-form-controls-text">
                    <p>{{ $trans('%date%', { date: user.login ? $date(user.login) : $trans('Never') }) }}</p>
                </div>
            </div>

            <div class="uk-margin">
                <span class="uk-form-label">{{ 'Registered since' | trans }}</span>
                <div class="uk-form-controls uk-form-controls-text">
                    {{ user.registered ? $trans('%date%', { date: $date(user.registered) }) : '' }}
                </div>
            </div>
        </div>

        <div class="uk-width-expand@s">
            <div uk-grid class="uk-grid-collapse">
                <div class="uk-width-expand@s" />
                <div class="uk-width-5-6@s">
                    <div v-show="user.name" class="uk-card uk-card-default uk-text-center">
                        <div class="uk-card-media-top">
                            <img v-gravatar="user.email" height="280" width="280" :alt="user.name" class="uk-width-1-1">
                        </div>

                        <div class="uk-card-footer">
                            <h3 class="uk-card-title uk-margin-remove-bottom uk-text-break">
                                {{ user.name }}
                                <i
                                    :title="(isNew ? 'New' : config.statuses[user.status]) | trans"
                                    :class="{
                                        'pk-icon-circle-primary': isNew,
                                        'pk-icon-circle-success': user.access && user.status,
                                        'pk-icon-circle-danger': !user.status
                                    }"
                                />
                            </h3>

                            <div>
                                <a class="uk-text-break uk-text-small" :href="'mailto:'+user.email">{{ user.email }}</a><i v-show="config.emailVerification && user.data.verified" uk-icon="icon: check" :title="'Verified email address' | trans" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

module.exports = {

    section: {
        label: 'User',
    },

    props: ['user', 'config', 'form'],

    inject: ['$validator'],

    data() {
        return { password: '', hidePassword: true, editingPassword: false };
    },

    mounted() {

    },

    computed: {

        isNew() {
            return !this.user.login && this.user.status;
        },

    },

    events: {

        'save:user': function (data) {
            data.password = this.password;
        },

    },

};

</script>

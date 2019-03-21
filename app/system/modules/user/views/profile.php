<?php $view->script('profile', 'system/user:app/bundle/profile.js', ['vue']) ?>

<form id="user-profile" class="uk-container pk-user pk-user-profile uk-form-stacked uk-width-1-2@m uk-width-1-3@l" @submit.prevent="submit">

    <h1 class="uk-h2 uk-text-center">{{ 'Your Profile' | trans }}</h1>

    <div class="uk-margin">
        <input class="uk-width-1-1 uk-input" type="text" name="name" placeholder="<?= __('Name') ?>" v-model="user.name" v-validate="'required'">
        <div class="uk-text-small uk-text-danger" v-show="errors.first('name')">{{ 'Name cannot be blank.' | trans }}</div>
    </div>

    <div class="uk-margin">
        <input class="uk-width-1-1 uk-input" type="text" name="email" placeholder="<?= __('Email') ?>" v-model="user.email" v-validate="'required|email'">
        <div class="uk-text-small uk-text-danger" v-show="errors.first('email')">{{ 'Invalid Email.' | trans }}</div>
    </div>

    <div class="uk-margin">
        <a href="#" @click.prevent="changePassword = !changePassword">{{ 'Change password' | trans }}</a>
    </div>

    <div class="uk-margin js-password" v-if="changePassword">
        <div class="uk-inline uk-width-1-1">
            <a class="uk-form-icon uk-form-icon-flip uk-icon-link" :uk-icon="hidePassword ? 'lock' : 'unlock'" @click.prevent="hidePassword = !hidePassword" tabindex="-1"></a>
            <input class="uk-width-1-1 uk-input" :type="hidePassword ? 'password' : 'text'" placeholder="<?= __('Current Password') ?>" name="password_old" v-model="user.password.old" v-validate="'required'">
        </div>
        <div class="uk-text-meta uk-text-danger" v-show="errors.first('password_old')">{{ 'Password cannot be blank.' | trans }}</div>
    </div>

    <div class="uk-margin js-password" v-if="changePassword">
        <div class="uk-inline uk-width-1-1">
            <a class="uk-form-icon uk-form-icon-flip uk-icon-link" :uk-icon="hidePassword ? 'lock' : 'unlock'" @click.prevent="hidePassword = !hidePassword" tabindex="-1"></a>
            <input class="uk-width-1-1 uk-input" :type="hidePassword ? 'password' : 'text'" placeholder="<?= __('New Password') ?>" name="password_new" v-model="user.password.new" v-validate="'required'">
        </div>
        <div class="uk-text-meta uk-text-danger" v-show="errors.first('password_new')">{{ 'Password cannot be blank.' | trans }}</div>
    </div>

    <p class="uk-margin">
        <button class="uk-button uk-button-primary uk-button-large uk-width-1-1" type="submit">{{ 'Save' | trans }}</button>
    </p>

</form>

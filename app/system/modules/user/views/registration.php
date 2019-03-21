<?php $view->script('registration', 'system/user:app/bundle/registration.js', ['vue']) ?>

<form id="user-registration" class="pk-user pk-user-registration uk-form-stacked uk-width-1-2@m uk-width-1-3@l uk-container" @submit.prevent="valid" hidden v-cloak>

    <h1 class="uk-h2 uk-text-center"><?= __('Create an account') ?></h1>

    <div class="uk-alert-danger" uk-alert v-show="error">{{ error }}</div>

    <div class="uk-margin">
        <div class="uk-inline uk-width-1-1">
            <span class="uk-form-icon" uk-icon="icon: user"></span>
            <input class="uk-width-1-1 uk-input" type="text" name="username" placeholder="<?= __('Username') ?>" v-model="user.username" v-validate="{required:true, regex:/^[a-zA-Z0-9._\-]{3,}$/}">
        </div>
        <div class="uk-text-small uk-text-danger" v-show="errors.first('username')"><?= __('Username is invalid.') ?></div>
    </div>

    <div class="uk-margin">
        <div class="uk-inline uk-width-1-1">
            <span class="uk-form-icon" uk-icon="icon: user"></span>
            <input class="uk-width-1-1 uk-input" type="text" name="name" placeholder="<?= __('Name') ?>" v-model="user.name" v-validate="'required'">
        </div>
        <div class="uk-text-small uk-text-danger" v-show="errors.first('name')"><?= __('Name cannot be blank.') ?></div>
    </div>

    <div class="uk-margin">
        <div class="uk-inline uk-width-1-1">
            <span class="uk-form-icon" uk-icon="icon: mail"></span>
            <input class="uk-width-1-1 uk-input" type="email" name="email" placeholder="<?= __('Email') ?>" v-model="user.email" v-validate="'required|email'">
        </div>
        <div class="uk-text-small uk-text-danger" v-show="errors.first('email')"><?= __('Email address is invalid.') ?></div>
    </div>

    <div class="uk-margin">
        <div class="uk-inline uk-width-1-1">
            <a href="" class="uk-icon-link uk-form-icon uk-form-icon-flip" tabindex="-1" @click.prevent="hidePassword = !hidePassword" :title="hidePassword ? 'Show' : 'Hide' | trans">
                <span class="mdi mdi-18px" :class="{ 'mdi-eye-outline': hidePassword, 'mdi-eye-off-outline': !hidePassword }"></span>
            </a>
            <input class="uk-width-1-1 uk-input" name="password" :type="hidePassword ? 'password' : 'text'" placeholder="<?= __('Password') ?>" v-model="user.password" v-validate="{required: true, regex:/^.{6,}$/}">
        </div>
        <div class="uk-text-small uk-text-danger" v-show="errors.first('password')"><?= __('Password must be 6 characters or longer.') ?></div>
    </div>

    <p class="uk-margin">
        <button class="uk-button uk-button-primary uk-button-large uk-width-1-1" type="submit"><?= __('Sign up') ?></button>
    </p>

    <p class="uk-text-center"><?= __('Already have an account?') ?> <a href="<?= $view->url('@user/login') ?>"><?= __('Login!') ?></a></p>

</form>

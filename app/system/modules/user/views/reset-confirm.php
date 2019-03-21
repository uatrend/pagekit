<?php $view->script('reset-confirm', 'system/user:app/bundle/reset-confirm.js', ['vue']) ?>

<form id="reset-confirm" class="pk-user pk-user-reset uk-form-stacked uk-width-1-2@m uk-width-1-3@l uk-container" action="<?= $view->url('@user/resetpassword/confirm', ['key' => $activation]) ?>" method="post" ref="resetform" v-cloak>

    <?php if($error): ?>
    <div class="uk-alert-danger" uk-alert>
        <?= $error; ?>
    </div>
    <?php endif; ?>

    <h1 class="uk-h2 uk-text-center"><?= __('Password Confirmation') ?></h1>

    <div class="uk-margin js-password">
        <div class="uk-inline uk-width-1-1">
            <a class="uk-form-icon uk-form-icon-flip uk-icon-link" :uk-icon="hidePassword ? 'lock' : 'unlock'" @click.prevent="hidePassword = !hidePassword" tabindex="-1"></a>
            <input class="uk-width-1-1 uk-input" :type="hidePassword ? 'password' : 'text'" placeholder="<?= __('New Password') ?>" name="password" v-validate="{required:true, regex:/^.{6,}$/}">
        </div>
        <div class="uk-text-meta uk-text-danger" v-show="errors.first('password')">{{ 'Password cannot be blank and must be 6 characters or longer.' | trans }}</div>
    </div>

    <p class="uk-margin">
        <button class="uk-button uk-button-primary uk-button-large uk-width-1-1" @click.prevent="submit"><?= __('Confirm') ?></button>
    </p>

    <?php $view->token()->get() ?>

</form>

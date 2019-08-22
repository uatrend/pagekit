<?php $view->script('blog-settings', 'blog:app/bundle/settings.js', 'vue') ?>

<div id="settings" class="uk-form-horizontal" v-cloak>

    <div class="pk-grid-large" uk-grid>
        <div class="pk-width-sidebar">

            <div class="uk-panel">

                <ul class="uk-nav uk-nav-default pk-nav-large" uk-switcher="connect: #tab-content">
                    <li><a><i class="pk-icon-large-settings uk-margin-right"></i> {{ 'General' | trans }}</a></li>
                    <li><a><i class="pk-icon-large-comment uk-margin-right"></i> {{ 'Comments' | trans }}</a></li>
                </ul>

            </div>

        </div>
        <div class="pk-width-content">

            <ul id="tab-content" class="uk-switcher uk-margin">
                <li>

                    <div class="uk-margin uk-flex uk-flex-between uk-flex-wrap">
                        <div>
                            <h2 class="uk-h3 uk-margin-remove">{{ 'General' | trans }}</h2>
                        </div>
                        <div>
                            <button class="uk-button uk-button-primary" @click.prevent="save">{{ 'Save' | trans }}</button>
                        </div>
                    </div>

                    <div class="uk-margin">
                        <span class="uk-form-label">{{ 'Permalink' | trans }}</span>
                        <div class="uk-form-controls uk-form-controls-text">
                            <p class="uk-margin-small">
                                <label>
                                    <input class="uk-radio" type="radio" v-model="config.permalink.type" value="">
                                    <span class="uk-margin-small-left">{{ 'Numeric' | trans }} <code>{{ '/123' | trans }}</code></span>
                                </label>
                            </p>
                            <p class="uk-margin-small">
                                <label>
                                    <input class="uk-radio" type="radio" v-model="config.permalink.type" value="{slug}">
                                    <span class="uk-margin-small-left">{{ 'Name' | trans }} <code>{{ '/sample-post' | trans }}</code></span>
                                </label>
                            </p>
                            <p class="uk-margin-small">
                                <label>
                                    <input class="uk-radio" type="radio" v-model="config.permalink.type" value="{year}/{month}/{day}/{slug}">
                                    <span class="uk-margin-small-left">{{ 'Day and name' | trans }} <code>{{ '/2014/06/12/sample-post' | trans }}</code></span>
                                </label>
                            </p>
                            <p class="uk-margin-small">
                                <label>
                                    <input class="uk-radio" type="radio" v-model="config.permalink.type" value="{year}/{month}/{slug}">
                                    <span class="uk-margin-small-left">{{ 'Month and name' | trans }} <code>{{ '/2014/06/sample-post' | trans }}</code></span>
                                </label>
                            </p>
                            <p class="uk-margin-small">
                                <label>
                                    <input class="uk-radio" type="radio" v-model="config.permalink.type" value="custom">
                                    <span class="uk-margin-small-left">{{ 'Custom' | trans }}</span>
                                </label>
                                <input class="uk-form-small uk-input uk-margin-small-top" type="text" v-model="config.permalink.custom">
                            </p>
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label">{{ 'Posts per page' | trans }}</label>
                        <div class="uk-form-controls uk-form-controls-text">
                            <p class="uk-margin-small">
                                <input type="number" v-model="config.posts.posts_per_page" class="uk-form-width-small uk-input">
                            </p>
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label">{{ 'Default post settings' | trans }}</label>
                        <div class="uk-form-controls uk-form-controls-text">
                            <p class="uk-margin-small">
                                <label><input class="uk-checkbox" type="checkbox" v-model="config.posts.markdown_enabled"><span class="uk-margin-small-left">{{ 'Enable Markdown' | trans }}</span></label>
                            </p>
                            <p class="uk-margin-small">
                                <label><input class="uk-checkbox" type="checkbox" v-model="config.posts.comments_enabled"><span class="uk-margin-small-left">{{ 'Enable Comments' | trans }}</span></label>
                            </p>
                        </div>
                    </div>

                </li>
                <li>

                    <div class="uk-margin uk-flex uk-flex-between uk-flex-wrap">
                        <div>

                            <h2 class="uk-h3 uk-margin-remove">{{ 'Comments' | trans }}</h2>

                        </div>
                        <div>

                            <button class="uk-button uk-button-primary" @click.prevent="save">{{ 'Save' | trans }}</button>

                        </div>
                    </div>

                    <div class="uk-margin">
                        <span class="uk-form-label">{{ 'Comments' | trans }}</span>
                        <div class="uk-form-controls uk-form-controls-text">
                            <p class="uk-margin-small">
                                <label><input class="uk-checkbox" type="checkbox" v-model="config.comments.require_email"> {{ 'Require e-mail.' | trans }}</label>
                            </p>
                            <p class="uk-margin-small">
                                <input class="uk-checkbox" type="checkbox" v-model="config.comments.autoclose"> {{ 'Close comments on articles older than' | trans }}
                                <input class="uk-form-small uk-form-width-mini uk-input" type="number" v-model="config.comments.autoclose_days" min="1"> {{ 'days.' | trans }}
                            </p>
                        </div>
                    </div>

                    <div class="uk-margin">
                        <span class="uk-form-label">{{ 'Appearance' | trans }}</span>
                        <div class="uk-form-controls uk-form-controls-text">
                            <p class="uk-margin-small">
                                <label><input class="uk-checkbox" type="checkbox" v-model="config.comments.gravatar"> {{ 'Show Avatars from Gravatar.' | trans }}</label>
                            </p>
                            <p class="uk-margin-small">
                                <label>{{ 'Order comments by' | trans }}
                                    <select class="uk-form-small uk-select" v-model="config.comments.order">
                                        <option value="ASC">{{ 'latest last' | trans }}</option>
                                        <option value="DESC">{{ 'latest first' | trans }}</option>
                                    </select>
                                </label>
                            </p>
                            <p class="uk-margin-small">
                                <input class="uk-checkbox" type="checkbox" v-model="config.comments.nested"> {{ 'Enable nested comments of' | trans }}
                                <input class="uk-form-small uk-form-width-mini uk-input" type="number" v-model="config.comments.max_depth" min="2" max="10"> {{ 'levels deep.' | trans }}
                            </p>
                        </div>
                    </div>

                </li>
            </ul>

        </div>
    </div>

</div>

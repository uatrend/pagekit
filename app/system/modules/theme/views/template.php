<!DOCTYPE html>
<html lang="<?= str_replace('_', '-', $intl->getLocaleTag()) ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <?php $view->style('theme', 'system/theme:css/theme.css') ?>
        <?php $view->script('theme', 'system/theme:js/theme.js', ['vue']) ?>
        <?= $view->render('head') ?>
    </head>
    <body class="<?= $pid ?>" pid="<?= $pid ?>">

        <div id="sidebar" class="tm-sidebar-wrapper tm-navbar-fixed" v-cloak>

            <div id="header" class="tm-header">

                <div class="tm-headerbar">
                     <div class="uk-container">
                        <div class="uk-flex uk-flex-between uk-flex-middle">

                            <div class="uk-flex uk-flex-middle">

                                <div class="uk-offcanvas-content">

                                    <a class="offcanvas-menu uk-hidden@s" uk-navbar-toggle-icon href="#offcanvas" uk-toggle="target: #offcanvas"></a>

                                    <div id="offcanvas" uk-offcanvas="" class="tm-offcanvas">
                                        <div class="uk-offcanvas-bar">

                                            <ul class="uk-nav uk-nav-default">
                                                <li class="uk-nav-header" v-show="subnav">
                                                    <img class="uk-margin-small-right" width="30" height="30" :alt="item.label | trans" :src="item.icon">
                                                    <span> {{ item.label | trans }} </span>
                                                </li>
                                                <li :class="{ 'uk-active': item.active }" v-for="item in subnav">
                                                    <a :href="item.url">
                                                        {{ item.label | trans }}
                                                    </a>
                                                </li>
                                                <li class="uk-nav-divider" v-show="subnav"></li>
                                                <li class="uk-nav-header">{{ 'Extensions' | trans }}</li>
                                                <li :class="{ 'uk-active': item.active }" v-for="item in nav">
                                                    <a :href="item.url">
                                                        <img class="uk-margin-small-right" width="27" height="27" :alt="item.label | trans" :src="item.icon"> {{ item.label | trans }}
                                                    </a>
                                                </li>
                                            </ul>

                                        </div>
                                    </div>

                                </div>


                                <h1 class="tm-heading uk-h4 uk-hidden">{{ item.label | trans }}</h1>

                                <a class="tm-logo uk-visible@s" :href="$url.route('')" target="_blank" :title="'Visit Site' | trans">
                                    <img  src="<?= $view->url()->getStatic('app/system/assets/images/pagekit-logo-text.svg') ?>" alt="pagekit">
                                </a>
                            </div>

                            <h1 class="tm-heading uk-h4 uk-hidden@s">{{ item.label | trans }}</h1>

                            <div class="uk-offcanvas-content">

                                <a href="#offcanvas-flip" class="offcanvas-user uk-hidden@s" uk-toggle="target: #offcanvas-flip">
                                    <i class="uk-icon-button" uk-icon="user" ratio="1" style="width: 30px; height: 30px;"></i>
                                </a>

                                <div id="offcanvas-flip" uk-offcanvas="flip: true" class="tm-offcanvas">
                                    <div class="uk-offcanvas-bar">

                                        <ul class="uk-nav uk-nav-default">
                                            <li class="uk-nav-header">
                                                <img class="uk-border-circle uk-margin-small-right" height="32" width="32" :title="user.name" v-gravatar="user.email">
                                                {{ user.username }}
                                            </li>
                                        </ul>
                                        <ul class="uk-iconnav uk-iconnav-vertical uk-margin-small-top uk-text-small">
                                            <li><a :href="$url.route('')" target="_blank"><span class="uk-margin-small-right" uk-icon="home"></span><span class="uk-text-middle">{{ 'Visit Site' | trans }}</span></a></li>
                                            <li><a :href="$url.route('admin/user/edit', {id: user.id})"><span class="uk-margin-small-right" uk-icon="cog"></span><span class="uk-text-middle">{{ 'Settings' | trans }}</span></a></li>
                                            <li><a href="<?= $view->url('@user/logout', ['redirect' => 'admin/login']) ?>"><span class="uk-margin-small-right" uk-icon="sign-out"></span><span class="uk-text-middle">{{ 'Logout' | trans }}</span></a></li>
                                        </ul>

                                    </div>
                                </div>

                            </div>

                            <div class="uk-light uk-flex uk-flex-middle uk-visible@s">

                                <ul class="uk-iconnav">
                                    <li><a class="uk-link-muted" uk-icon="question" href="https://discord.gg/e7Kw47E" :title="'Get Help' | trans " target="_blank"></a></li>
                                    <li><a class="uk-link-muted" uk-icon="home" :href="$url.route('')" :title="'Visit Site' | trans " target="_blank"></a></li>
                                    <li><a class="uk-link-muted" uk-icon="sign-out" href="<?= $view->url('@user/logout', ['redirect' => 'admin/login']) ?>" :title="'Logout' | trans "></a></li>
                                </ul>

                                <div class="uk-margin-small-left"><a class="uk-link-muted" :href="$url.route('admin/user/edit', {id: user.id})" :title="'Profile' | trans "><img class="uk-border-circle uk-margin-small-right" height="32" width="32" :title="user.name" v-gravatar="user.email"><span class="uk-text-small uk-text-middle" v-text="user.username"></span></a></div>

                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <div class="tm-navbar uk-visible@s" :style="style('body-wrapper')">
                <div class="uk-container">
                    <nav class="uk-navbar-container" uk-navbar>
                        <div class="uk-navbar-left">
                            <div class="uk-logo uk-margin-remove uk-padding-remove uk-flex uk-flex-middle">
                                <img width="30" height="30" :alt="item.label | trans" :src="item.icon" class="uk-margin-small-right">
                                <span class="uk-margin-remove uk-h4">{{ item.label | trans }}</span>
                            </div>
                        </div>
                        <div class="uk-navbar-right">
                            <ul class="uk-navbar-nav uk-flex-middle">
                                <li :class="{ 'uk-active': item.active }" v-for="item in subnav">
                                    <a :href="item.url">
                                        {{ item.label | trans }}
                                    </a>
                                    <div class="uk-navbar-dropdown" v-if="getChild(item.id, true)">
                                        <ul class="uk-nav uk-navbar-dropdown-nav">
                                            <li :class="{ 'uk-active': subitem.active }" v-for="subitem in getChild(item.id, false)">
                                                <a :href="subitem.url">{{ subitem.label | trans }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>

            <div class="tm-sidebar-menu uk-visible@s" :style="style('sidebar')">

                <ul uk-nav>
                    <li class="tm-toggle-sidebar">
                        <a v-on:click="toggleSidebar()" uk-tooltip="<?php echo __('Toggle sidebar') ?>" pos="right" delay="200">
                            <i uk-navbar-toggle-icon></i>
                            <span v-if="sb"><?= __('Extensions') ?></span>
                        </a>
                    </li>
                </ul>
                <ul id="js-appnav" class="uk-nav-default uk-nav-parent-icon" uk-nav data-url="<?= $view->url('@system/adminmenu') ?>" uk-sortable="cls-custom: tm-sortable-dragged; handle: .tm-menu-item">
                    <li :class="{ 'uk-active': item.active }" v-for="item in nav" :data-id="item.id" :uk-tooltip="item.label | trans" pos="right" delay="200">
                        <a class="tm-menu-item" :href="item.url">
                            <i><img :alt="item.label | trans" :src="item.icon"></i>
                            <span v-if="sb">{{ item.label | trans }}</span>
                        </a>
                    </li>
                </ul>

            </div>

        </div>

        <div class="tm-body-wrapper">

            <main class="tm-main">
                <div class="uk-container">
                    <div class="tm-content" uk-height-viewport="expand: true">
                        <?= $view->render('content') ?>
                    </div>
                </div>
            </main>

            <div class="uk-hidden"><?= $view->render('messages') ?></div>

        </div>
    </body>
</html>

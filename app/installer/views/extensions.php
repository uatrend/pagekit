<?php $view->script('extensions', 'installer:app/bundle/extensions.js', ['vue']); ?>

<div id="extensions" v-cloak>

    <div class="uk-margin uk-flex uk-flex-between uk-flex-wrap">
        <div class="uk-flex uk-flex-middle uk-flex-wrap" >
            <h2 class="uk-h3 uk-margin-remove">{{ 'Extensions' | trans }}</h2>
            <div class="uk-search uk-search-default pk-search">
                <span uk-search-icon></span>
                <input class="uk-search-input" type="search" v-model="search">
            </div>
        </div>
        <div class="uk-hidden@m">
            <package-upload :api="api" :packages="packages" type="extension"></package-upload>
        </div>
    </div>

    <div class="uk-overflow-auto">
        <table class="uk-table uk-table-hover uk-table-middle">
            <thead>
                <tr>
                    <th colspan="2">{{ 'Name' | trans }}</th>
                    <th class="uk-table-shrink"></th>
                    <th class="uk-table-shrink uk-text-center">{{ 'Status' | trans }}</th>
                    <th class="pk-table-width-100 uk-text-center">{{ 'Version' | trans }}</th>
                    <th class="pk-table-width-100">{{ 'Folder' | trans }}</th>
                    <th class="uk-table-shrink uk-preserve-width">
                        <ul class="uk-iconnav uk-flex-nowrap uk-invisible">
                            <li v-for="i in [1, 2]"><span uk-icon="info"></span></li>
                        </ul>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="uk-visible-toggle" v-for="pkg in filterBy(packages, search, 'title')">
                    <td class="uk-table-shrink uk-preserve-width">
	                    <img width="40" height="40" :src="icon(pkg)" :alt="pkg.title">
                    </td>
                    <td class="pk-table-min-width-100 uk-text-nowrap">
                        <a @click="settings(pkg)" v-if="pkg.enabled && pkg.settings">{{ pkg.title }}</a>
                        <span v-else>{{ pkg.title }}</span>
                        <div class="uk-text-muted">{{ pkg.authors[0].name }}</div>
                    </td>
                    <td>
                        <a class="uk-button tm-button-success uk-button-small" @click="update(pkg, updates)" v-show="updates && updates[pkg.name]">{{ 'Update' | trans }}</a>
                    </td>
                    <td class="uk-text-center">
                        <a class="pk-icon-circle-success" :title="'Enabled' | trans" v-if="pkg.enabled" @click="disable(pkg)"></a>
                        <a class="pk-icon-circle-danger" :title="'Disabled' | trans" v-else @click="enable(pkg)"></a>
                    </td>
                    <td class="uk-text-center">{{ pkg.version }}</td>
                    <td class="pk-table-max-width-150 uk-text-truncate">/{{ pkg.name }}</td>
                    <td class="uk-preserve-width">
                        <ul class="uk-iconnav uk-flex-nowrap uk-invisible-hover">
                            <li><a uk-icon="info" :uk-tooltip="'View Details' | trans" @click.prevent="details(pkg)"></a></li>
                            <li v-show="pkg.enabled && pkg.permissions"><a uk-icon="lock" :uk-tooltip="'View Permissions' | trans" :href="$url.route('admin/user/permissions#{name}', {name:pkg.module})"></a></li>
                            <li v-show="!pkg.enabled"><a uk-icon="trash" :uk-tooltip="'Delete' | trans" @click="uninstall(pkg, packages)" v-confirm="'Uninstall extension?'"></a></li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <h3 class="uk-h2 uk-text-muted uk-text-center" v-show="empty(packages)">{{ 'No extension found.' | trans }}</h3>

    <v-modal ref="details">
        <package-details :api="api" :package="package"></package-details>
    </v-modal>

    <v-modal ref="settings">
        <component :is="view" :package="package"></component>
    </v-modal>

</div>

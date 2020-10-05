<?php $view->script('info', 'app/system/modules/info/app/bundle/info.js', 'vue') ?>

<div id="info" class="pk-grid-large" uk-grid v-cloak>
    <div class="pk-width-sidebar">

        <div class="uk-panel">
            <ul class="uk-nav uk-nav-default pk-nav-large" uk-switcher="connect: .uk-switcher">
                <li><a><span class="uk-margin-right" uk-icon="server" ratio="1.25"></span><span class="uk-text-middle">{{ 'System' | trans }}</span></a></li>
                <li><a><span class="uk-margin-right" uk-icon="code" ratio="1.25"></span><span class="uk-text-middle">{{ 'PHP' | trans }}</span></a></li>
                <li><a><span class="uk-margin-right" uk-icon="database" ratio="1.25"></span><span class="uk-text-middle">{{ 'Database' | trans }}</span></a></li>
                <li><a><span class="uk-margin-right" uk-icon="lock-file" ratio="1.25"></span><span class="uk-text-middle">{{ 'Permissions' | trans }}</span></a></li>
            </ul>
        </div>

    </div>
    <div class="pk-width-content">

        <ul class="uk-switcher uk-margin">
            <li>
                <h2 class="uk-h3">{{ 'System' | trans }}</h2>
                <div class="uk-overflow-auto">
                    <table class="uk-table uk-table-hover">
                        <thead>
                            <tr>
                                <th class="pk-table-width-150">{{ 'Setting' | trans }}</th>
                                <th>{{ 'Value' | trans }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="uk-text-nowrap">{{ 'Pagekit Version' | trans }}</td>
                                <td>{{ info.version }} (UIkit {{ UIkitVersion }}, Vue {{ VueVersion }})</td>
                            </tr>
                            <tr>
                                <td class="uk-text-nowrap">{{ 'Web Server' | trans }}</td>
                                <td class="pk-table-text-break">{{ info.server }}</td>
                            </tr>
                            <tr>
                                <td class="uk-text-nowrap">{{ 'User Agent' | trans }}</td>
                                <td class="pk-table-text-break">{{ info.useragent }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </li>
            <li>
                <h2 class="uk-h3">{{ 'PHP' | trans }}</h2>
                <div class="uk-overflow-auto">
                    <table class="uk-table uk-table-hover">
                        <thead>
                            <tr>
                                <th class="pk-table-width-150">{{ 'Setting' | trans }}</th>
                                <th>{{ 'Value' | trans }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ 'Version' | trans }}</td>
                                <td>{{ info.phpversion }}</td>
                            </tr>
                            <tr>
                                <td>{{ 'Handler' | trans }}</td>
                                <td>{{ info.sapi_name }}</td>
                            </tr>
                            <tr>
                                <td>{{ 'Built On' | trans }}</td>
                                <td class="pk-table-text-break">{{ info.php }}</td>
                            </tr>
                            <tr>
                                <td>{{ 'Extensions' | trans }}</td>
                                <td class="pk-table-text-break">{{ info.extensions }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </li>
            <li>
                <h2 class="uk-h3">{{ 'Database' | trans }}</h2>
                <div class="uk-overflow-auto">
                    <table class="uk-table uk-table-hover">
                        <thead>
                            <tr>
                                <th class="pk-table-width-150">{{ 'Setting' | trans }}</th>
                                <th>{{ 'Value' | trans }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ 'Driver' | trans }}</td>
                                <td>{{ info.dbdriver }}</td>
                            </tr>
                            <tr>
                                <td>{{ 'Version' | trans }}</td>
                                <td>{{ info.dbversion }}</td>
                            </tr>
                            <tr>
                                <td>{{ 'Client' | trans }}</td>
                                <td class="pk-table-text-break">{{ info.dbclient }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </li>
            <li>
                <h2 class="uk-h3">{{ 'Permission' | trans }}</h2>
                <div class="uk-overflow-auto">
                    <table class="uk-table uk-table-hover">
                        <thead>
                            <tr>
                                <th>{{ 'Path' | trans }}</th>
                                <th class="pk-table-width-200">{{ 'Status' | trans }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(writable, dir) in info.directories">
                                <td>{{ dir }}</td>
                                <td class="uk-text-success" v-if="writable">{{ 'Writable' | trans }}</span></td>
                                <td class="uk-text-danger" v-else>{{ 'Unwritable' | trans }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </li>
        </ul>

    </div>
</div>

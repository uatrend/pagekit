<?php $view->script('role-index', 'system/user:app/bundle/role-index.js', ['vue']) ?>

<div id="roles" v-cloak>

    <div class="pk-grid-large" uk-grid>
        <div class="pk-width-sidebar">

            <div class="uk-panel">

                <ul class="uk-nav uk-nav-default pk-nav-large" ref="sortable">
                    <li class="uk-visible-toggle" v-for="(role, key_role) in orderBy(roles, 'priority')" :key="role.id" :data-id="role.id" :class="{'uk-active': current.id === role.id}">
                        <a class="uk-sortable-handle tm-cursor-pointer uk-position-relative" @click.prevent="config.role = role.id">{{ role.name }}
                        	<ul class="uk-invisible-hover uk-iconnav uk-position-center-right uk-margin-small-right" v-if="!role.locked">
                            	<li><span class="uk-icon-link" uk-icon="file-edit" :uk-tooltip="'Edit' | trans" @click="edit(role)"></span></li>
                            	<li><span class="uk-icon-link" uk-icon="trash" :uk-tooltip="'Delete' | trans" @click="remove(role)" v-confirm="'Delete role?'"></span></li>
                        	</ul>
                        </a>
                    </li>
                </ul>

                <p>
                    <a class="uk-button uk-button-default" @click.prevent="edit()">{{ 'Add Role' | trans }}</a>
                </p>

            </div>

        </div>
        <div class="pk-width-content">

            <h2 class="uk-h3">{{ current.name }}</h2>

            <div class="uk-overflow-auto uk-margin" v-for="(group, key_group) in permissions" :key="key_group">
                <table class="uk-table uk-table-hover uk-table-middle">
                    <thead>
                        <tr>
                            <th class="pk-table-min-width-200">{{ key_group }}</th>
                            <th class="uk-table-shrink"></th>
                            <th class="uk-table-shrink"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(permission, key_permission) in group" :class="{'uk-visible-toggle': permission.trusted}" :key="key_permission">
                            <td class="pk-table-text-break">
                                <div :uk-tooltip="permission.description | trans" pos="pos-left">{{ permission.title | trans }}</div>
                            </td>
                            <td class="uk-preserve-width">
                                <i class="uk-invisible-hover uk-text-danger" uk-icon="warning" :uk-tooltip="'Grant this permission to trusted roles only to avoid security implications.' | trans" v-if="permission.trusted"></i>
                            </td>
                            <td class="uk-text-center">
                                <span class="uk-position-relative" v-show="showFakeCheckbox(current, key_permission)">
                                    <input class="uk-checkbox" type="checkbox" checked disabled>
                                    <span class="uk-position-cover" v-if="!current.administrator" @click="addPermission(current, key_permission)" @click="savePermissions(current)"></span>
                                </span>
                                <input class="uk-checkbox" type="checkbox" :value="key_permission" v-show="!showFakeCheckbox(current, key_permission)" v-model="current.permissions" @click="savePermissions(current)">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <v-modal ref="modal" :options="{bgClose: false}">

        <validation-observer v-slot="{ invalid, passes }">
            <div class="uk-form-stacked">

                <div class="uk-modal-header">
                    <h2 class="uk-h4">{{ (role.id ? 'Edit Role':'Add Role') | trans }}</h2>
                </div>

                <div class="uk-modal-body">

                    <div class="uk-margin">
                        <label for="form-name" class="uk-form-label">{{ 'Name' | trans }}</label>
                        <v-input id="form-name" name="name" type="text" view="class: uk-width-1-1 uk-input" rules="required" v-model.trim="role.name" message="Name cannot be blank." />
                    </div>

                </div>

                <div class="uk-modal-footer uk-text-right">
                    <button class="uk-button uk-button-text uk-margin-right uk-modal-close" type="button" autofocus>{{ 'Cancel' | trans }}</button>
                    <button class="uk-button uk-button-primary" :disabled="invalid || !role.name" @click.prevent="passes(save)">{{ 'Save' | trans }}</button>
                </div>

            </div>
        </validation-observer>

    </v-modal>

</div>

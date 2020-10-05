<template>
    <div class="uk-form-horizontal">
        <div class="uk-margin-small">
            <label for="form-class" class="uk-form-label">{{ 'HTML Class' | trans }}</label>
            <div class="uk-form-controls">
                <input id="form-class" v-model="theme.html_class" class="uk-form-width-large uk-input" type="text">
            </div>
        </div>

        <template v-if="node.type === 'page'">
            <div class="uk-margin-small">
                <span class="uk-form-label">{{ 'Title' | trans }}</span>
                <div class="uk-form-controls uk-form-controls-text">
                    <label><input v-model="theme.title_hide" class="uk-checkbox" type="checkbox"> {{ 'Hide Title' | trans }}</label>
                </div>
            </div>

            <div class="uk-margin-small">
                <span class="uk-form-label">{{ 'Title Size' | trans }}</span>
                <div class="uk-form-controls uk-form-controls-text">
                    <label><input v-model="theme.title_large" class="uk-checkbox" type="checkbox"> {{ 'Extra large title.' | trans }}</label>
                </div>
            </div>

            <div class="uk-margin-small">
                <span class="uk-form-label">{{ 'Alignment' | trans }}</span>
                <div class="uk-form-controls uk-form-controls-text">
                    <label><input v-model="theme.alignment" class="uk-checkbox" type="checkbox"> {{ 'Center the title and content.' | trans }}</label>
                </div>
            </div>

            <div class="uk-margin-small">
                <span class="uk-form-label">{{ 'Content' | trans }}</span>
                <div class="uk-form-controls uk-form-controls-text">
                    <label><input v-model="theme.content_hide" class="uk-checkbox" type="checkbox"> {{ 'Hide content.' | trans }}</label>
                </div>
            </div>
        </template>

        <div v-if="!theme.content_hide" class="uk-margin-small">
            <span class="uk-form-label">{{ 'Sidebar' | trans }}</span>
            <div class="uk-form-controls uk-form-controls-text">
                <label><input v-model="theme.sidebar_first" class="uk-checkbox" type="checkbox"> {{ 'Show the sidebar before the content.' | trans }}</label>
            </div>
        </div>

        <div class="uk-margin-small">
            <div v-for="name in visiblePositions" :key="name" class="uk-margin-small">
                <hr class="uk-margin-small">
                <label class="uk-form-label uk-text-capitalize">{{ name === 'main' ? 'Main': configPositions[name] }} {{ 'Position' | trans }}</label>
                <div class="uk-form-controls uk-form-controls-text">
                    <div class="uk-child-width-1-2@m uk-grid-small uk-height-match" uk-grid>
                        <div>
                            <div class="uk-margin-small">
                                <input-image v-model="positions[name]['image']" class-name="uk-form-width-large" />
                                <div class="uk-text-meta">
                                    <span>{{ 'Select a background image for the position.' | trans }}</span>
                                </div>
                            </div>
                            <div v-if="positions[name]['image']" class="uk-margin-small-top uk-form-width-large uk-responsive-width">
                                <div class="uk-grid-small" uk-grid>
                                    <div class="uk-width-expand">
                                        <select v-model="positions[name]['image_position']" class="uk-select">
                                            <option value="top-left">
                                                {{ 'Top Left' | trans }}
                                            </option>
                                            <option value="top-center">
                                                {{ 'Top Center' | trans }}
                                            </option>
                                            <option value="top-right">
                                                {{ 'Top Right' | trans }}
                                            </option>
                                            <option value="center-left">
                                                {{ 'Center Left' | trans }}
                                            </option>
                                            <option value="">
                                                {{ 'Center' | trans }}
                                            </option>
                                            <option value="center-right">
                                                {{ 'Center Right' | trans }}
                                            </option>
                                            <option value="bottom-left">
                                                {{ 'Bottom Left' | trans }}
                                            </option>
                                            <option value="bottom-center">
                                                {{ 'Bottom Center' | trans }}
                                            </option>
                                            <option value="bottom-right">
                                                {{ 'Bottom Right' | trans }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="uk-width-expand">
                                        <select v-model="positions[name]['effect']" class="uk-select">
                                            <option value="">
                                                {{ 'None' | trans }}
                                            </option>
                                            <option value="fixed">
                                                {{ 'Fixed' | trans }}
                                            </option>
                                            <option value="parallax">
                                                {{ 'Parallax' | trans }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="uk-text-meta">
                                    <span>{{ 'Select background position and effect.' | trans }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="uk-margin-small uk-form-width-large uk-responsive-width">
                                <div class="uk-grid-small" uk-grid>
                                    <div class="uk-width-expand">
                                        <select v-model="positions[name]['style']" class="uk-select">
                                            <option value="uk-section-default">
                                                {{ 'Default' | trans }}
                                            </option>
                                            <option value="uk-section-primary">
                                                {{ 'Primary' | trans }}
                                            </option>
                                            <option value="uk-section-secondary">
                                                {{ 'Secondary' | trans }}
                                            </option>
                                            <option value="uk-section-muted">
                                                {{ 'Muted' | trans }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="uk-width-expand">
                                        <select v-model="positions[name]['size']" class="uk-select">
                                            <option value="">
                                                {{ 'Default' | trans }}
                                            </option>
                                            <option value="uk-section-xsmall">
                                                {{ 'XSmall' | trans }}
                                            </option>
                                            <option value="uk-section-small">
                                                {{ 'Small' | trans }}
                                            </option>
                                            <option value="uk-section-large">
                                                {{ 'Large' | trans }}
                                            </option>
                                            <option value="uk-section-xlarge">
                                                {{ 'XLarge' | trans }}
                                            </option>
                                            <option value="uk-padding-remove-vertical">
                                                {{ 'Padding remove' | trans }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div v-if="positions[name]['size'] !== 'uk-padding-remove-vertical'" class="uk-margin-small">
                                    <div class="uk-text-small">
                                        <label :class="{'uk-text-meta': !positions[name]['padding_remove_top']}"><input v-model="positions[name]['padding_remove_top']" class="uk-checkbox" type="checkbox"> {{ 'Remove section top padding.' | trans }}</label>
                                    </div>
                                    <div class="uk-text-small">
                                        <label :class="{'uk-text-meta': !positions[name]['padding_remove_bottom']}"><input v-model="positions[name]['padding_remove_bottom']" class="uk-checkbox" type="checkbox"> {{ 'Remove section bottom padding.' | trans }}</label>
                                    </div>
                                </div>
                                <div class="uk-text-meta">
                                    <span>{{ 'Select section style and padding.' }}</span>
                                </div>
                            </div>
                            <div class="uk-margin-small-top uk-form-width-large uk-responsive-width">
                                <div class="uk-grid-small" uk-grid>
                                    <div class="uk-width-expand">
                                        <select v-model="positions[name]['width']" class="uk-select">
                                            <option value="">
                                                {{ 'Default' | trans }}
                                            </option>
                                            <option value="xsmall">
                                                {{ 'XSmall' | trans }}
                                            </option>
                                            <option value="small">
                                                {{ 'Small' | trans }}
                                            </option>
                                            <option value="large">
                                                {{ 'Large' | trans }}
                                            </option>
                                            <option value="expand">
                                                {{ 'Expand' | trans }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="uk-width-expand">
                                        <select v-model="positions[name]['height']" class="uk-select">
                                            <option value="">
                                                {{ 'None' | trans }}
                                            </option>
                                            <option value="full">
                                                {{ 'Viewport' | trans }}
                                            </option>
                                            <option value="percent">
                                                {{ 'Viewport (Minus 20%)' | trans }}
                                            </option>
                                            <option value="section">
                                                {{ 'Viewport (Minus the following section)' | trans }}
                                            </option>
                                            <option value="expand">
                                                {{ 'Expand' | trans }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="uk-text-meta">
                                    <span>{{ 'Select container width and viewport height.' | trans }}</span>
                                </div>
                            </div>
                            <div class="uk-margin-small">
                                <select v-model="positions[name]['vertical_align']" class="uk-select uk-form-width-large">
                                    <option value="">
                                        {{ 'Top' | trans }}
                                    </option>
                                    <option value="middle">
                                        {{ 'Middle' | trans }}
                                    </option>
                                    <option value="bottom">
                                        {{ 'Bottom' | trans }}
                                    </option>
                                </select>
                                <div class="uk-text-meta">
                                    <span>{{ 'Select container alignment.' | trans }}</span>
                                </div>
                            </div>
                            <div class="uk-margin-small-top">
                                <div class="uk-text-small">
                                    <label :class="{'uk-text-meta': !positions[name]['header_transparent']}"><input v-model="positions[name]['header_transparent']" class="uk-checkbox" type="checkbox"> {{ 'Transparent header.' | trans }}</label>
                                </div>
                                <div v-if="positions[name]['header_transparent']" class="uk-text-small">
                                    <label :class="{'uk-text-meta': !positions[name]['header_preserve_color']}"><input v-model="positions[name]['header_preserve_color']" class="uk-checkbox" type="checkbox"> {{ 'Preserve header colors.' | trans }}</label>
                                </div>
                                <div v-if="positions[name]['header_transparent']" class="uk-text-small">
                                    <label :class="{'uk-text-meta': !positions[name]['header_transparent_noplaceholder']}"><input v-model="positions[name]['header_transparent_noplaceholder']" class="uk-checkbox" type="checkbox"> {{ 'Pull content beneath navbar.' | trans }}</label>
                                </div>
                                <div v-if="positions[name]['style'] === 'uk-section-primary'||positions[name]['style'] === 'uk-section-secondary'" class="uk-text-small">
                                    <label :class="{'uk-text-meta': !positions[name]['preserve_color']}"><input v-model="positions[name]['preserve_color']" type="checkbox" class="uk-checkbox"> {{ 'Preserve section colors.' | trans }}</label>
                                </div>
                                <div class="uk-text-small">
                                    <label :class="{'uk-text-meta': !positions[name]['overlap']}"><input v-model="positions[name]['overlap']" type="checkbox" class="uk-checkbox"> {{ 'Apply a border image and a negative offset to the section.' | trans }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import NodeMixin from '@system/modules/site/app/mixins/node-mixin';

const NodeTheme = {

    section: {
        label: 'Theme',
        priority: 90
    },

    mixins: [NodeMixin],

    props: ['roles', 'value'],

    data() {
        return {
            node: this.value,
            configPositions: window.$theme.positions,
            customPositions: window.$theme.position.customizable,
            positionDefaults: window.$theme.position.defaults
        };
    },

    computed: {

        theme() {
            return this.node.theme;
        },

        positions() {
            return this.node.theme.positions;
        },

        visiblePositions() {
            return this.customPositions.filter((name) => !(name === 'main' && this.theme.content_hide));
        }

    },

    created() {
        _.forEach(this.customPositions, (name) => {
            this.positions[name] = _.merge({}, this.positionDefaults, this.positions[name]);
        });
    }

};

export default NodeTheme;

window.Site.components['node-theme'] = NodeTheme;

</script>

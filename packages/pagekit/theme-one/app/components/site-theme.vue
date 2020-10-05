<template>
    <div>
        <div class="uk-margin uk-flex uk-flex-middle uk-flex-between uk-flex-wrap">
            <div>
                <h2 class="uk-h3 uk-margin-remove">
                    {{ 'Theme' | trans }}
                </h2>
            </div>
            <div class="uk-margin-small">
                <button class="uk-button uk-button-primary" type="submit">
                    {{ 'Save' | trans }}
                </button>
            </div>
        </div>

        <div class="uk-form uk-form-horizontal">
            <div class="uk-margin-small">
                <label class="uk-form-label">{{ 'Header' | trans }}</label>
                <div class="uk-form-controls uk-form-controls-text">
                    <div class="uk-text-small uk-margin-small">
                        <label><input v-model="config.header.fullwidth" type="checkbox" class="uk-checkbox"> {{ 'Expand header container width.' | trans }}</label>
                    </div>
                    <div v-if="config.header.fullwidth" class="uk-text-small uk-margin-small">
                        <label><input v-model="config.header.logo_padding_remove" type="checkbox" class="uk-checkbox"> {{ 'Remove logo padding.' | trans }}</label>
                    </div>
                </div>
            </div>

            <div class="uk-margin-small">
                <label class="uk-form-label">{{ 'Menu' | trans }}</label>
                <div class="uk-form-controls">
                    <select v-model="config.header.layout" class="uk-select uk-form-width-large">
                        <option value="horizontal-left">
                            Horizontal left
                        </option>
                        <option value="horizontal-center">
                            Horizontal center
                        </option>
                        <option value="horizontal-right">
                            Horizontal right
                        </option>
                    </select>
                    <div class="uk-text-meta uk-form-width-large">
                        {{ 'Select the menu location in the header.' | trans }}
                    </div>
                </div>
            </div>

            <div class="uk-margin-small">
                <label class="uk-form-label">{{ 'Navigation' | trans }}</label>
                <div class="uk-form-controls">
                    <select v-model="config.navbar.sticky" class="uk-select uk-form-width-large">
                        <option value="0">
                            Static
                        </option>
                        <option value="1">
                            Sticky
                        </option>
                        <option value="2">
                            Sticky on scroll up
                        </option>
                    </select>
                    <div class="uk-text-meta uk-form-width-large">
                        {{ 'Select the navigation bar position mode.' }}
                    </div>
                </div>
            </div>

            <div class="uk-margin-small">
                <label class="uk-form-label">{{ 'Dropdown' | trans }}</label>
                <div class="uk-form-controls uk-form-controls-text">
                    <div class="uk-margin-small">
                        <select v-model="config.navbar.dropbar_align" class="uk-select uk-form-width-large">
                            <option value="left">
                                Left
                            </option>
                            <option value="center">
                                Center
                            </option>
                            <option value="right">
                                Right
                            </option>
                        </select>
                    </div>
                    <div class="uk-text-small uk-margin-small">
                        <label><input v-model="config.navbar.dropdown_boundary" type="checkbox" class="uk-checkbox"> {{ 'Align to navbar instead of the menu item' | trans }}</label>
                    </div>
                </div>
            </div>

            <div class="uk-margin-small">
                <label class="uk-form-label">{{ 'Dropbar' | trans }}</label>
                <div class="uk-form-controls uk-form-controls-text">
                    <div class="uk-margin-small">
                        <select v-model="config.navbar.dropbar" class="uk-select uk-form-width-large">
                            <option value="">
                                None
                            </option>
                            <option value="slide">
                                Slide
                            </option>
                            <option value="push">
                                Push
                            </option>
                        </select>
                    </div>
                    <div class="uk-text-meta uk-form-width-large">
                        {{ 'Extends to the full width of the navbar and displays the dropdown without its default styling.' | trans }}
                    </div>
                </div>
            </div>
            <div class="uk-margin-small">
                <label class="uk-form-label">{{ 'Off-canvas' | trans }}</label>
                <div class="uk-form-controls uk-form-controls-text">
                    <div class="uk-margin-small">
                        <select v-model="config.navbar.offcanvas.mode" class="uk-select uk-form-width-large">
                            <option value="none">
                                None
                            </option>
                            <option value="slide">
                                Slide
                            </option>
                            <option value="reveal">
                                Reveal
                            </option>
                            <option value="push">
                                Push
                            </option>
                        </select>
                    </div>
                    <div class="uk-text-small uk-margin-small">
                        <label><input v-model="config.navbar.offcanvas.overlay" type="checkbox" class="uk-checkbox"> {{ 'Add an overlay, blanking out the page.' | trans }}</label>
                    </div>
                    <div class="uk-text-small uk-margin-small">
                        <label><input v-model="config.navbar.offcanvas.flip" type="checkbox" class="uk-checkbox"> {{ 'Slides off-canvas from the right.' | trans }}</label>
                    </div>
                    <div class="uk-text-meta uk-form-width-large">
                        {{ 'Choose between different animation modes for the off-canvas\' entrance.' | trans }}
                    </div>
                </div>
            </div>

            <div class="uk-margin-small">
                <label class="uk-form-label">{{ 'Logo Contrast' | trans }}</label>
                <div class="uk-form-controls">
                    <input-image v-model="config.logo_contrast" class-name="uk-form-width-large" />
                    <div class="uk-text-meta">
                        {{ 'Select an alternative logo which looks great on images.' | trans }}
                    </div>
                </div>
            </div>

            <div class="uk-margin-small">
                <label class="uk-form-label">{{ 'Logo Off-canvas' | trans }}</label>
                <div class="uk-form-controls">
                    <input-image v-model="config.logo_offcanvas" class-name="uk-form-width-large" />
                    <div class="uk-text-meta">
                        {{ 'Select an optional logo for the off-canvas menu.' | trans }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

const SiteTheme = {

    section: {
        label: 'Theme',
        icon: 'brush',
        priority: 15
    },

    data() {
        return _.extend({ config: {} }, window.$theme);
    },

    events: {

        'settings-save': function () {
            this.$http.post('admin/system/settings/config', { name: this.name, config: this.config }).catch(function (res) {
                this.$notify(res.data, 'danger');
            });
        }

    }

};

export default SiteTheme;

window.Site.components['site-theme'] = SiteTheme;

</script>

<template>
    <div class="uk-grid pk-grid-large pk-width-sidebar-large">
        <div class="pk-width-content uk-form-horizontal">
            <div class="uk-margin">
                <label for="form-title" class="uk-form-label">{{ 'Title' | trans }}</label>
                <div class="uk-form-controls">
                    <v-input id="form-title" v-model="widget.title" type="text" name="title" placeholder="Enter Title" view="class: uk-input uk-form-width-large" rules="required" message="Title cannot be blank." />
                </div>
            </div>
        </div>
        <div class="pk-width-sidebar">
            <component :is="'template-settings'" v-model="widget" :config="config" />
        </div>
    </div>
</template>

<script>

import WidgetMixin from '../mixins/widget-mixin';

export default {

    mixins: [WidgetMixin],

    section: { label: 'Settings' }
};

</script>

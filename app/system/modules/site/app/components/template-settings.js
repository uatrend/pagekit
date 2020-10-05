import NodeMixin from '../mixins/node-mixin';
import Settings from '../templates/settings.html';

export default {
    name: 'template-settings',

    props: {
        titleRequired: {
            type: Boolean,
            default: true
        }
    },

    mixins: [NodeMixin],

    template: Settings
};

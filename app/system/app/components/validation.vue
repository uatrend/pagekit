<template>
    <ValidationObserver slim>
        <ValidationProvider v-slot="{ errors }" :name="$attrs.name" :rules="rules" slim>
            <div v-if="!get('type')" :class="get('containerClass') ? get('containerClass') : 'uk-form-controls'">
                <input v-if="!get('tag')" :id="$attrs.id" v-model="innerValue" :name="$attrs.name" :type="$attrs.type" :placeholder="$attrs.placeholder | trans" :class="[get('class'), {'uk-form-danger': get('danger') && errors[0]}]" :autocomplete="$attrs.autocomplete">
                <textarea v-else-if="get('tag') === 'textarea'" :id="$attrs.id" v-model="innerValue" :name="$attrs.name" :type="$attrs.type" :placeholder="$attrs.placeholder | trans" :rows="$attrs.rows" :class="[get('class'), {'uk-form-danger': get('danger') && errors[0]}]" />
                <div v-if="errors[0]" class="uk-text-meta uk-text-danger">
                    {{ $attrs.message | trans }}
                </div>
            </div>
            <div v-else-if="get('type') === 'icon'" :class="get('containerClass')">
                <div class="uk-inline">
                    <a v-if="get('iconTag') === 'a'" tabindex="-1" :class="['uk-form-icon', {'uk-form-icon-flip': get('iconDir') === 'right'}]" href="#" :style="get('iconLabel') ? {width: 'auto', marginRight: '10px'} : ''" @click.prevent="get('iconClick')">
                        <span v-if="get('iconDir') === 'right' && get('iconLabel')" class="uk-margin-small-right uk-text-small">{{ get('iconLabel') | trans }}</span>
                        <span :uk-icon="get('icon')" />
                    </a>
                    <span v-else :class="['uk-form-icon', {'uk-form-icon-flip': get('dir') === 'right'}]" href="#" :uk-icon="get('icon')" />
                    <input :id="$attrs.id" v-model="innerValue" :name="$attrs.name" :type="$attrs.type" :placeholder="$attrs.placeholder | trans" :class="[get('class'), {'uk-form-danger': get('danger') && errors[0]}]" :autocomplete="$attrs.autocomplete">
                </div>
                <div v-if="errors[0]" class="uk-text-meta uk-text-danger">
                    {{ $attrs.message | trans }}
                </div>
            </div>
        </ValidationProvider>
    </ValidationObserver>
</template>

<script>

import { ValidationObserver, ValidationProvider, extend } from 'vee-validate';
import { required, email, regex } from 'vee-validate/dist/rules';

extend('required', required);
extend('email', email);
extend('regex', regex);

const VInput = {
    inheritAttrs: false,
    name: 'v-input',
    props: {
        rules: {
            type: [Object, String],
            default: ''
        },
        view: {
            type: [Object, String],
            default: () => ({})
        },
        value: { type: null }
    },
    data: () => ({
        form: {},
        innerValue: ''
    }),
    watch: {
        innerValue(newVal) {
            this.$emit('input', newVal);
        },

        value(newVal) {
            this.innerValue = newVal;
        }
    },
    created() {
        if (typeof this.view === 'string') {
            this.form = this.toObject(this.view);
        }
        if (typeof this.view === 'object') {
            this.form = this.view;
        }
        if (this.value) {
            this.innerValue = this.value;
        }
    },
    methods: {
        toObject(str) {
            return str.split(',')
                .map((keyVal) => keyVal.split(':').map((_) => _.trim()))
                .reduce((accumulator, currentValue) => {
                    accumulator[currentValue[0]] = currentValue[1];
                    return accumulator;
                }, {});
        },
        get(prop) {
            if (this.form[prop] && typeof this.form[prop] === 'function') {
                return this.form[prop].call();
            }
            return this.form[prop];
        }

    },
    components: {
        ValidationObserver,
        ValidationProvider
    }

};

export default VInput;
export { ValidationObserver, VInput };

</script>

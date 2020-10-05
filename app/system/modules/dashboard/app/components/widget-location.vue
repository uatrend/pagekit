<template>
    <div>
        <div class="uk-position-top-right uk-position-small uk-position-z-index">
            <ul class="uk-iconnav uk-invisible-hover">
                <li v-show="!editing" class="uk-light">
                    <a uk-icon="file-edit" class="uk-link-muted" :title="'Edit' | trans" uk-tooltip="delay: 500" @click.prevent="$parent.edit" />
                </li>
                <li v-show="!editing" class="uk-light">
                    <a uk-icon="more-vertical" class="uk-link-muted uk-sortable-handle" :title="'Drag' | trans" uk-tooltip="delay: 500" />
                </li>
                <li v-show="editing">
                    <a v-confirm="'Delete widget?'" uk-icon="trash" :title="'Delete' | trans" uk-tooltip="delay: 500" @click.prevent="$parent.remove" />
                </li>
                <li v-show="editing">
                    <a uk-icon="check" :title="'Close' | trans" uk-tooltip="delay: 500" @click.prevent="$parent.save" />
                </li>
            </ul>
        </div>

        <div v-show="editing" class="uk-card-header pk-panel-teaser">
            <form class="uk-form-stacked" @submit.prevent>
                <div class="uk-margin">
                    <label for="form-city" class="uk-form-label">{{ 'Location' | trans }}</label>
                    <div class="uk-form-controls">
                        <div ref="autocomplete" class="uk-autocomplete">
                            <input id="form-city" ref="location" class="uk-input" type="text" :placeholder="location" autocomplete="off" @blur="clear">
                        </div>
                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label">{{ 'Unit' | trans }}</label>

                    <div class="uk-form-controls uk-form-controls-text">
                        <p class="uk-margin-small">
                            <label><input v-model="widget.units" class="uk-radio" type="radio" value="metric"> {{ 'Metric' | trans }}</label>
                        </p>
                        <p class="uk-margin-small">
                            <label><input v-model="widget.units" class="uk-radio" type="radio" value="imperial"> {{ 'Imperial' | trans }}</label>
                        </p>
                    </div>
                </div>

                <div class="uk-margin-top">
                    <label for="google-time-api" class="uk-form-label">{{ 'Google Time Zone API Key' | trans }}</label>
                    <div class="uk-form-controls uk-form-controls-text">
                        <p class="uk-margin-small" /><div class="uk-inline uk-width-expand">
                            <div v-if="gApiResult === 'process'" class="uk-form-icon uk-form-icon-flip" uk-spinner ratio="0.6" />
                            <div v-else-if="gApiResult === 'success'" class="uk-form-icon uk-form-icon-flip uk-text-success" uk-icon="check" />
                            <a v-else class="uk-form-icon uk-form-icon-flip" uk-icon="refresh" @click="gApiTest()" />
                            <input id="google-time-api" v-model="widget.GOOGLE_API_KEY" class="uk-input">
                        </div>
                        <p class="uk-text-small uk-margin-remove uk-text-truncate">
                            <span v-if="gApiResult === 'process'" class="uk-text-muted">{{ 'Checking Google API...' }}</span>
                            <span v-else-if="gApiResult !== 'success'" class="uk-text-danger">{{ gApiResult }}</span>
                        </p>
                        <p>
                            <img class="uk-float-right" width="100" height="12.5" alt="powered_by_Google" src="data:image/png;base64,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">
                        </p>
                    </div>
                </div>
            </form>
        </div>

        <div v-if="status !== 'loading'" class="uk-inline-clip pk-panel-background uk-light">
            <canvas class="" width="550" height="350" />
            <div class="uk-position-cover">
                <div class="uk-flex uk-flex-center uk-flex-column uk-height-1-1">
                    <h1 v-if="time" class="uk-margin-remove uk-text-center pk-text-xlarge">
                        {{ time | date(format) }}
                    </h1>
                    <h2 v-if="time" class="uk-h4 uk-text-center uk-margin-remove">
                        {{ time | date('longDate') }}
                    </h2>
                </div>
                <div class="uk-position-bottom uk-padding-small uk-flex uk-flex-middle uk-flex-between uk-flex-wrap">
                    <h3 v-if="widget.city" class="uk-h4 uk-margin-remove">
                        {{ widget.city }}
                    </h3>
                    <h3 v-if="status=='done'" class="uk-h4 uk-flex uk-flex-middle uk-margin-remove">
                        {{ temperature }} <img class="uk-margin-small-left" :src="icon" width="25" height="25" alt="Weather">
                    </h3>
                </div>
            </div>
        </div>

        <div v-else class="uk-text-center">
            <v-loader />
        </div>
    </div>
</template>

<script>

import { on, append } from 'uikit-util';
import WidgetMixin from '../mixins/widget-mixin';

export default {

    name: 'Location',

    mixins: [WidgetMixin],

    type: {

        id: 'location',
        label: 'Location',
        disableToolbar: true,
        description: () => {},
        defaults: { units: 'metric' }

    },

    data() {
        return {
            status: '',
            timezone: {},
            icon: '',
            temp: 0,
            time: 0,
            format: 'shortTime',
            gApiResult: ''
        };
    },

    computed: {

        location() {
            return this.widget.city ? `${this.widget.city}, ${this.widget.country}` : '';
        },

        temperature() {
            if (this.widget.units !== 'imperial') {
                return `${Math.round(this.temp)} °C`;
            }

            return `${Math.round(this.temp * (9 / 5) + 32)} °F`;
        }

    },

    watch: {

        'widget.uid': {

            handler(uid) {
                if (uid === undefined) {
                    this.$set(this.widget, 'uid', '');
                    this.$parent.save();
                    this.$parent.edit(true);
                }

                if (!uid) return;

                this.load();
            },
            immediate: true

        },

        timezone: 'updateClock'

    },

    mounted() {
        const vm = this;
        let list;

        const Autocompete = UIkit.autocomplete(this.$refs.autocomplete, {
            source(release) {
                vm.$http.get('admin/dashboard/weather', { params: { action: 'find', data: { q: this.input.value, type: 'like' } } }).then(
                    (res) => {
                        const { data } = res;
                        list = data.list || [];
                        release(list);
                    },
                    () => {
                        release([]);
                    }
                );
            },

            template: '<ul class="uk-nav uk-dropdown-nav uk-autocomplete-results">{{~items}}<li data-id="{{$item.id}}"><a>{{$item.name}} <span>, {{$item.sys.country}}</span></a></li>{{/items}}{{^items.length}}<li class="uk-skip"><a class="uk-text-muted">{{msgNoResults}}</a></li>{{/end}}</ul>',

            renderer(data) {
                append(this.dropdown, this.template({ items: data || [], msgNoResults: vm.$trans('No location found.') }));
                this.show();
            }
        });

        on(Autocompete.$el, 'select', (e, el, data) => {
            if (!data || !data.id) {
                return;
            }

            const location = _.find(list, { id: parseInt(data.id) });

            Vue.nextTick(() => {
                vm.$refs.location.blur();
            });

            if (!location) {
                return;
            }

            vm.$set(vm.widget, 'uid', location.id);
            vm.$set(vm.widget, 'city', location.name);
            vm.$set(vm.widget, 'country', location.sys.country);
            vm.$set(vm.widget, 'coords', location.coord);
        });

        this.timer = setInterval(this.updateClock(), 60 * 1000);
    },

    destroyed() {
        clearInterval(this.timer);
    },

    methods: {

        load() {
            if (!this.widget.uid) {
                return;
            }

            this.$http.get('admin/dashboard/weather', { params: { action: 'weather', data: { id: this.widget.uid, units: 'metric' } }, cache: 60 }).then(
                function (res) {
                    const { data } = res;
                    if (data.cod === 200) {
                        this.init(data);
                    } else {
                        this.$set(this, 'status', 'error');
                    }
                },
                function () {
                    this.$set(this, 'status', 'error');
                }
            );

            this.$http.get('https://maps.googleapis.com/maps/api/timezone/json', { params: { location: `${this.widget.coords.lat},${this.widget.coords.lon}`, timestamp: Math.floor(Date.now() / 1000), key: this.widget.GOOGLE_API_KEY }, cache: { key: `timezone-${this.widget.coords.lat}${this.widget.coords.lon}`, lifetime: 1440 } }).then(function (res) {
                const { data } = res;
                data.offset = data.rawOffset + data.dstOffset;

                this.$set(this, 'timezone', data);
            }, function () {
                this.$set(this, 'status', 'error');
            });
        },

        gApiTest() {
            this.gApiResult = 'process';
            this.$http.get('https://maps.googleapis.com/maps/api/timezone/json', { params: { location: `${this.widget.coords.lat},${this.widget.coords.lon}`, timestamp: Math.floor(Date.now() / 1000), key: this.widget.GOOGLE_API_KEY } }).then(function (res) {
                const { data } = res;
                data.offset = data.rawOffset + data.dstOffset;
                setTimeout(() => {
                    this.gApiResult = data.offset ? 'success' : data.errorMessage;
                    if (this.gApiResult !== 'success') console.error(data);
                }, 300);
            });
        },

        init(data) {
            this.$set(this, 'temp', data.main.temp);
            this.$set(this, 'icon', this.getIconUrl(data.weather[0].icon));
            this.$set(this, 'status', 'done');
        },

        getIconUrl(icon) {
            const icons = {

                '01d': 'sun.svg',
                '01n': 'moon.svg',
                '02d': 'cloud-sun.svg',
                '02n': 'cloud-moon.svg',
                '03d': 'cloud.svg',
                '03n': 'cloud.svg',
                '04d': 'cloud.svg',
                '04n': 'cloud.svg',
                '09d': 'drizzle-sun.svg',
                '09n': 'drizzle-moon.svg',
                '10d': 'rain-sun.svg',
                '10n': 'rain-moon.svg',
                '11d': 'lightning.svg',
                '11n': 'lightning.svg',
                '13d': 'snow.svg',
                '13n': 'snow.svg',
                '50d': 'fog.svg',
                '50n': 'fog.svg'

            };

            return this.$url('app/system/modules/dashboard/assets/images/weather-{icon}', { icon: icons[icon] });
        },

        updateClock() {
            const offset = this.timezone.offset || 0;
            const date = new Date();
            const time = offset ? new Date(date.getTime() + date.getTimezoneOffset() * 60000 + offset * 1000) : new Date();

            this.$set(this, 'time', time);

            return this.updateClock;
        },

        clear() {
            this.$refs.location.value = '';
        }

    }

};

</script>

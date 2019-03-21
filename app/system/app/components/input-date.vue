<template>
    <div class="uk-grid-small uk-child-width-1-2@l" uk-grid>
        <div>
            <div ref="datepicker" class="uk-inline uk-width-1-1">
                <span class="uk-form-icon" uk-icon="calendar" />
                <input v-if="isRequired" v-model.lazy="date" v-validate="'required'" class="uk-input" type="text">
                <input v-else v-model.lazy="date" class="uk-input" type="text">
            </div>
        </div>
        <div>
            <div ref="timepicker" class="uk-inline uk-width-1-1">
                <span class="uk-form-icon" uk-icon="clock" />
                <input v-if="isRequired" v-model="time" v-validate="'required'" class="uk-input" type="text">
                <input v-else v-model="time" class="uk-input" type="text">
            </div>
        </div>
    </div>
</template>

<script>

const { $ } = UIkit.util;

module.exports = {

    props: ['value', 'required'],

    inject: ['$validator'],

    data() {
        return {
            datetime: this.value,
            options: {
                datepicker: {
                    allowInput: true,
                    locale: {
                        amPM: window.$locale.DATETIME_FORMATS.AMPMS,
                        firstDayOfWeek: 1,
                        months: {
                            longhand: window.$locale.DATETIME_FORMATS.STANDALONEMONTH,
                            shorthand: window.$locale.DATETIME_FORMATS.SHORTMONTH,
                        },
                        weekdays: {
                            longhand: window.$locale.DATETIME_FORMATS.DAY,
                            shorthand: window.$locale.DATETIME_FORMATS.SHORTDAY,
                        },
                    },
                },
                timepicker: {
                    allowInput: true,
                    enableTime: true,
                    minuteIncrement: 1,
                    noCalendar: true,
                },
            },
        };
    },

    created() {},

    mounted() {
        const format = this.dateFormat;

        this.$nextTick(() => {
            flatpickr($('input', this.$refs.datepicker), _.extend(this.options.datepicker, {
                altInput: true,
                altFormat: this.dateFormat,
            }));
            flatpickr($('input', this.$refs.timepicker), _.extend(this.options.timepicker, {
                time_24hr: (this.clockFormat == '24h'),
                dateFormat: this.timeFormat,
            }));
        });
    },

    computed: {

        getlocale: function locale() {
            return {
                months: window.$locale.DATETIME_FORMATS.STANDALONEMONTH,
                weekdays: window.$locale.DATETIME_FORMATS.SHORTDAY,
            };
        },

        dateFormat() {
            return window.$locale.DATETIME_FORMATS.shortDate
                .replace(/\bdd\b/i, 'D').toLowerCase()
                .replace(/\bmm\b/i, 'M').toLowerCase()
                .replace(/\byy\b/i, 'Y')
                .toLowerCase()
                .split('')
                .join('');
        },

        timeFormat() {
            return window.$locale.DATETIME_FORMATS.shortTime
                .replace(/\bmm\b/i, 'i')
                .replace(/\bhh\b/i, 'H')
                .replace(/\bh\b/i, (this.clockFormat == '24h') ? 'H' : 'h')
                .replace(/\ba\b/i, 'K');
        },

        clockFormat() {
            return window.$locale.DATETIME_FORMATS.shortTime.match(/a/) ? '12h' : '24h';
        },

        date: {

            get() {
                return this.datetime;
            },

            set(date) {
                const prev = new Date(this.datetime);
                date = new Date(date);
                date.setHours(prev.getHours(), prev.getMinutes(), 0);
                this.$set(this, 'datetime', date.toISOString());
                this.$emit('input', this.datetime);
            },

        },

        time: {

            get() {
                return new Date(this.datetime)
                    .toLocaleString(window.$locale.localeID.replace(/\_/g, '-'), { hour: 'numeric', minute: 'numeric', hour12: this.clockFormat != '24h' });
            },

            set(time) {
                const fulltime = time;
                const date = new Date(this.datetime);
                let hour; let
                    min;
                time = time.replace(/AM|PM/, '').trim().split(':');
                hour = parseInt(time[0]) + ((fulltime.indexOf('PM') !== -1) ? 12 : 0);
                min = parseInt(time[1]);
                date.setHours(hour, min);
                this.$set(this, 'datetime', date.toISOString());
                this.$emit('input', this.datetime);
            },

        },

        isRequired() {
            return typeof this.required !== 'undefined';
        },

    },

};

Vue.component('input-date', (resolve, reject) => {
    Vue.asset({
        css: [
            'app/assets/flatpickr/dist/flatpickr.min.css',
            'app/assets/flatpickr/dist/themes/airbnb.css',
        ],
        js: [
            'app/assets/flatpickr/dist/flatpickr.min.js',
        ],
    }).then(() => {
        resolve(module.exports);
    });
});

</script>

<template>
    <div class="uk-margin">
        <label class="uk-form-label">{{ 'Pages' | trans }}</label>
        <div class="uk-form-controls">
            <select v-model="page" class="uk-width-1-1 uk-select">
                <option v-for="p in pages" :key="p.id" :value="p.id">
                    {{ p.title }}
                </option>
            </select>
        </div>
    </div>
</template>

<script>

const LinkPage = {

    link: { label: 'Page' },

    data() {
        return {
            pages: [],
            page: ''
        };
    },

    created() {
        // TODO don't retrieve entire page objects
        this.$http.get('api/site/page').then(function (res) {
            this.pages = res.data;
            if (this.pages.length) {
                this.page = this.pages[0].id;
            }
        });
    },

    watch: {

        page(page) {
            this.$emit('input', `@page/${page}`);
        }

    }

};

export default LinkPage;

window.Links.default.components['link-page'] = LinkPage;

</script>

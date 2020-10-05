<template>
    <a title="Memory"><span class="pf-icon pf-icon-memory" /> {{ data.peak_usage_str }}</a>
</template>

<script>

export default {

    section: { priority: 40 },

    replace: false,

    props: {
        data: {
            type: Object,
            default() {
                return {};
            }
        }
    }

};

</script>

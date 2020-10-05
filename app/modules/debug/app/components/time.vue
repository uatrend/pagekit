<template>
    <a title="Time"><span class="pf-icon pf-icon-time" /> {{ data.duration_str }}</a>
</template>

<script>

export default {

    section: { priority: 30 },

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

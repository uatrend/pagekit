<template>
    <a title="Request"><span class="pf-icon pf-icon-request" /> <span class="pf-badge">200</span> @test</a>
</template>

<script>

export default {

    section: { priority: 10 },

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

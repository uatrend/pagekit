<template>
    <a title="Log">Log ({{ data.records.length }})</a>
</template>

<script>

export default {

    section: {
        priority: 70,
        panel: '#panel-log',
        template: `
                <div>
                    <h1>Logs</h1>

                    <table class="pf-table">
                        <thead>
                            <tr>
                                <th>Message</th>
                                <th>Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="record in records">
                                <td>{{ record.message }}</td>
                                <td>{{ record.level_name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>`
    },

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

<template>
    <div>
        <rhpharma-sortable-table :columns="sortTable.columns" :rows="sortTable.rows">
            <template slot="actions" slot-scope="props">
                <a :href="`${modelUrl}/${props.id}/edit`" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
            </template>
        </rhpharma-sortable-table>
    </div>
</template>

<script>
    import SortTable from './../helpers/SortTable';

    export default {
        data() {
            return {
                sortTable: {
                    columns: [
                        {id: 'name', title: 'Nom', sortable: true},
                        {id: 'start_time', title: 'Début', sortable: true},
                        {id: 'end_time', title: 'Fin', sortable: true},
                        {title: 'Actions', slot:'actions'},
                    ],
                    rows: this.rows,
                }
            }
        },

        props: ['modelUrl', 'rows'],

        methods: {

        },

        computed: {

        }
    }
</script>
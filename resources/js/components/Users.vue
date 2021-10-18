<template>
<div>
    <div class="col-md-3 row">
        <input type="search" class="form-control" v-model="search" placeholder="Recherche un utilisateur" spellcheck="false">
    </div>
    <rhpharma-sortable-table :columns="sortTable.columns" :rows="sortTable.rows" :search="search">
        <template slot="is_active" slot-scope="props">
            <td>{{ isActive(props.row) }}</td>
        </template>
        <template slot="options" slot-scope="props">
            <td><a :href="`${modelUrl}/${props.id}`" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
        </template>
    </rhpharma-sortable-table>
</div>
</template>

<script>
    import SortTable from './../helpers/SortTable';

    export default {
        data() {
            return {
                search: '',
                sortTable: {
                    columns: [
                        {id: 'lastname', title: 'Nom', sortable: true},
                        {id: 'firstname', title: 'Prénom', sortable: true},
                        {id: 'branch.name', title: 'Branche', sortable: true},
                        {id: 'workdays_per_week', title: 'Jours de travail par semaine', sortable: false},
                        {id: 'seniority', title: 'Ancienneté', sortable: false},
                        {id: 'is_active', title: 'Actif', sortable:true, slot:'is_active'},
                        {title: 'Actions', slot:'options'},
                    ],
                    rows: this.rows,
                }
            }
        },

        props: ['modelUrl', 'rows'],

        methods: {

            isActive(bool) {
                return bool ? 'Actif' : 'Inactif';
            }
        },

        computed: {

        }
    }
</script>
<template>
    <div>
        <button class="btn btn-default pull-right" @click="show()" v-show="!showForm"><span class="fa fa-plus"></span> Ajouter une branche</button>
        <form action="#" @submit.prevent="edit ? updateBranch(form.id) : createBranch()" v-show="showForm" @keydown="form.errors.clear($event.target.name)">
            <div class="form-group" v-bind:class="{ 'has-error' : form.errors.any() }">
                <div class="input-group">
                    <input type="text" v-model="form.name" ref='name' class="form-control" autofocus>
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary"
                                :disabled="form.errors.any()" v-text="edit ? 'Éditer' : 'Créer'">
                        </button>
                        <button type="button" @click="showForm = false" class="btn btn-default">Annuler</button>
                    </span>
                </div>
                <div class="help-block" v-show="form.errors.has('name')" v-text="form.errors.get('name')"></div>
            </div>
        </form>
        <rhpharma-sortable-table :columns="sortTable.columns" :rows="sortTable.rows">
            <template slot="options" scope="props">
                <button @click="editBranch(props.id)" class="btn btn-primary btn-xs">Edit</button>
                <button @click="deleteBranch(props.id)" class="btn btn-danger btn-xs">Delete</button>
            </template>
        </rhpharma-sortable-table>
    </div>
</template>

<script>
    import Form from '../helpers/Form';

    export default {
        props: {
            rows: {
                required:true
            }

        },

        data() {
            return {
                edit:false,
                list:[],
                form: new Form({
                    id: '',
                    name: ''
                }),
                showForm:false,
                sortTable: {
                    columns: [
                        {id: 'name', title: 'Nom de la branche', sortable: true},
                        {id: 'users_count', title: '# Utilisateurs'},
                        {title: 'Actions', slot:'options'},
                    ],
                    rows: this.rows,
                }
            };
        },

        template: '#branches',

        methods: {
            refresh () {
                this.form.get('api/branches')
                    .then(data => {
                        this.sortTable.rows = data;
                    })
                    .catch(error => console.log(error));
            },

            createBranch() {
                this.form.post('api/branches/store')
                    .then(() => {
                        this.showForm = false;
                        this.refresh();
                    })
                    .catch(error => console.log(error));
            },

            editBranch(id) {
                this.form.get('api/branches/'+id)
                    .then((data) => {
                        this.form.id = data.id;
                        this.form.name = data.name;
                        this.show(true);
                    });
                Vue.nextTick(() => this.$refs.name.focus());
            },

            updateBranch(id) {
                this.form.patch('api/branches/' + id, this.branch)
                    .then(() => {
                        this.edit = false;
                        this.showForm = false;
                        this.refresh();
                    });
            },

            deleteBranch(id) {
                alert('Deleting id : ' + id);
            },

            show(edit = false) {
                this.showForm = true;

                this.edit = edit;

                if(!edit) this.form.reset();

                Vue.nextTick(() => this.$refs.name.focus());
            }
        }
    }
</script>
<template>
    <div>
        <button class="btn btn-default pull-right" @click="show()" v-show="!showForm">
            <span class="fa fa-plus"></span> Ajouter une journée fériée
        </button>

        <form action="#" @submit.prevent="edit ? updateHoliday(form.id) : createHoliday()" v-show="showForm" @keydown="form.errors.clear($event.target.name)">
            <div class="row"> 
                <div class="form-group col-md-6">
                    <label>Description:</label>
                    <input type="text" v-model="form.description" ref='description' name="description" class="form-control" autofocus>
                </div>
                <div class="form-group col-md-2">
                    <label>Date fériée:</label>
                    <div @click="flatpickrClicked($event.target.name)">
                        <Flatpickr :options="fpOptions" v-model="form.date" name="date" class="form-control"></Flatpickr>
                    </div>
                </div>
            </div>
            <div :class="{ 'has-error' : form.errors.any() }" v-show="form.errors.any()">
                <div class="help-block" v-show="form.errors.any()" v-text="form.errors.get()"></div>
            </div>
            <div class="row">
                <div class="form-group col-md-3">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary"
                                :disabled="form.errors.any()" v-text="!edit ? 'Créer' : 'Modifier'">
                        </button>
                        <button type="button" class="btn btn-default" @click="showForm = false">Annuler</button>
                    </div>
                </div>
            </div>
            <hr>
        </form>
        <rhpharma-sortable-table :columns="sortTable.columns" :rows="sortTable.rows">
            <template slot="options" scope="props">
                <button @click="editHoliday(props.id)" class="btn btn-primary btn-xs">Edit</button>
                <button @click="deleteHoliday(props.id)" class="btn btn-danger btn-xs">Delete</button>
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
                    description: '',
                    date: ''
                }),
                showForm:false,
                filterAll:0,
                sortTable: {
                    columns: [
                        {id: 'date', title: 'Date', sortable: true},
                        {id: 'description', title: 'Journée fériée', sortable: true},
                        {title: 'Actions', slot:'options'},
                    ],
                    rows: this.rows
                },
                fpOptions: {}
            };
        },

        methods: {
            createHoliday() {
                this.form.post('api/holidays/store')
                    .then(() => {
                        this.showForm = false;
                        this.refresh();
                    })
                    .catch(error => console.log(error));
            },

            show(edit = false) {
                this.showForm = true;

                this.edit = edit;

                if(!edit) this.form.reset();

                Vue.nextTick(() => this.$refs.description.focus());
            },

            editHoliday(id) {
                this.form.get('api/holidays/'+id)
                    .then((data) => {
                        this.form.id = data.id;
                        this.form.description = data.description;
                        this.form.date = data.date;
                        this.show(true);
                    });
                Vue.nextTick(() => this.$refs.description.focus());
            },

            updateHoliday(id) {
                this.form.patch('api/holidays/' + id)
                    .then(() => {
                        this.edit = false;
                        this.showForm = false;
                        this.refresh();
                    });
            },

            deleteHoliday() {
                alert('Todo');
            },

            refresh() {
                this.form.get('api/holidays')
                    .then(data => {
                        this.sortTable.rows = data;
                    })
                    .catch(error => console.log(error));
            },

            flatpickrClicked(field) {
                return this.form.errors.clear(field);
            }
        }
    }
</script>

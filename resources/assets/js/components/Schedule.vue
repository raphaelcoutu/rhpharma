<template>
    <div>
        <button class="btn btn-default pull-right"
                @click="showForm = true"
                v-show="!showForm">
            <span class="fa fa-plus"></span> Ajouter un horaire</button>
        <form action="#" @submit.prevent="createSchedule()" v-show="showForm" @keydown="form.errors.clear($event.target.name)">
            <h2>Création d'un horaire</h2>

            <div class="row">
                <div class="form-group col-md-6">
                    <label>Nom:</label>
                    <input type="text" v-model="form.name" ref='name' class="form-control" autofocus>
                </div>
                <div class="form-group col-md-2">
                    <label>Date contraintes:</label>
                    <div @click="flatpickrClicked($event.target.name)">
                        <Flatpickr :options="fpOptions" v-model="form.constraint_limit_date" name="constraint_limit_date" class="form-control" />
                    </div>
                </div>
                <div class="form-group col-md-2">
                    <label>Date de début:</label>
                    <div @click="flatpickrClicked($event.target.name)">
                        <Flatpickr :options="fpOptions" v-model="form.start_date" name="start_date" class="form-control"/>
                    </div>
                </div>
                <div class="form-group col-md-2">
                    <label>Date de fin:</label>
                    <div @click="flatpickrClicked($event.target.name)">
                        <Flatpickr :options="fpOptions" v-model="form.end_date" name="end_date" class="form-control" />
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
                                :disabled="form.errors.any()">
                            Créer
                        </button>
                        <button type="button" class="btn btn-default" @click="showForm = false">Annuler</button>
                    </div>
                </div>
            </div>
            <hr>
        </form>
        <rhpharma-sortable-table :columns="sortTable.columns" :rows="sortTable.rows">
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
                showForm: false,
                form: new Form({
                    name: '',
                    constraint_limit_date: '',
                    start_date: '',
                    end_date: ''
                }),
                sortTable: {
                        columns: [
                            {id: 'name', title: 'Nom de la branche', sortable: true},
                            {id: 'constraint_limit_date', title: 'Date contraintes', sortable: true},
                            {id: 'start_date', title: 'Début', sortable: true},
                            {id: 'end_date', title: 'Fin', sortable: true},
                            {title: 'Actions', slot:'options'},
                        ],
                        rows: this.rows,
                    },
                dateStr: '',
                fpOptions: {}
            }
        },

        methods: {
            flatpickrClicked(field) {
                return this.form.errors.clear(field);
            },

            refresh () {
                this.form.get('api/schedules')
                    .then(data => {
                        this.sortTable.rows = data;
                    })
                    .catch(error => console.log(error));
            },

            createSchedule() {
                this.form.post('api/schedules/store')
                    .then((data) => {
                        this.showForm = false;
                        this.refresh();
                        console.log(data);
                    })
                    .catch(error => console.log(error))
            },

        }

    }
</script>
<template>
    <div>
        <h2>Contraintes à dates fixes</h2>
        <table class="table table-responsive table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Debut</th>
                <th>Fin</th>
                <th>Importance</th>
                <th width="40%">Autres informations</th>
                <th>Options</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="constraint in constraints" :class="{'success' : form.id == constraint.id}">
                    <td>{{ constraint.id }}</td>
                    <td><strong>{{ constraint.constraint_type.name }}</strong><br>
                        <i>{{ constraint.constraint_type.description }}</i>
                    </td>
                    <td>{{ constraint.start_datetime | moment }}</td>
                    <td>{{ constraint.end_datetime | moment }}</td>
                    <td><i class="fa fa-2x" :class="trans_weight(constraint.weight)"></i></td>
                    <td>
                        <strong>Status:</strong>{{ constraint.status }}<br>
                        <strong>Répétition:</strong>{{ constraint.number_of_occurrences }}<br>
                        <strong>Commentaire:</strong>{{ constraint.comment }}<br>
                    </td>
                    <td>
                        <a class="btn btn-success" :disabled="showForm" @click="editConstraint(constraint.id)" v-scroll-to="'#formScroll'"><i class="fa fa-pencil-square-o"></i></a>
                        <a class="btn btn-danger" :disabled="showForm"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-show="showForm" id="form">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">

                <div class="row">
                <div class="col-md-6">
                    <label>Type de contrainte</label>
                        <div class="form-group">
                            <select v-model="form.constraint_type" class="form-control">
                                <option v-for="type in constraintTypes" v-bind:value="type">{{ type.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Description de la contrainte:</label>
                        <p><i>{{ form.constraint_type.description }}</i></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-inline">
                        <div class="form-group">
                            <label>Date/heure de début</label>
                            <Flatpickr :config="fpConfig" v-model="form.start_date" placeholder="AAAA-MM-JJ" class="form-control" size="12"/>
                            <Timepickr v-model="form.start_time" />
                        </div>
                    </div>
                    <div class="col-md-6 form-inline">
                        <div class="form-group">
                            <label>Date/heure de fin</label>
                            <Flatpickr :config="fpConfig" v-show="showEndDate" v-model="form.end_date" placeholder="AAAA-MM-JJ" class="form-control" size="12"/>
                            <Timepickr type="text" v-model="form.end_time"/>
                        </div>
                    </div>
                </div>
                <div class="row col-md-12">
                    <div class="form-group">
                        <label>Importance</label>
                        <label class="radio-inline"><input type="radio" name="weight" v-model="form.weight" value="0" />Faible</label>
                        <label class="radio-inline"><input type="radio" name="weight" v-model="form.weight" value="1" />Forte</label>
                    </div>
                    <div class="form-group">
                        <label>Commentaires</label>
                        <textarea cols="30" rows="2" v-model="form.comment" class="form-control"></textarea>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-9">
                        <div class="alert alert-danger" v-show="formErrors">
                            <li v-for="value in formErrors">
                                {{ value[0] }}
                            </li>
                        </div>
                    </div>
                    <div class="col-md-offset-9 col-md-3">
                        <a class="btn btn-success btn-sm" @click="saveConstraint()">Enregistrer</a>
                        <a class="btn btn-warning btn-sm" @click="cancelForm()">Annuler</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <a id="formScroll" class="btn btn-success" @click="addConstraint()" v-show="!showForm">Ajouter une contrainte à date fixe</a>
    </div>
</template>

<script>

    import moment from 'moment';
    import VueScrollTo from 'vue-scrollTo';
    import Flatpickr from 'vue-flatpickr-component';
    import 'flatpickr/dist/flatpickr.css';
    import Timepickr from './Timepickr.vue'


    Vue.use(VueScrollTo);

    export default {

        props: {
            constraintsProps: { required: true },
            constraintTypes: { required: true }
        },

        components: {
            Flatpickr,
            Timepickr
        },

        mounted() {
            this.constraints = this.constraintsProps;
        },

        data() {
            return {
                constraints: null,
                showForm: false,
                form: {
                    id: null,
                    constraint_type: {},
                    start_date: null,
                    start_time: null,
                    end_date: null,
                    end_time: null,
                    weight: null,
                    comment: null
                },
                formErrors: null,
                fpConfig: {
                    onOpen: this.onFlatpickrOpen
                }
            }
        },

        methods: {
            resetForm() {
                this.form = {
                    id: null,
                    constraint_type: {},
                    start_date: null,
                    start_time: null,
                    end_date: null,
                    end_time: null,
                    weight: null,
                    comment: null
                };
                this.formErrors = null
            },

            cancelForm() {
                this.resetForm();
                this.showForm = false;
            },

            refreshData() {
                axios.get('/api/constraints/fixed')
                    .then(res => {
                        this.constraints = res.data;
                    }).catch(err => {
                        console.error(err);
                });
            },

            getConstraintType(constraint_type_id) {
                return _.find(this.contraintTypes, {id: constraint_type_id});
            },

            addConstraint() {
                this.showForm = true;
            },

            editConstraint(id) {
                if(this.showForm) return;
                axios.get('api/constraints/'+id+'/edit')
                    .then(res => {
                        this.showForm = true;
                        let start = moment(res.data.start_datetime);
                        let end = moment(res.data.end_datetime);
                        this.form = {
                            id: res.data.id,
                            constraint_type: res.data.constraint_type,
                            start_date: start.format('YYYY-MM-DD'),
                            start_time: start.format('HH:mm'),
                            end_date: end.format('YYYY-MM-DD'),
                            end_time: end.format('HH:mm'),
                            weight: res.data.weight,
                            comment: res.data.comment
                        };
                    });
            },

            saveConstraint() {
                let start_datetime = this.form.start_date + ' ' + this.form.start_time;

                let end_datetime = null;
                if(this.form.constraint_type.is_single_day) {
                    end_datetime = this.form.start_date + ' ' + this.form.end_time;
                } else {
                    end_datetime = this.form.end_date + ' ' + this.form.end_time;
                }

                let constraint = {
                    constraint_type_id: this.form.constraint_type.id,
                    start_datetime: start_datetime,
                    end_datetime: end_datetime,
                    weight: this.form.weight,
                    comment: this.form.comment
                };

                if(this.form.id == null) {
                    axios.post('/api/constraints/store', constraint)
                        .then(res => {
                            this.showForm = false;
                            this.refreshData();
                            this.resetForm();

                        }).catch(err => {
                        this.formErrors = err.response.data.errors;
                    });
                } else {
                    axios.put('api/constraints/'+this.form.id+'/update', constraint)
                        .then(res => {
                           this.showForm = false;
                           this.refreshData();
                           this.resetForm();
                        }).catch(err => {
                            console.error(constraint);
                            this.formErrors = err.respose.data.errors;
                    });
                }
            },

            trans_weight(weight) {
                if(weight) {
                    return 'fa-battery-full'
                } else {
                    return 'fa-battery-empty'
                }
            },

            onFlatpickrOpen(selectedDates, dateStr, instance) {
                if(this.form.start_date == null || this.form.start_date == '') {
                    instance.jumpToDate(new Date())
                }
            }
        },
        computed: {
            showEndDate() {
                if(!this.form.constraint_type.is_single_day) {
                    return true;
                }

                return false;
            }
        },
        filters: {
            moment: function (date) {
                return moment(date).format('YYYY-MM-DD HH:mm');
            }
        }
    }
</script>
<template>
    <div>
        <h2>Contraintes à dates fixes</h2>
        <table class="table table-responsive table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th width="100px">Debut</th>
                    <th width="100px">Fin</th>
                    <th>Importance</th>
                    <th width="30%">Autres informations</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="constraint in filteredConstraints"
                    :class="{ success: form.id == constraint.id }"
                >
                    <td>{{ constraint.id }}</td>
                    <td>
                        <strong>{{ constraint.constraint_type.name }}</strong
                        ><br />
                        <i>{{ constraint.constraint_type.description }}</i>
                    </td>
                    <td>{{ constraint.start_datetime | moment }}</td>
                    <td>{{ constraint.end_datetime | moment }}</td>
                    <td>
                        <i
                            class="fa fa-2x"
                            :class="trans_weight(constraint.weight)"
                        ></i>
                    </td>
                    <td>
                        <strong>Status:</strong>
                        <span v-html="status(constraint.status)"></span><br />
                        <strong>Répétition:</strong
                        >{{ constraint.number_of_occurrences }}<br />
                        <strong>Commentaire:</strong>{{ constraint.comment
                        }}<br />
                    </td>
                    <td>
                        <a
                            class="btn btn-success"
                            :disabled="showForm"
                            @click="editConstraint(constraint.id)"
                            ><i class="fa fa-pencil-square-o"></i
                        ></a>
                        <a class="btn btn-danger" :disabled="showForm"
                            ><i class="fa fa-trash-o"></i
                        ></a>
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
                                <select
                                    v-model="form.constraint_type"
                                    class="form-control"
                                >
                                    <option
                                        v-for="type in constraintTypes"
                                        v-bind:value="type"
                                    >
                                        {{ type.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Description de la contrainte:</label>
                            <p>
                                <i>{{ form.constraint_type.description }}</i>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-inline">
                            <div class="form-group">
                                <label>Date/heure de début</label>
                                <input
                                    type="datetime-local"
                                    v-model="form.start_date"
                                    placeholder="AAAA-MM-JJ"
                                    class="form-control"
                                    size="12"
                                />
                            </div>
                        </div>
                        <div class="col-md-6 form-inline">
                            <div class="form-group">
                                <label>Date/heure de fin</label>
                                <input
                                    type="datetime-local"
                                    v-show="showEndDate"
                                    v-model="form.end_date"
                                    placeholder="AAAA-MM-JJ"
                                    class="form-control"
                                    size="12"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="row col-md-12">
                        <div class="form-group">
                            <label>Importance</label>
                            <label class="radio-inline"
                                ><input
                                    type="radio"
                                    name="weight"
                                    v-model="form.weight"
                                    value="0"
                                />Faible</label
                            >
                            <label class="radio-inline"
                                ><input
                                    type="radio"
                                    name="weight"
                                    v-model="form.weight"
                                    value="1"
                                />Forte</label
                            >
                        </div>
                        <div class="form-group">
                            <label>Commentaires</label>
                            <textarea
                                cols="30"
                                rows="2"
                                v-model="form.comment"
                                class="form-control"
                            ></textarea>
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
                            <a
                                class="btn btn-success btn-sm"
                                @click="saveConstraint()"
                                >Enregistrer</a
                            >
                            <a
                                class="btn btn-warning btn-sm"
                                @click="cancelForm()"
                                >Annuler</a
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a class="btn btn-success" @click="addConstraint()" v-show="!showForm"
            >Ajouter une contrainte à date fixe</a
        >
    </div>
</template>

<script>
import moment from "moment";
import FilterInInterval from "./../helpers/FilterInInterval";

export default {
    props: {
        constraintsProps: { required: true },
        constraintTypes: { required: true },
    },

    mounted() {
        this.constraints = this.constraintsProps;
        this.$root.$on("schedule", (data) => {
            this.schedule = data;
        });
    },

    data() {
        return {
            constraints: null,
            schedule: null,
            showForm: false,
            form: {
                id: null,
                constraint_type: {},
                start_datetime: null,
                end_datetime: null,
                weight: null,
                comment: null,
            },
            formErrors: null,
        };
    },

    methods: {
        resetForm() {
            this.form = {
                id: null,
                constraint_type: {},
                start_datetime: null,
                end_datetime: null,
                weight: null,
                comment: null,
            };
            this.formErrors = null;
        },

        cancelForm() {
            this.resetForm();
            this.showForm = false;
        },

        refreshData() {
            axios
                .get("/api/constraints/fixed")
                .then((res) => {
                    this.constraints = res.data;
                })
                .catch((err) => {
                    console.error(err);
                });
        },

        getConstraintType(constraint_type_id) {
            return _.find(this.contraintTypes, { id: constraint_type_id });
        },

        addConstraint() {
            this.showForm = true;
        },

        editConstraint(id) {
            if (this.showForm) return;
            axios.get("api/constraints/" + id + "/edit").then((res) => {
                this.showForm = true;
                let start = moment(res.data.start_datetime);
                let end = moment(res.data.end_datetime);
                this.form = {
                    id: res.data.id,
                    constraint_type: res.data.constraint_type,
                    start_datetime: start.format("YYYY-MM-DD HH:mm"),
                    end_datetime: end.format("YYYY-MM-DD HH:mm"),
                    weight: res.data.weight,
                    comment: res.data.comment,
                };
            });
        },

        saveConstraint() {
            let constraint = {
                constraint_type_id: this.form.constraint_type.id,
                start_datetime: this.start_datetime,
                end_datetime: this.end_datetime,
                weight: this.form.weight,
                comment: this.form.comment,
            };

            if (this.form.id == null) {
                axios
                    .post("/api/constraints/store", constraint)
                    .then((res) => {
                        this.showForm = false;
                        this.refreshData();
                        this.resetForm();
                    })
                    .catch((err) => {
                        this.formErrors = err.response.data.errors;
                    });
            } else {
                axios
                    .put(
                        "api/constraints/" + this.form.id + "/update",
                        constraint,
                    )
                    .then((res) => {
                        this.showForm = false;
                        this.refreshData();
                        this.resetForm();
                    })
                    .catch((err) => {
                        console.error(constraint);
                        this.formErrors = err.respose.data.errors;
                    });
            }
        },

        trans_weight(weight) {
            if (weight) {
                return "fa-battery-full";
            } else {
                return "fa-battery-empty";
            }
        },

        status(status_id) {
            if (status_id == 0) {
                return "<i class='fa fa-clock-o'></i> En attente";
            } else if (status_id == 1) {
                return "<i class='fa fa-check-circle-o text-success'></i> Approuvé";
            } else {
                return "<i class='fa fa-times-circle-o text-danger'></i> Refusé";
            }
        },
    },
    computed: {
        filteredConstraints() {
            if (this.schedule) {
                let schedule_start_date = new Date(this.schedule.start_date);
                let schedule_end_date = new Date(this.schedule.end_date);

                return this.constraints.filter(
                    FilterInInterval(schedule_start_date, schedule_end_date),
                );
            }

            return this.constraints;
        },

        showEndDate() {
            if (!this.form.constraint_type.is_single_day) {
                return true;
            }

            return false;
        },
    },
    filters: {
        moment: function (date) {
            return moment(date).format("YYYY-MM-DD HH:mm");
        },
    },
};
</script>

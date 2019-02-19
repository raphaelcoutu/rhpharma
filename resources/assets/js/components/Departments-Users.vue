<template>
    <div>
        <table class="table table-responsive">
            <thead>
            <tr>
                <th width="40%">Nom</th>
                <th width="20%">Historique (%)</th>
                <th width="20%">Prévision long terme (%)</th>
                <th width="20%">Prévision court terme (%)</th>
            </tr>
            </thead>
            <tbody>

            <template v-if="departments.length > 0">
                <tr  v-for="department in departments">
                    <td>{{ department.name}} <button @click="removeDepartment(department.id)">X</button></td>
                    <td>{{ department.pivot.history}}</td>
                    <td>{{ department.pivot.planning_long}}</td>
                    <td>{{ department.pivot.planning_short}}</td>
                </tr>
            </template>
            <template v-else>
                <tr>
                    <td class="text-center" colspan="4">Aucun secteur attribué.</td>
                </tr>
            </template>

            </tbody>
        </table>
        <a class="btn btn-primary" v-show="!showForm" @click="addDepartment()">Ajouter un secteur</a>
        <div v-show="showForm" id="form">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">

                    <div class="row">
                        <div class="col-md-4">
                            <label>Secteur</label>
                            <div class="form-group">
                                <select v-model="form.department_id" class="form-control">
                                    <option v-for="department in remainingDepartments" :value="department.id">{{ department.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label>Historique</label>
                            <input type="text" class="form-control" v-model="form.history">

                        </div>
                        <div class="col-md-2">
                            <label>Prév. long</label>
                            <input type="text" class="form-control" v-model="form.planning_long">

                        </div>
                        <div class="col-md-2">
                            <label>Prév. court</label>
                            <input type="text" class="form-control" v-model="form.planning_short">
                        </div>
                    </div>
                    <div class="btn-group btn-group-sm pull-right">
                        <button class="btn btn-success" @click="saveDepartment()">Enregistrer</button>
                        <button class="btn btn-warning" @click="cancelForm()">Annuler</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: {
            userId: {
                required: true
            },
            departmentsProp: {
                required: true
            },
            departmentsList: {
                required: true
            }
        },

        data() {
            return {
                departments: this.departmentsProp,
                remainingDepartments: null,
                showForm: false,
                form : {
                    department_id: null,
                    history: null,
                    planning_long: null,
                    planning_short: null,
                },
                formErrors: null,
            }
        },

        methods: {
            addDepartment() {
                this.filterDepartmentsList();
                this.showForm = true;
            },

            removeDepartment(id) {
                axios.delete('/api/departmentUsers/' + this.userId, {
                    params:{department_id: id}
                }).then(res => {
                    this.refreshData();
                });
            },

            refreshData() {
                axios.get('/api/departmentUsers/' + this.userId)
                    .then(res => {
                        this.departments = res.data;
                    }).catch(err => {
                    console.error(err);
                });
            },

            saveDepartment() {
                axios.post('/api/departmentUsers/'+ this.userId +'/store', this.form)
                    .then(res => {
                        this.refreshData();
                        this.resetForm();
                        this.showForm = false;
                    }).catch(err => {
                        console.log(err);
                });
            },

            cancelForm() {
                this.resetForm();
                this.showForm = false;
            },

            resetForm() {
                this.form = {
                    department_id: null,
                    history: null,
                    planning_long: null,
                    planning_short: null,
                };
                this.formErrors = null
            },

            filterDepartmentsList() {
                let departments = this.departments;
                this.remainingDepartments = this.departmentsList.filter(function (e) {
                    return departments.filter(function (f) {
                        return e.id == f.id;
                    }).length == 0;
                });
            }
        },
    }

</script>
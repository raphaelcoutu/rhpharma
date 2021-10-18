<template>
    <div>
        <strong>Trier par:</strong>
        <select v-model="sort">
            <option value="1" selected>Date &uparrow;</option>
            <option value="2">Date &downarrow;</option>
            <option value="3">Nom famille &uparrow;</option>
            <option value="4">Nom famille &downarrow;</option>
            <option value="5">Importance &downarrow;</option>
        </select>
        <table class="table table-responsive table-striped">
            <thead>
            <tr>
                <th width="115px">Validation</th>
                <th width="115px">Utilisateur</th>
                <th width="250px">Type</th>
                <th width="155px">Début/Fin</th>
                <th>Importance</th>
                <th>Raison</th>
            </tr>
            </thead>
            <tbody v-if="constraints.length">
            <tr  v-for="(constraint, index) in constraintsSorted">
                <td>
                    <div class="btn-group btn-group-sm">
                        <a @click="validate(constraint, 1, index)" class="btn btn-success"><i class="fa fa-2x fa-thumbs-up"></i></a>
                        <a @click="validate(constraint, 2, index)" class="btn btn-danger"><i class="fa fa-2x fa-thumbs-down"></i></a>
                    </div>
                </td>
                <td><a :href="userHistoryUrl(constraint)">{{ constraint.user.firstname }} <br>{{ constraint.user.lastname }}</a></td>
                <td>{{ constraint.constrainttype.name }}</td>
                <td class="datetime">{{ constraint.start_datetime }} <br>{{ constraint.end_datetime }}</td>
                <td><i class="fa fa-2x" :class="trans_weight(constraint.weight)"></i></td>
                <td>{{ constraint.comment }}</td>
            </tr>
            </tbody>
            <tbody v-else>
                <td class="text-center" colspan="7"><i>Aucune contrainte à valider.</i></td>
            </tbody>
        </table>
    </div>
</template>

<style>
    .datetime {
        font-family: Courrier;
    }
</style>

<script>
    export default {

        props: {
            constraintsProps: {required: true},
            validatorIdProps: {required:true}
        },

        mounted() {
            this.$root.$emit('constraints-count', this.constraints.length);
        },

        data() {
            return {
                sort:1,
                constraints: this.constraintsProps
            }
        },

        computed: {
            constraintsSorted: function () {
                if(this.sort == 1) {
                    return this.constraints.sort(function (a, b) {
                        return a.start_datetime > b.start_datetime;
                    });
                } else if(this.sort == 2) {
                    return this.constraints.sort(function (a, b) {
                        return a.start_datetime < b.start_datetime;
                    });
                } else if(this.sort == 3) {
                    return this.constraints.sort(function (a, b) {
                        return a.user.lastname > b.user.lastname;
                    });
                } else if(this.sort == 4) {
                    return this.constraints.sort(function (a, b) {
                        return a.user.lastname < b.user.lastname;
                    });
                } else if(this.sort == 5) {
                    return this.constraints.sort(function (a, b) {
                        return a.weight < b.weight;
                    });
                }
            }
        },

        methods: {
            validate(constraint, status, index) {
                let data = {
                    id: constraint.id,
                    constraint_type_id: constraint.constraint_type_id,
                    start_datetime: constraint.start_datetime,
                    end_datetime: constraint.end_datetime,
                    weight: constraint.weight,
                    status: status,
                    validated_by: this.validatorIdProps
                };

                axios.put('api/constraintsValidator/'+constraint.id, data)
                    .then(res => {
                        this.constraints.splice(index, 1);
                        this.$root.$emit('constraints-count', this.constraints.length);
                    }).catch(err => {
                    console.error(constraint);
                });
            },

            userHistoryUrl(constraint) {
                return '/constraintsValidator/history?user=' + constraint.user.id;
            },

            trans_weight(weight) {
                if(weight) {
                    return 'fa-battery-full'
                } else {
                    return 'fa-battery-empty'
                }
            }
        }
    }
</script>
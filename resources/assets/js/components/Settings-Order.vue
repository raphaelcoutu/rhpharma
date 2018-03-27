<template>
    <div>
        <table class="table table-hover">
            <thead>
            <tr>
                <th></th>
                <th>Département</th>
                <th>Actif</th>
            </tr>
            </thead>
            <tbody v-if="sortedDepartments">
                <tr v-for="(item, index) in sortedDepartments">
                    <td>
                        <span class="btn-group-xs">
                        <a class="btn btn-primary" @click="moveUp(item)" :class="{ 'disabled' : item.order == 0}"><i class="fa fa-arrow-up"></i></a>
                        <a class="btn btn-primary" @click="moveDown(item)" :class="{ 'disabled' : item.order == sortedDepartments.length - 1 }"><i class="fa fa-arrow-down"></i></a>
                        </span></td>
                    <td :class="{ 'text-bold' : item.active }">
                        {{ item.name }}
                    </td>
                    <td>
                        <input type="checkbox" v-model="item.active">
                    </td>
                </tr>
            </tbody>
        </table>

        <a class="btn btn-primary" @click="save()">Sauvegarder</a>
    </div>
</template>

<script>

    export default {
        props: {
            departments: {
                required: true
            },
            order: {
                required: true
            }
        },

        mounted() {
            this.sortDepartments();
        },

        data() {
            return {
                departmentsWithOrder: []
            };
        },

        methods: {
            sortDepartments() {
                let departments = this.departments;
                let order = this.order;
                let arrResult = [];


                if(order) {
                    // Combiner les paramètres avec les départements actuels
                    arrResult = _.map(departments, function (obj) {
                        return _.assign(obj, _.find(order, {
                            id: obj.id
                        }));
                    });
                } else {
                    arrResult = departments;
                }

                // Classer les départements en ordre et réattribuer leur index
                // (au cas si un département est ajouté/retiré)
                this.departmentsWithOrder = _.each(_.sortBy(arrResult, 'order'), function (obj, i) {
                    obj.order = i;
                    if(!obj.hasOwnProperty('active')) obj.active = false;
                });
            },

            moveUp(item) {
                let itemOrder = item.order;

                // On fait rien si l'item est déjà le plus haut
                if(itemOrder == 0) return;

                let itemBefore = _.find(this.sortedDepartments, {"order": item.order - 1})

                itemBefore.order = itemOrder;
                item.order = itemOrder - 1;
            },

            moveDown(item) {
                let itemOrder = item.order;

                // On fait rien si l'item est déjà le plus bas
                if(itemOrder == this.sortedDepartments.length - 1) return;

                let itemAfter = _.find(this.sortedDepartments, {"order": item.order + 1})

                itemAfter.order = itemOrder;
                item.order = itemOrder + 1;
            },

            save() {

                let data = _.map(this.sortedDepartments, function (obj) {
                    return _.pick(obj, ['id', 'active', 'order']);
                });

                axios.patch('/api/settings/order', data)
                    .then(res => {
                        console.log(res);
                    }).catch(err => {
                        console.log(err);
                });
            }
        },

        computed: {
            sortedDepartments() {
                if(this.departmentsWithOrder.length > 0)
                    return _.sortBy(this.departmentsWithOrder, 'order');
            }
        }
    }
</script>

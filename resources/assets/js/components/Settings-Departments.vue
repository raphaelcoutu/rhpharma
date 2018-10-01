<template>
    <div>
        <table class="table table-hover">
            <thead>
            <tr>
                <th width="20%"></th>
                <th width="60%">Département</th>
                <th width="20%" class="text-center">
                    <div>Actif</div>
                    <div class="btn-group btn-group-xs">
                        <button @click="selectAll" class="btn btn-default">Tous</button>
                        <button @click="selectNone" class="btn btn-default">Aucun</button>
                    </div>
                </th>
            </tr>
            </thead>
            <tbody v-if="sortedDepartments">
                <tr v-for="(item, index) in sortedDepartments">
                    <td>
                        <span class="btn-group-xs">
                            <a class="btn btn-primary" @click="moveTop(item)" :class="{ 'disabled' : item.order == 0}"><i class="fa fa-level-up"></i></a>
                            <a class="btn btn-primary" @click="moveUp(item)" :class="{ 'disabled' : item.order == 0}"><i class="fa fa-arrow-up"></i></a>
                            <a class="btn btn-primary" @click="moveDown(item)" :class="{ 'disabled' : item.order == sortedDepartments.length - 1 }"><i class="fa fa-arrow-down"></i></a>
                            <a class="btn btn-primary" @click="moveBottom(item)" :class="{ 'disabled' : item.order == sortedDepartments.length - 1 }"><i class="fa fa-level-down"></i></a>
                        </span></td>
                    <td :class="{ 'text-bold' : item.active }">
                        {{ item.name }}
                    </td>
                    <td class="text-center">
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
        props: ['departments', 'settings'],

        mounted() {
            this.sortDepartments();
        },

        data: () => ({
            departmentsWithSettings: []
        }),

        methods: {
            sortDepartments() {
                let departments = this.departments;
                let settings = this.settings;
                let arrResult = [];


                if(settings) {
                    // Combiner les paramètres avec les départements actuels
                    arrResult = _.map(departments, function (obj) {
                        return _.assign(obj, _.find(settings, {
                            id: obj.id
                        }));
                    });
                } else {
                    arrResult = departments;
                }

                // Classer les départements en ordre et réattribuer leur index
                // (au cas si un département est ajouté/retiré)
                this.departmentsWithSettings = _.each(_.sortBy(arrResult, 'order'), function (obj, i) {
                    obj.order = i;
                    if(!obj.hasOwnProperty('active')) obj.active = false;
                });
            },

            moveTop(item) {
                let itemOrder = item.order;

                // Si on est au top, on fait rien
                if(itemOrder === 0) return;

                // Filtrer les items plus hauts et les descendre de 1
                _.forEach(_.filter(this.sortedDepartments, i => {
                    return i.order < itemOrder;
                }), i => {
                    return i.order++;
                });

                //Finalement, on met notre item en haut de liste
                item.order = 0;
            },

            moveUp(item) {
                let itemOrder = item.order;

                // On fait rien si l'item est déjà le plus haut
                if(itemOrder === 0) return;

                let itemBefore = _.find(this.sortedDepartments, {"order": item.order - 1});

                itemBefore.order = itemOrder;
                item.order = itemOrder - 1;
            },

            moveDown(item) {
                let itemOrder = item.order;

                // On fait rien si l'item est déjà le plus bas
                if(itemOrder === this.sortedDepartments.length - 1) return;

                let itemAfter = _.find(this.sortedDepartments, {"order": item.order + 1});

                itemAfter.order = itemOrder;
                item.order = itemOrder + 1;
            },

            moveBottom(item) {
                let itemOrder = item.order;

                // On fait rien si l'item est déjà le plus bas
                if(itemOrder === this.sortedDepartments.length - 1) return;

                // Filtrer les items plus hauts et les monter de 1
                _.forEach(_.filter(this.sortedDepartments, i => {
                    return i.order > itemOrder;
                }), i => {
                    return i.order--;
                });

                //Finalement, on met notre item en bas
                item.order = this.sortedDepartments.length - 1;
            },

            save() {

                let data = _.map(this.sortedDepartments, function (obj) {
                    return _.pick(obj, ['id', 'active', 'order']);
                });

                axios.patch('/api/settings/departments', data)
                    .catch(err => {
                        console.log(err);
                });
            },

            selectAll() {
                _.each(this.sortedDepartments, function (item) {
                    item.active = true;
                });
            },

            selectNone() {
                _.each(this.sortedDepartments, function (item) {
                    item.active = false;
                });
            }
        },

        computed: {
            sortedDepartments() {
                if(this.departmentsWithSettings.length > 0)
                    return _.sortBy(this.departmentsWithSettings, 'order');
            }
        }
    }
</script>

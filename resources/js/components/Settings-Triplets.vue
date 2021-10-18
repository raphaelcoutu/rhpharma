<template>
    <div>
        <table class="table table-hover">
            <thead>
            <tr>
                <th></th>
                <th>Séquence</th>
                <th>Actif</th>
            </tr>
            </thead>
            <tbody v-if="sortedTriplets">
            <tr v-for="(triplet, index) in sortedTriplets">
                <td>
                        <span class="btn-group-xs">
                        <a class="btn btn-primary" @click="moveUp(triplet)" :class="{ 'disabled' : triplet.order == 0}"><i class="fa fa-arrow-up"></i></a>
                        <a class="btn btn-primary" @click="moveDown(triplet)" :class="{ 'disabled' : triplet.order == sortedTriplets.length - 1 }"><i class="fa fa-arrow-down"></i></a>
                        </span></td>
                <td :class="{ 'text-bold' : triplet.active }" class="monospace">
                    {{ triplet.sequence }}
                </td>
                <td>
                    <input type="checkbox" v-model="triplet.active">
                </td>
            </tr>
            </tbody>
        </table>

        <a class="btn btn-primary" @click="save()">Sauvegarder</a>
    </div>
</template>

<style>
    .monospace {
        font-family:monospace;
    }
</style>

<script>

    export default {
        props: ['triplets', 'settings'],

        mounted() {
            this.sortTriplets();
        },

        data() {
            return {
                tripletsWithSettings: [],
                work_weekend: 0,
            }
        },

        methods: {
            sortTriplets() {
                let triplets = this.triplets;
                let settings = this.settings;
                let arrResult = [];


                if(order) {
                    // Combiner les paramètres avec les départements actuels
                    arrResult = _.map(triplets, function (obj) {
                        return _.assign(obj, _.find(settings, {
                            id: obj.id
                        }));
                    });
                } else {
                    arrResult = triplets;
                }

                // Classer les départements en ordre et réattribuer leur index
                // (au cas si un département est ajouté/retiré)
                this.tripletsWithSettings = _.each(_.sortBy(arrResult, 'order'), function (obj, i) {
                    obj.order = i;
                    if(!obj.hasOwnProperty('active')) obj.active = false;
                });
            },

            moveUp(item) {
                let itemOrder = item.order;

                // On fait rien si l'item est déjà le plus haut
                if(itemOrder == 0) return;

                let itemBefore = _.find(this.sortedTriplets, {"order": item.order - 1})

                itemBefore.order = itemOrder;
                item.order = itemOrder - 1;
            },

            moveDown(item) {
                let itemOrder = item.order;

                // On fait rien si l'item est déjà le plus bas
                if(itemOrder == this.sortedTriplets.length - 1) return;

                let itemAfter = _.find(this.sortedTriplets, {"order": item.order + 1})

                itemAfter.order = itemOrder;
                item.order = itemOrder + 1;
            },

            save() {

                let data = _.map(this.sortedTriplets, function (obj) {
                    return _.pick(obj, ['id', 'active', 'order']);
                });

                axios.patch('/api/settings/triplets', data)
                    .catch(err => {
                    console.log(err);
                });
            }
        },

        computed: {
            sortedTriplets() {
                return _.sortBy(this.tripletsWithSettings, 'order');
            }
        }
    }

</script>
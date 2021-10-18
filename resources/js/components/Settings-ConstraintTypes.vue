<template>
    <div>
        <div class="form-inline">
            Recherche: <input type="search" ref="search" class="form-control" v-model="search">
            <button @click="resetSearch">&times;</button>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th @click="orderBy('code')">Type de contrainte</th>
                <th>Description</th>
                <th @click="orderBy('status')">Status <button class="btn btn-xs btn-default pull-right" @click="setActive">Tous Actifs</button></th>
            </tr>
            </thead>
            <tbody v-if="filteredConstraintTypes.length > 0">
                <tr v-for="type in filteredConstraintTypes">
                    <td>{{ type.code }}</td>
                    <td>{{ type.name }}</td>
                    <td>
                        <select class="form-control"
                                v-model="type.status"
                                @change="statusChanged(type)"
                        >
                            <option value="0">Inactif</option>
                            <option value="1">Fortes seulement</option>
                            <option value="2">Actif</option>
                        </select>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="3">Aucun type de contrainte.</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['dataConstraintTypes'],

        data() {
            return {
                search: '',
                orderColumn: 'code',
                sortOrder: 'asc',
                constraintTypes: this.dataConstraintTypes
            }
        },

        computed: {
            filteredConstraintTypes() {
                let search = this.search;

                return _(this.constraintTypes)
                    .filter(type => {
                        if(this.search !== "") {
                            return type.code.toLowerCase().includes(this.search.toLowerCase())
                        } else {
                            return true;
                        }
                    }).orderBy(this.orderColumn, this.sortOrder)
                    .value();
            }
        },

        methods: {
            resetSearch() {
                this.search = '';
                this.$refs.search.focus();
            },

            statusChanged(type) {
                let data = {
                    id: type.id,
                    status: type.status
                };

                this.update(data, false);
            },

            setActive() {
                _(this.constraintTypes).each(type => {
                    type.status = 2;
                });

                this.update({}, true);
            },

            update(data, massUpdate) {
                axios.patch('/api/settings/constraintTypes', {
                    massUpdate,
                    data
                })
                    .then(res => console.log(res))
                    .catch(err => console.log(err));
            },

            orderBy(orderColumn) {
                if(this.orderColumn === orderColumn) {
                    this.sortOrder = (this.sortOrder === 'asc') ? 'desc' : 'asc';
                } else {
                    this.orderColumn = orderColumn;
                    this.sortOrder = 'asc';
                }
            }

        }
    }
</script>
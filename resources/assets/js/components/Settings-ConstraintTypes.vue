<template>
    <div>
        <div class="form-inline">
            Recherche: <input type="search" ref="search" class="form-control" v-model="search">
            <button @click="resetSearch">&times;</button>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Type de contrainte</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody v-if="constraintTypes.length > 0">
                <tr v-for="type in constraintTypes">
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
            }
        },

        computed: {
            constraintTypes() {
                let search = this.search;

                return _(this.dataConstraintTypes)
                    .filter(type => {
                        if(this.search !== "") {
                            return type.code.toLowerCase().includes(this.search.toLowerCase())
                        } else {
                            return true;
                        }
                    }).sortBy('code')
                    .value();
            }
        },

        methods: {
            resetSearch() {
                this.search = '';
                this.$refs.search.focus();
            },

            statusChanged(type) {
                let payload = {
                    id: type.id,
                    status: type.status
                };

                axios.patch('/api/settings/constraintTypes', payload)
                    .then(res => console.log(res))
                    .catch(err => console.log(err));
            }
        }
    }
</script>
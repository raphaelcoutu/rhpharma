<template>
    <div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th :class="{ clickable : col.sortable }" v-for="col in columns" @click="sortBy(col)">
                    {{ col.title }} <i :class="sortIcon(col)"></i>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="row in tableFilter">
                <td v-for="col in columns">
                    <span v-if="!col.slot">
                        {{ extractData(row, col.id) }}
                    </span>
                    <span v-else>
                    <slot :name="col.slot" :id="row.id" :row="row[col.id]"></slot>
                    </span>
                </td>
            </tr>
            <tr v-if="tableFilter.length == 0">
                <td :colspan="columns.length" class="text-center">Aucune donnée disponible.</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {

        props: {
            columns: {
                type: Array,
                required: true
            },
            rows: {
                type: Array,
                required: true
            },
            search: {
                type: String,
                default() {
                    return '';
                }
            }
        },

        data() {
            return {
                sortKey: '',
                sortReverse: false,
                columnsIds: []
            }
        },

        mounted() {
            this.generateColumns();
        },

        methods: {

            generateColumns() {

                let ids = [];

                for(let col of this.columns) {
                    if(typeof col === 'string') {
                        throw console.error('Error: Column should be an object.');

                    } else {
                        if(!col.hasOwnProperty('id') && !col.hasOwnProperty('slot')) {
                            console.error('Error: Column ID is missing.');
                        }

                        if(!col.hasOwnProperty('title')) {
                            col.title = 'ErrorUnamedColumn';
                        }

                        if(!col.hasOwnProperty('sortable') || !col.sortable === 'boolean') {
                            col.sortable = false;
                        }

                        ids.push(col.id);
                    }
                }

                this.columnsIds = ids;
            },

            extractData(row, col) {

                if(col.indexOf('.') !== -1) {
                    let splitByDot = col.split('.');
                    let item = row;

                    for(let split of splitByDot) {
                        item = item[split];
                    }
                    return item;
                }

                return row[col];
            },

            sortIcon(col) {
                if(col.sortable) {
                    let icon = 'fa fa-sort';

                    if(this.sortKey === col.id) {
                        icon += (!this.sortReverse) ? '-asc' : '-desc';
                    }

                    return icon;
                }
            },

            sortBy(col) {
                if(!col.sortable)
                    return;

                this.sortReverse = (this.sortKey == col.id) ? !this.sortReverse : false;

                //First property of rows object
                this.sortKey = col.id;
            },

            orderBy(list) {
                let order = (this.sortReverse) ? 'desc' : 'asc';
                return _.orderBy(list, this.sortKey, order);
            },

            filterBy() {
                let query = this.search;

                if(query) {
                    let keys = query.toLowerCase().match(/[^ ]+/g);

                    return this.rows.filter((row) => {

                        if(query.length > 1) {

                            for(let key of keys) {
                                //TODO : mettre les champs Searchable (si existe = eux, sinon tout le table)

                                //TODO : caractères spéciaux
                                if (row.firstname.toLowerCase().indexOf(key) >= 0 ||
                                    row.lastname.toLowerCase().indexOf(key) >= 0) {
                                    return true;
                                }
                            }
                            return false;

                        } else {
                            return true;
                        }

                    });
                } else {
                    return this.rows;
                }
            }
        },

        computed: {
            tableFilter() {
                return this.orderBy(this.filterBy())
            }
        }
    }
</script>
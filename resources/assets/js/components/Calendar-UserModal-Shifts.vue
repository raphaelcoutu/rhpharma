<template>
    <div>
        <input type="search" class="form-control" v-model="search" placeholder="Rechercher" @keyup="filterShifts">
        <div id="shifts">
            <li v-for="shift in filteredShifts">
                <div @click="toggleActive(shift)">
                    <input type="checkbox" v-bind:checked="shift.active">  {{ shift.code}}
                </div>
            </li>
        </div>
    </div>
</template>

<style>
    #shifts > li {
        list-style: none;
    }

    #shifts > li:nth-child(odd) {
        background-color: #e3e3e3;
    }

    #shifts {
        height:100px;
        overflow-y: auto;
    }
</style>

<script>
    export default {
        props: ['dataShifts', 'dataAssignedShifts'],

        mounted() {
            this.parseShifts();
            this.filterShifts();
        },

        data() {
            return {
                search: '',
                shifts: [],
                filteredShifts: []
            }
        },

        methods: {
            parseShifts() {
                let that = this;
                this.shifts = _.forEach(this.dataShifts, function (item) {
                    item.active = _.some(that.dataAssignedShifts, {'shift_id': item.id});
                });
            },
            filterShifts() {
                let shifts = this.shifts;
                if(this.search.length > 0) {
                    let that = this;
                    shifts = shifts.filter(function (item) {
                        return item.code.toLowerCase().includes(that.search.toLowerCase())
                    });
                }

                this.filteredShifts =  _.orderBy(shifts, ['active'], ['desc']);
            },
            toggleActive(shift) {
                shift.active = !shift.active;
                this.filterShifts();
            }
        }

    }
</script>
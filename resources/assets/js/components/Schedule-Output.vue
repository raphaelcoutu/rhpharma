<template>
    <div>
        <a @click="changeTab(0)">Log</a>
        <a @click="changeTab(1)">Conflits ({{ conflicts.length }})</a>
        <Log v-show="tabIndex === 0"
             :data-schedule="dataSchedule"
        ></Log>
        <Conflicts v-show="tabIndex === 1"
                :data-schedule="dataSchedule"
                :data-conflicts="conflicts"
        ></Conflicts>
    </div>
</template>

<script>
    import Conflicts from './Schedule-Conflicts.vue'
    import Log from './Schedule-Log'

    export default {
        props: ['dataSchedule', 'dataConflicts', 'dataStatuses'],

        components: {
            Conflicts,
            Log
        },

        data() {
            return {
                tabIndex: 0,
                conflicts: this.dataConflicts
            }
        },

        watch: {
            dataStatuses: {
                handler(oldValue, newValue) {
                    //TODO: À changer pour une fonction qui détectera un 3 parmi les status disponibles
                    if(newValue.clinical === 3) {
                        this.showConflicts = false;
                    } else {
                        this.loadConflicts()
                            .then(res => {
                                this.conflicts = res.data;
                                this.showConflicts = true
                            });
                    }
                },
                deep: true
            }
        },

        methods: {
            loadConflicts() {
                return new Promise((resolve, reject) => {
                    axios.get('/api/conflicts/' + this.dataSchedule.id)
                        .then(res => resolve(res))
                        .catch(err => reject(err));
                })
            },

            changeTab(index) {
                this.tabIndex = index;
            }
        }

    }
</script>
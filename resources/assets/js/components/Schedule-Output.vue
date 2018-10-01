<template>
    <div>
        <div class="bg-light-grey">
        <a @click="changeTab(0)" class="btn" :class="{'text-bold btn-default': tabIndex === 0}">Log</a>
        <a @click="changeTab(1)" class="btn" :class="{'text-bold btn-default': tabIndex === 1}">Conflits ({{ conflicts.length }})</a>
        <a @click="changeTab(2)" class="btn" :class="{'text-bold btn-default': tabIndex === 2}">Notes</a>
        </div>
        <Log v-show="tabIndex === 0"
             :data-schedule="dataSchedule"
        ></Log>
        <Conflicts v-show="tabIndex === 1"
                   :data-schedule="dataSchedule"
                   :data-conflicts="conflicts"
        ></Conflicts>
        <Notes v-show="tabIndex === 2"
               :data-schedule="dataSchedule"
        ></Notes>
    </div>
</template>

<script>
    import Conflicts from './Schedule-Conflicts.vue'
    import Log from './Schedule-Log'
    import Notes from './Schedule-Notes'

    export default {
        props: ['dataSchedule', 'dataConflicts', 'dataStatuses'],

        components: {
            Conflicts,
            Log,
            Notes
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
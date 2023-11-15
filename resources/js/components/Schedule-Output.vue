<template>
    <div>
        <div class="bg-light-grey">
            <a @click="changeTab(0)" href="#log" class="btn" :class="{'text-bold btn-default': tabIndex === 0}">Log</a>
            <a @click="changeTab(1)" href="#conflicts" class="btn" :class="{'text-bold btn-default': tabIndex === 1}">Conflits ({{ conflicts.length }})</a>
            <a @click="changeTab(2)" href="#notes" class="btn" :class="{'text-bold btn-default': tabIndex === 2}">Notes</a>
            <a @click="changeTab(3)" href="#stats-departments" class="btn" :class="{'text-bold btn-default': tabIndex === 3}">Stats-Départements</a>
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
        <Stats-Departments v-show="tabIndex === 3"
               :data-schedule="dataSchedule"
        ></Stats-Departments>
    </div>
</template>

<script>
    import Conflicts from './Schedule-Conflicts.vue'
    import Log from './Schedule-Log.vue'
    import Notes from './Schedule-Notes.vue'
    import StatsDepartments from './Schedule-Stats-Departments.vue'

    export default {
        props: ['dataSchedule', 'dataConflicts', 'dataStatuses'],

        components: {
            Conflicts,
            Log,
            Notes,
            StatsDepartments
        },

        mounted() {
            let tabs = ['#log', '#conflicts', '#notes', '#stats-departments'];
            let index = tabs.indexOf(window.location.hash);

            // Si le hash est présent, on met la tab appropriée
            if(index !== -1) {
                this.tabIndex = index;
            }
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

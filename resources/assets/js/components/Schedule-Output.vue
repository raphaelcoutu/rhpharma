<template>
    <Conflicts v-show="showConflicts"
            :data-schedule="dataSchedule"
            :data-conflicts="conflicts"
    ></Conflicts>
</template>

<script>
    import Conflicts from './Schedule-Conflicts.vue'

    export default {
        props: ['dataSchedule', 'dataConflicts', 'dataStatuses'],

        components: {
            Conflicts
        },

        data() {
            return {
                showConflicts: true,
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
            }
        }

    }
</script>
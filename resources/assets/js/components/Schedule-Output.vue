<template>
    <conflicts v-show="!showConsole"
            :schedule="schedule"
            :conflicts="conflicts"
    ></conflicts>
</template>

<script>
    import Conflicts from './Schedule-Conflicts.vue'

    export default {
        props: ['schedule', 'conflicts'],

        mounted() {
            Echo.channel('build-status')
                .listen('UpdateBuildStatus', (e) => {
                    if(e.scheduleId === this.schedule.id) {
                        this.showConsole = (e.status == 3);
                    }
                });
        },

        components: {
            Conflicts
        },

        data() {
            return {
                showConsole: this.schedule.status_clinical_departments === 3
            }
        }

    }
</script>
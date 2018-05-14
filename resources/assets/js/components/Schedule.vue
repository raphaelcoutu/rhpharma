<template>
    <div>
        <Processus
                :schedule="schedule"
                :constraints-count="constraintCount"
                :statuses="statuses"
                v-on:updateBuildStatus="buildStatusUpdated"
        ></Processus>
        <Output
                :data-schedule="schedule"
                :data-conflicts="conflicts"
                :data-statuses="statuses"
        ></Output>
    </div>
</template>

<script>
    import Processus from './Schedule-Processus';
    import Output from './Schedule-Output';

    export default {
        components: {
            Processus,
            Output
        },

        props: ['schedule', 'constraint-count', 'conflicts'],

        mounted() {
            Echo.channel('build-status')
                .listen('UpdateBuildStatus', (e) => {
                    if(e.scheduleId === this.schedule.id) {
                        this.buildStatusUpdated(e);
                    }
                });
        },

        data() {
            return {
                statuses: {
                    holidays: this.schedule.status_holidays,
                    weekends: this.schedule.status_weekends,
                    lastEvening: this.schedule.status_last_evening,
                    clinical: this.schedule.status_clinical_departments
                }
            }
        },

        methods: {
            buildStatusUpdated(event) {
                if(event.buildStep === 'clinical') {
                    this.statuses.clinical = event.status;
                }
            }
        }
    }

</script>
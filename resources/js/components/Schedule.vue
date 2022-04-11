<template>
    <div>
        <schedule-processus
                :data-schedule="dataSchedule"
                :data-constraints-count="dataConstraintCount"
                :data-statuses="statuses"
                v-on:updateBuildStatus="buildStatusUpdated"
        ></schedule-processus>
        <schedule-output
                :data-schedule="dataSchedule"
                :data-conflicts="dataConflicts"
                :data-statuses="statuses"
        ></schedule-output>
    </div>
</template>

<script>
    import ScheduleProcessus from './Schedule-Processus';
    import ScheduleOutput from './Schedule-Output';

    export default {
        components: {
            ScheduleOutput,
            ScheduleProcessus,
        },

        props: ['dataSchedule', 'dataConstraintCount', 'dataConflicts'],

        mounted() {
            Echo.channel('build-status')
                .listen('UpdateBuildStatus', (e) => {
                    if(e.scheduleId === this.dataSchedule.id) {
                        this.buildStatusUpdated(e);
                    }
                });
        },

        data() {
            return {
                statuses: {
                    holidays: this.dataSchedule.status_holidays,
                    weekends: this.dataSchedule.status_weekends,
                    lastEvening: this.dataSchedule.status_last_evening,
                    clinical: this.dataSchedule.status_clinical_departments
                }
            }
        },

        methods: {
            buildStatusUpdated(event) {
                if(event.buildStep === 'clinical') {
                    this.statuses.clinical = event.status;
                } else if (event.buildStep === 'last_evening') {
                    this.statuses.lastEvening = event.status;
                }
            }
        }
    }

</script>

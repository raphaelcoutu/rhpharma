<template>
    <div>
        <div v-if="dataStatus !== 3">
            <button class="btn btn-sm btn-success" @click="updateStatus(3)"><slot></slot></button>
        </div>
        <div v-else>
            Processus en cours...Veuillez patienter.
        </div>
    </div>
</template>
<script>

    export default {
        props: ['dataScheduleId', 'dataBuildStep', 'dataStatus'],

        data() {
            return {
                event: {
                    scheduleId: this.dataScheduleId,
                    buildStep: this.dataBuildStep
                }
            }
        },

        methods: {
            updateStatus(newStatus) {
                this.event.status = newStatus;
                this.$emit('updateBuildStatus', this.event);
                axios.post('/api/schedules/updateStatus', this.event);
            }
        }
    }

</script>

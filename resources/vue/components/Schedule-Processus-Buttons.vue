<template>
    <div>
        <div class="btn-group btn-group-sm" v-if="dataStatus !== 3">
            <button class="btn btn-success" @click="updateStatus(3)">Générer</button>
            <button class="btn btn-primary" @click="updateStatus(6)">Réanalyser</button>
            <button class="btn btn-danger" @click="updateStatus(5)">Mise à zéro</button>
        </div>
        <div v-else>
            Processus en cours...Veuillez patienter.
            <button @click="updateStatus(4)" class="btn btn-danger btn-xs">Annuler</button>
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
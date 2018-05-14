<template>
    <div>
        <div class="btn-group btn-group-sm" v-if="dataStatus !== 3">
            <button class="btn btn-success" @click="build">Générer</button>
            <button class="btn btn-primary">Réanalyser</button>
            <button class="btn btn-danger">Mise à zéro</button>
        </div>
        <div v-else>Processus en cours...Veuillez patienter.</div>
    </div>
</template>

<script>
    export default {

        props: ['dataScheduleId', 'dataBuildStep', 'dataStatus'],

        methods: {
            build() {
                this.$emit('updateBuildStatus', {
                    buildStep: this.dataBuildStep,
                    status: 3
                });
                axios.get('/api/build/' + this.dataScheduleId + '/' + this.dataBuildStep);
            }
        }
    }
</script>
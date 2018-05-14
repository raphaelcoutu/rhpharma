<template>
    <div>
        <div class="btn-group btn-group-sm" v-if="status !== 3">
            <button class="btn btn-success" @click="build()">Générer</button>
            <button class="btn btn-primary">Réanalyser</button>
            <button class="btn btn-danger">Mise à zéro</button>
        </div>
        <div v-else>Processus en cours...Veuillez patienter.</div>
    </div>
</template>

<script>
    export default {

        props: ['scheduleId', 'buildStep', 'status'],

        data() {
            return {

            }
        },

        methods: {
            build() {
                this.$emit('updateBuildStatus', {
                    buildStep: this.buildStep,
                    status: 3
                });
                axios.get('/api/build/' + this.scheduleId + '/' + this.buildStep);
            }
        }
    }
</script>
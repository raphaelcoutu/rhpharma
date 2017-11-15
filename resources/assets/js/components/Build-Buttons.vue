<template>
    <div>
        <div class="btn-group btn-group-sm">
            <button class="btn btn-success" @click="build()">Générer</button>
            <button class="btn btn-primary">Réanalyser</button>
            <button class="btn btn-danger">Mise à zéro</button>
        </div>
    </div>
</template>

<script>
    export default {

        props: {
            scheduleId: {
                required: true
            },
        },

        data() {
            return {

            }
        },

        methods: {
            build() {
                axios.get('/build/' + this.scheduleId + '/clinicalDepartments')
                    .then(() => {
                        // Désactiver les boutons (incluant les autres sections (holidays, weekends, lastWeekNight)
                    });
            }
        }

    }
</script>
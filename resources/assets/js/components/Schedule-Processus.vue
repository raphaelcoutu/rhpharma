<template>
    <div>
        <table class="table table-hover">
            <thead>
            <tr>
                <th width="40%">Étape</th>
                <th width="20%">Status</th>
                <th width="40%">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    Validation des contraintes des fériés/fins de semaine
                    <ul>
                        <li><strong>Limite:</strong> {{ formatDate(dataSchedule.limit_date_weekends) }}</li>
                        <li><strong>Contraintes restantes à valider:</strong> {{ dataConstraintsCount }} <i class="fa fa-refresh" style="margin-left: 10px"></i></li>
                    </ul>
                </td>
                <td>
                    <i>Icone</i>
                </td>
                <td>
                    <p>
                        <a href="/constraintImporter" class="btn btn-sm btn-primary">Importer les contraintes Azure</a>
                    </p>
                    <p>
                        <a :href="validateUrl" class="btn btn-sm btn-default">Valider les contraintes</a>
                    </p>
                </td>
            </tr>
            <tr>
                <td>Assigner les fériés</td>
                <td>
                    <div class="fa fa-2x"
                         :class="statusIcon(dataStatuses.holidays)">
                    </div>
                </td>
                <td>Boutons</td>
            </tr>
            <tr>
                <td>Assigner les fins de semaine</td>
                <td>
                    <div class="fa fa-2x"
                         :class="statusIcon(dataStatuses.weekends)">
                    </div>
                </td>
                <td>
                    <schedule-processus-pre-weekend-constraints
                        :data-schedule-id="dataSchedule.id"
                        data-build-step="weekends"
                        :data-status="dataStatuses.weekends"
                        v-on:updateBuildStatus="buildStatusChanged"
                >Compléter weekends + congés</schedule-processus-pre-weekend-constraints></td>
            </tr>
            <tr>
                <td>Assigner les derniers soirs de semaine</td>
                <td>
                    <div class="fa fa-2x"
                         :class="statusIcon(dataStatuses.lastEvening)">
                    </div>
                </td>
                <td>
                    <schedule-processus-pre-weekend-constraints
                            :data-schedule-id="dataSchedule.id"
                            data-build-step="last_evening"
                            :data-status="dataStatuses.last_evening"
                            v-on:updateBuildStatus="buildStatusChanged"
                    >Assigner les VS</schedule-processus-pre-weekend-constraints>
                </td>
            </tr>
            <tr>
                <td>
                    Validation des contraintes
                    <ul>
                        <li><strong>Limite:</strong> {{ formatDate(dataSchedule.limit_date) }}</li>
                        <li><strong>Contraintes restantes à valider:</strong> {{ dataConstraintsCount }} <i class="fa fa-refresh" style="margin-left: 10px"></i></li>
                    </ul>
                </td>
                <td>
                    <i>Icone</i>
                </td>
                <td>
                    <a :href="validateUrl" class="btn btn-default">Valider les contraintes</a>
                </td>
            </tr>
            <tr>
                <td>Assigner les secteurs cliniques</td>
                <td>
                    <div class="fa fa-2x"
                         :class="statusIcon(dataStatuses.clinical)">
                    </div>
                </td>
                <td>
                    <schedule-processus-buttons
                            :data-schedule-id="dataSchedule.id"
                            data-build-step="clinical"
                            :data-status="dataStatuses.clinical"
                            v-on:updateBuildStatus="buildStatusChanged"
                    ></schedule-processus-buttons>
                </td>
            </tr>
            <tr>
                <td>Assigner la distribution</td>
                <td>-</td>
                <td>-</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>

    import ScheduleProcessusButtons from './Schedule-Processus-Buttons.vue';
    import ScheduleProcessusPreWeekendConstraints from './Schedule-Processus-AssignPreWeekendConstraintsButton.vue';

    export default {
        props: ['dataSchedule', 'dataConstraintsCount', 'dataStatuses'],

        components: {
            ScheduleProcessusButtons,
            ScheduleProcessusPreWeekendConstraints
        },

        data() {
            return {
                validateUrl: '/constraintsValidator?schedule=' + this.dataSchedule.id,
            }
        },

        methods: {
            statusIcon(status) {
                if(status === 0) return {'fa-clock-o' : true};
                else if(status === 1) return {'fa-check-circle-o text-success': true};
                else if(status === 2) return {'fa-exclamation-triangle text-warning' : true};
                else if(status === 3) return {'fa-refresh fa-spin fa-fw text-primary' : true};
                else if(status === 4) return {'fa-stop-circle text-danger' : true};
                else if(status === 5) return {'fa-clock-o' : true};
                else if(status === 6) return {'fa-refresh fa-spin fa-fw text-primary' : true};
                else return '';
            },

            buildStatusChanged(event) {
                // Émettre l'event au parent "Schedule"
                this.$emit('updateBuildStatus', event)
            },

            formatDate(dateStr) {
                return dateStr.substr(0,10);
            }
        }
    }
</script>
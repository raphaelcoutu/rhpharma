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
                        <li><strong>Limite:</strong> {{ formatDate(schedule.limit_date_weekends) }}</li>
                        <li><strong>Contraintes restantes à valider:</strong> {{ constraintsCount }} <i class="fa fa-refresh" style="margin-left: 10px"></i></li>
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
                <td>Assigner les fériés</td>
                <td>
                    <div class="fa fa-2x"
                         :class="statusIcon(statuses.holidays)">
                    </div>
                </td>
                <td>Boutons</td>
            </tr>
            <tr>
                <td>Assigner les fins de semaine</td>
                <td>
                    <div class="fa fa-2x"
                         :class="statusIcon(statuses.weekends)">
                    </div>
                </td>
                <td>Boutons</td>
            </tr>
            <tr>
                <td>Assigner les derniers soirs de semaine</td>
                <td>
                    <div class="fa fa-2x"
                         :class="statusIcon(statuses.lastEvening)">
                    </div>
                </td>
                <td>Boutons</td>
            </tr>
            <tr>
                <td>
                    Validation des contraintes
                    <ul>
                        <li><strong>Limite:</strong> {{ formatDate(schedule.limit_date) }}</li>
                        <li><strong>Contraintes restantes à valider:</strong> {{ constraintsCount }} <i class="fa fa-refresh" style="margin-left: 10px"></i></li>
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
                         :class="statusIcon(statuses.clinical)">
                    </div>
                </td>
                <td>
                    <schedule-processus-buttons
                            :scheduleId="schedule.id"
                            buildStep="clinical"
                            :status="statuses.clinical"
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

    export default {
        props: ['schedule', 'constraintsCount', 'statuses'],

        components: {
            ScheduleProcessusButtons
        },

        data() {
            return {
                validateUrl: '/constraintsValidator?schedule=' + this.schedule.id,
            }
        },

        methods: {
            statusIcon(status) {
                if(status === 0) return {'fa-clock-o' : true};
                else if(status === 1) return {'fa-check-circle-o text-success': true};
                else if(status === 2) return {'fa-exclamation-triangle text-warning' : true};
                else if(status === 3) return {'fa-refresh fa-spin fa-fw text-primary' : true};
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
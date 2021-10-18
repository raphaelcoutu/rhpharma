<template>
    <div>
        <h3>Stats</h3>
        <div class="row" v-if="status === Status.Success">
            <button @click="generate">Regénérer</button>
            <div class="col-md-5">
            <table class="table" v-for="statDepartment in statistics">
                <thead>
                <tr>
                    <th>{{ statDepartment.department.name}}</th>
                    <th>Temps</th>
                </tr>
                </thead>
                <tbody v-for="statUser in statDepartment.users">
                <tr>
                    <td>{{ statUser.user.firstname }} {{ statUser.user.lastname }}</td>
                    <td>{{ statUser.hours }}</td>
                </tr>
                </tbody>
            </table>
            </div>
        </div>
        <div v-else-if="status === Status.Running">
            <span><i class="fa fa-spin fa-refresh"></i></span>
        </div>
        <div v-else>
            <button @click="generate">Générer</button>
        </div>

    </div>
</template>

<script>
    import Status from '../Status'

    export default {
        props: ['dataSchedule'],

        created() {
            Echo.channel(`schedule.${this.dataSchedule.id}`)
                .listen('StatsByDepartmentsGenerated', this.postGenerate);
        },

        data() {
            return {
                Status,
                status: Status.Standby,
                statistics: null
            }
        },

        methods: {
            generate() {
                this.status = Status.Running;
                axios.get(`/api/scheduleStatDepartment/${this.dataSchedule.id}/create`)
            },
            postGenerate(e) {

                axios.get(`/api/scheduleStatDepartment/${this.dataSchedule.id}`)
                    .then(res => {
                        this.statistics = JSON.parse(res.data.content);
                        this.status = Status.Success;
                    })
            }

        }

    }
</script>
<template>
    <div>
        <h3>Conflits</h3>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>Étape du processus</th>
                <th>Sévérité</th>
                <th>Départment</th>
                <th>Date</th>
                <th>Message</th>
            </tr>
            </thead>
            <tbody v-if="dataConflicts.length">
            <tr v-for="conflict in dataConflicts" v-if="dataConflicts">
                <td>{{ conflict.id }}</td>
                <td></td>
                <td></td>
                <td><a :href="departmentHref(conflict.department.id)">{{ conflict.department.name }} ({{ conflict.department.id }})</a></td>
                <td>{{ conflict.date }}</td>
                <td>{{ conflict.message }}</td>
            </tr>
            </tbody>
            <tbody v-else>
                <tr class="text-center">
                    <td colspan="6">Aucun conflit pour l'instant</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['dataSchedule', 'dataConflicts'],
        date() {
            return {
            }
        },

        methods: {
            departmentHref(id) {
                return "/calendar/" + this.dataSchedule.id + "/byDepartment/" + id;
            }
        }
    }
</script>
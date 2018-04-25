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
            <tbody v-if="conflicts.length">
            <tr v-for="conflict in conflicts" v-if="conflicts">
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
                    <td colspan="5">Aucun conflit pour l'instant</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['schedule', 'conflicts'],
        date() {
            return {
            }
        },

        methods: {
            departmentHref(id) {
                return "/calendar/" + this.schedule.id + "/byDepartment/" + id;
            }
        }
    }
</script>
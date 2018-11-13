<template>
    <div>
        <h4>{{ department.name }}</h4>
        <table class="table table-condensed">
            <thead>
            <tr>
                <th width="50%">Noms <button class="btn btn-xs"><i class="fa fa-plus-square-o"></i></button></th>
                <th width="12.5%">Actif</th>
                <th width="12.5%">Historique</th>
                <th width="12.5%">Plan. long</th>
                <th width="12.5%">Plan. court</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in users">
                <td>{{ user.firstname }} {{ user.lastname }}</td>
                <td><input type="checkbox"
                           v-model="user.pivot.active"
                           @click="settingsChanged(user, 'active')"
                ></td>
                <td>{{ user.pivot.history }}</td>
                <td>{{ user.pivot.planning_long }}</td>
                <td><input type="text" size="5"
                           v-model="user.pivot.planning_short"
                           @change="settingsChanged(user, 'planning')"
                           @focus="$event.target.select()"
                ></td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['dataDepartment'],

        created() {
            this.department = this.dataDepartment;
            this.users = this.dataDepartment.users;
        },

        data: () => ({
            department: null,
            users: null
        }),

        methods: {
            settingsChanged(user, setting) {
                let userActive = (setting === 'active') ? !user.pivot.active : user.pivot.active;

                // Si une fraction est utilisée, on la calcule.
                if(user.pivot.planning_short.includes('/')) {
                    user.pivot.planning_short = parseInt(eval(user.pivot.planning_short) * 100);
                }

                let data = {
                    departmentId: this.department.id,
                    userId: user.id,
                    userActive: userActive,
                    userPlanning: user.pivot.planning_short
                };


                axios.patch('/api/settings/departmentUser', data)
                    .then(res => {
                        //
                    }).catch(err => {
                        // TODO: Si erreur, on remet la valeur de planning_short initiale
                        alert('BUG')
                    });
            }
        }
    }
</script>
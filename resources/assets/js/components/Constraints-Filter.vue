<template>
    <div>
        Horaire:
        <select v-model="schedule">
            <option value="all">Toutes (50 dernières)</option>
            <option v-for="schedule in schedules" v-bind:value="schedule.id" >{{ schedule.name }}</option>
        </select>
    </div>
</template>

<script>

    export default {

        props: {
            schedules: {required: true}
        },

        data() {
            return {
                schedule: null
            }
        },

        watch: {
            schedule: function (val) {
                if(val != 'all') {
                    let schedule = this.schedules.find(function (el) {
                        return el.id == val;
                    });

                    this.$root.$emit('schedule', {
                        id: schedule.id,
                        start_date: schedule.start_date,
                        end_date: schedule.end_date
                    });
                } else {
                    //Si on veut toutes les constraintes, on envoit un object null
                    this.$root.$emit('schedule', null);
                }
            }
        }
    }
</script>
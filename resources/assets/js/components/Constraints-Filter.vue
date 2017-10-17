<template>
    <div>
        Horaire:
        <select>
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
            }
        }
    }
</script>
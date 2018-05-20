<template>
    <tbody>
    <tr v-for="user in dataUsers">
        <td>{{ user.lastname }}, {{ user.firstname }}</td>
        <td v-for="(date, index) in dataDates"
            :class="{'alert-info': ['0','6'].includes(date.format('e')) }"
            is="calendar-cell"
            @open="$emit('openModal', $event)"
            :data-user-id="user.id"
            :data-date="date"
        >
            <div slot="assignedShifts" v-if="getAssignedShiftByDay(user.id, date)">{{ getAssignedShiftByDay(user.id, date) }}</div>
            <div slot="constraints" class="text-danger" v-if="getConstraintsByDay(user.id, date)">{{ getConstraintsByDay(user.id, date) }}</div>
        </td>
    </tr>
    </tbody>
</template>

<script>
    import CalendarCell from './Calendar-Cell'

    export default {

        props: [
            'dataAssignedShifts',
            'dataConstraints',
            'dataDates',
            'dataUsers',
            'dataWeeksCount',
            ],

        components: {
            CalendarCell
        },

        methods: {
            getAssignedShiftByDay(userId, date) {
                return _.map(_.filter(this.dataAssignedShifts, function (shift) {
                    return shift.user_id === userId && date >= new Date(shift.date) && date <= new Date(shift.date)
                }), 'shift.code').join('-');
            },
            getConstraintsByDay(userId, date) {
                return _.map(_.filter(this.dataConstraints, function (constraint) {
                    if(constraint.user_id === userId
                        && date >= new Date(constraint.start_datetime) && date <= new Date(constraint.end_datetime)) {
                        if(constraint.day) {
                            return constraint.day === parseInt(date.format('e'));
                        } else {
                            return true;
                        }
                    }

                }), 'constraint_type.code').join('-');
            }
        }
    }
</script>
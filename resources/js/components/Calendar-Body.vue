<template>
    <tbody>
    <tr v-for="user in dataUsers">
        <td>{{ user.lastname }}, {{ user.firstname }} ({{user.workdays_per_week}})</td>
        <td v-for="(date, index) in dataDates"
            :key="`${user.id}_${dataFirstDay+index}`"
            class="noselect"
            :class="{'alert-info': ['0','6'].includes(date.format('e'))}"
            is="calendar-cell"
            @open="$emit('openModal', $event)"
            :data-user-id="user.id"
            :data-date="date"
            :data-key="`${user.id}_${dataFirstDay+index}`"
        >
            <div slot="assignedShifts" v-if="getAssignedShiftByDay(user.id, date)">
                <span v-html="getAssignedShiftByDay(user.id, date)"></span>
            </div>
            <div slot="constraints"
                 v-if="getConstraintsByDay(user.id, date).length > 0"
            >
                <span v-for="(constraint, index) in getConstraintsByDay(user.id, date)"
                      :class="{'text-danger' : constraint.isActive}"
                >{{ constraint.code }}<span v-if="index !== (getConstraintsByDay(user.id, date).length - 1)">-</span>
                </span>
            </div>
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
            'dataFirstDay',
            'dataUsers',
            'dataWeeksCount',
            ],

        components: {
            CalendarCell
        },

        data() {
            return {
            }
        },

        methods: {
            getAssignedShiftByDay(userId, date) {
                return _.map(_.filter(this.dataAssignedShifts, function (shift) {
                    return shift.user_id === userId && date >= new Date(shift.date) && date <= new Date(shift.date)
                }), function(assignedShift) {
                    let code = assignedShift.shift.code;
                    if(!assignedShift.is_generated) {
                        return '<span class="text-yellow">' + code + '</span>';
                    } else {
                        return code;
                    }
                }).join('-');
            },
            getConstraintsByDay(userId, date) {
                return _(this.dataConstraints).filter(function (constraint) {
                    let dateStart = parseInt(date.format('x'));
                    let dateEnd = dateStart + 86400000 - 1;
                    let constraintStart = new Date(constraint.start_datetime).getTime();
                    let constraintEnd = new Date(constraint.end_datetime).getTime();

                    if(constraint.user_id === userId
                        && ((constraintStart >= dateStart && constraintStart <= dateEnd)
                            || (constraintStart < dateEnd && constraintEnd > dateStart))) {
                        if(constraint.day) {
                            return constraint.day === parseInt(date.format('e'));
                        } else {
                            return true;
                        }
                    }
                }).map(function (constraint) {
                    let code = (constraint.weight)
                        ? `[${constraint.constraint_type.code}]`
                        : `${constraint.constraint_type.code}`;

                    let isActive = (constraint.constraint_type.status === 2
                        || (constraint.constraint_type.status === 1 && constraint.weight === 1));

                    return {
                        code,
                        isActive
                    };
                }).orderBy(['isActive'], ['desc']).value();
            }
        }
    }
</script>
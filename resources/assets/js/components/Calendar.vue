<template>
    <div>
        <table class="table-bordered calendar">
            <calendar-header
                    :data-dates="dates"
                    :data-duration="duration"
                    :data-first-day="firstDay"
                    :data-weeks-count="weeksCount"
                    v-on:firstDayChanged="firstDay = $event"
                    v-on:weeksCountChanged="specifiedWeeksCount = $event"
            ></calendar-header>
            <calendar-body
                    :data-assigned-shifts="assignedShifts"
                    :data-constraints="constraints"
                    :data-dates="dates"
                    :data-users="users"
                    :data-weeks-count="weeksCount"
                    v-on:openModal="openModal"
            ></calendar-body>
        </table>
        <calendar-user-modal
                v-if="showModal"
                :data-modal="dataModal"
                @save="saveModal"
                @close="closeModal"
        ></calendar-user-modal>
    </div>
</template>

<style>
    .calendar {
        margin: 20px auto;
    }
    .calendar > thead tr:nth-child(1) th{
        background: rgba(255,255,255,0.9);
        position: sticky;
        top: -1px;
        z-index: 10;
        text-align: center;
        padding: 8px 0px;
    }
    .calendar > tbody > tr > td:not(:first-child) {
        text-align: center;
        cursor: pointer;
    }

    .calendar > tbody > tr > td > div {
        overflow-x: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 60px;
    }

    .calendar > tbody > tr:nth-child(odd) {
        background: #EFF8FF;
    }

    .calendar > tbody > tr:nth-child(odd) > td.alert-info {
        background: #BCDEFA;
    }
</style>

<script>
    import moment from 'moment';
    import CalendarHeader from './Calendar-Header'
    import CalendarBody from './Calendar-Body'
    import CalendarUserModal from './Calendar-UserModal'

    export default {
        props: ['dataSchedule', 'dataUsers'],

        components: {
            CalendarHeader,
            CalendarBody,
            CalendarUserModal
        },

        mounted() {
            let that = this;
            window.addEventListener('keyup', (event) => {
                if(event.code === 'Escape' && this.showModal === true) {
                    this.closeModal();
                }
            });

            // Changer le nombre de colonnes selon la grandeur de fenêtre
            this.$nextTick(function() {
                window.addEventListener('resize', function(e) {
                    that.windowWidth = window.innerWidth;
                });
            })
        },

        data() {
            return {
                showModal: false,
                dataModal: null,
                firstDay: 0,
                windowWidth: window.innerWidth,
                specifiedWeeksCount: 0,
                users: this.dataUsers,
                duration: moment(this.dataSchedule.end_date).diff(this.dataSchedule.start_date, 'days') + 1
            }
        },

        methods: {
            openModal(e) {
                //Obtenir le pharmacien et tous les renseignements
                axios.get('/api/calendar/getUserData', {
                    params : {
                        userId: e.dataUserId,
                        date: e.dataDate.format("YYYYMMDD")
                    }
                }).then(res => {
                        this.showModal = true;
                        this.dataModal = {
                            date: e.dataDate,
                            user: res.data.user,
                            assignedShifts: res.data.assignedShifts,
                            constraints: res.data.constraints,
                            shifts: res.data.shifts,
                        };
                    });
            },
            closeModal() {
                this.showModal = false;
                this.dataModal = null;
            },
            saveModal(e) {
                this.closeModal()
                axios.post('/api/calendar', {
                    user_id: e.user_id,
                    date: e.date.format("YYYY-MM-DD"),
                    shifts: e.shifts
                }).then(res => {
                    this.updateAssignedShifts(e, res.data);
                })
            },
            updateAssignedShifts(event, data) {
                if(typeof event === 'object') {
                    let id = _.findIndex(this.users, ['id', event.user_id]);

                    let assignedShifts = _.reject(this.users[id].assigned_shifts, function (shift) {
                        let shiftDate = new Date(shift.date);
                        return shiftDate >= event.date && shiftDate <= event.date;
                    });

                    this.users[id].assigned_shifts = assignedShifts;

                    _.each(data, newShift => {
                        this.users[id].assigned_shifts.push(newShift);
                    });
                }

                //Todo: typeof Array (pour plusieurs d'un coup!)

            }
        },

        computed: {
            startDate() {
                return moment(this.dataSchedule.start_date).add(this.firstDay, 'days');
            },
            endDate() {
                return this.startDate.clone().add(this.weeksCount * 7 -1, 'days').hours(23).minutes(59);
            },
            dates() {
                if(this.startDate) {
                    let dates = [];

                    for (let i = 0; i < (this.weeksCount * 7); i++) {
                        dates.push(this.startDate.clone().add(i, 'days'))
                    }

                    return dates;
                }
                return null;
            },
            assignedShifts() {
                if(this.startDate && this.endDate) {
                    let that = this;

                    return _.filter(
                        _.flatMap(this.users, 'assigned_shifts'), function (shift) {
                            let shiftDate = new Date(shift.date);
                                return that.startDate <= shiftDate && that.endDate >= shiftDate
                        });
                }
            },
            constraints() {
                if(this.startDate && this.endDate) {
                    let that = this;

                    return _.filter(
                        _.flatMap(this.users, 'constraints'), function (constraint) {
                            let constraintStart = new Date(constraint.start_datetime);
                            let constraintEnd = new Date(constraint.end_datetime);

                            return (constraintStart >= that.startDate && constraintStart <= that.endDate)
                                || (constraintStart < that.endDate && constraintEnd > that.startDate);
                        });
                }
            },
            weeksCount() {
                if(this.specifiedWeeksCount > 0) {
                    return this.specifiedWeeksCount;
                }

                if(this.windowWidth) {
                    let nameWidth = 220;
                    let shiftWidth = 60;
                    let col = 0;

                    while(this.windowWidth > (col + 1) * 7 * shiftWidth + nameWidth) {
                        col += 1;
                    }

                    return col;
                }

                return 1;
            }
        }
    }
</script>
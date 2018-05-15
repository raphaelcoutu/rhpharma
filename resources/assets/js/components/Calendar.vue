<template>
    <div class="table-responsive">
        <slot :showModal="showModal"
              :openModal="openModal"
              :saveModal="saveModal"
              :closeModal="closeModal"
              :dataModal="dataModal"
        ></slot>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        props: ['dataSchedule'],

        data() {
            return {
                showModal: false,
                dataModal: null
            }
        },

        methods: {
            openModal(e) {
                // Obtenir la bonne date
                let date = moment(this.dataSchedule.start_date).add(e.dataDayId, 'days');

                let queryDate = date.format("YYYYMMDD");
                //Obtenir le pharmacien et tous les renseignements
                axios.get('/api/calendar/'+ e.dataUserId +'/'+ queryDate)
                    .then(res => {
                        this.dataModal = {
                            date: date,
                            user: res.data.user,
                            assignedShifts: res.data.assignedShifts,
                            constraints: res.data.constraints,
                            shifts: res.data.shifts,

                        };
                        this.showModal = true;
                    });
            },
            closeModal() {
                this.showModal = false;
                this.dataModal = null;
            },
            saveModal(e) {
                axios.post('/api/calendar', e)
                    .then(res => this.closeModal())
            },
            getDate(startDate, dayId) {

                return date;
            }
        }
    }
</script>
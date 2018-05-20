<template>
    <transition name="modal">
        <div class="m-mask">
            <div class="m-container" :class="{ 'transparent' : isTransparent}">
                <div class="m-header">
                    <div class="m-header-left">
                        <p>DATE: {{ dataModal.date.format("YYYY-MM-DD") }}</p>
                        <h3>{{ dataModal.user.firstname }} {{ dataModal.user.lastname}}</h3>
                    </div>
                    <div class="m-header-right">
                        <label>
                            <input type="checkbox" v-model="isTransparent"> Transparence
                        </label>
                    </div>
                </div>

                <div class="m-body">
                    <div class="m-body-left">
                        <div>
                            <h4>Shifts</h4>
                            <user-modal-shifts
                                    :data-shifts="dataModal.shifts"
                                    :data-assigned-shifts="dataModal.assignedShifts"
                                    ref="userModalShifts"
                            ></user-modal-shifts>
                        </div>
                        <div>
                            <h4>Contraintes</h4>
                            <div v-if="dataModal.constraints.length > 0">
                            <li v-for="constraint in dataModal.constraints">
                                {{ constraint.constraint_type.name }}
                                <div style="padding-left:5px;font-size: small">
                                    ({{ constraint.start_datetime}}  - {{ constraint.end_datetime}})
                                </div>
                            </li>
                            </div>
                            <div v-else>
                                <p><i>Aucune contrainte.</i></p>
                            </div>
                        </div>
                    </div>
                    <div class="m-body-center">
                        <p>default body</p>
                    </div>
                    <div class="m-body-right">

                    </div>
                </div>

                <div class="m-footer">
                    <div class="m-default-button">
                        <button class="btn btn-success" @click="save()">Enregistrer</button>
                        <button class="btn btn-default" @click="$emit('close')">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<style>
    .m-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
    }

    .m-container {
        width: 90%;
        margin: 0px auto;
        padding: 15px 20px;
        background-color: rgba(255,255,255, 1);
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
        min-height: 500px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }

    .transparent {
        background-color: rgba(255,255,255, 0.5);
    }

    .m-header {
        display:flex;
        justify-content: space-between;
        align-content: center;
        padding-bottom: 10px;
        border-bottom: 1px solid #e3e3e3;
    }

    .m-header > p {
        margin:0;
    }

    .m-header-left {
        display:flex;
        flex-direction: column;
        width:80%;
    }

    .m-header-right {
        width:20%;
        text-align: right;
    }

    .m-header-left h3 {
        color: #42b983;
        margin:0;
    }

    .m-body {
        max-height: 70vh;
        display: flex;
        justify-content: space-between;
        flex: 1;
    }

    .m-body-left, .m-body-center, .m-body-right {
        width:30%;
    }

    .m-body-left {
        display:flex;
        flex-direction: column;
    }

    .m-footer {
        border-top: 1px solid #e3e3e3;
        padding-top: 10px;
    }

    .m-default-button {
        display: flex;
        justify-content: flex-end;
        width:100%;
    }

    .m-default-button > button:first-child {
        margin-right: 10px;
    }

    /*
     * The following styles are auto-applied to elements with
     * transition="modal" when their visibility is toggled
     * by Vue.js.
     *
     * You can easily play with the modal transition by editing
     * these styles.
     */

    .modal-enter {
        opacity: 0;
    }

    .modal-leave-active {
        opacity: 0;
    }

    .modal-enter .modal-container,
    .modal-leave-active .modal-container {
        /*-webkit-transform: scale(1.1);*/
        /*transform: scale(1.1);*/
    }
</style>

<script>
    import UserModalShifts from './Calendar-UserModal-Shifts'

    export default {
        props: ['dataModal'],

        components: {
            UserModalShifts
        },

        data() {
            return {
                isTransparent: false
            }
        },

        methods: {
            save() {
                let shiftsIds = _.map(_.filter(this.$refs.userModalShifts.$data.shifts, ['active', true]), 'id');
                this.$emit('save', {
                    user_id: this.dataModal.user.id,
                    date: this.dataModal.date,
                    shifts: shiftsIds
                });
            }
        }
    }
</script>
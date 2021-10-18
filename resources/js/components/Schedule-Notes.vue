<template>
    <div>
        <h3>Notes</h3>

        <div class="form-group">
            <textarea class="form-control" rows="10" v-model="notes"></textarea>
        </div>

        <div class="form-group pull-right">
            <span v-text="message" v-if="message" class="alert alert-success mr-10"></span>
            <button class="btn btn-primary" @click="save" :disabled="message.length > 0">Sauvegarder</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['dataSchedule'],

        mounted() {
            this.notes = this.dataSchedule.notes;
        },

        data: () => ({
            notes: '',
            message: ''
        }),

        methods: {
            save() {
                axios.put('/api/schedules/'+ this.dataSchedule.id +'/updateNotes', {
                    notes: this.notes
                }).then(result => {
                    this.message = 'Sauvegarde effectuée avec succès.';
                    this.clearMessage(2000);
                });
            },

            clearMessage(delay) {
                let that = this;
                setTimeout(function () {
                    that.message = ''
                }, delay);
            }
        }
    }
</script>
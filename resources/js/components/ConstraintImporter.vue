<template>
    <div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Début:</label>
                    <input
                        type="date"
                        v-model="startDate"
                        placeholder="AAAAMMJJ"
                        class="form-control"
                    />
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Fin:</label>
                    <input
                        type="date"
                        v-model="endDate"
                        placeholder="AAAAMMJJ"
                        class="form-control"
                    />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <button class="btn btn-default" disabled>Visualiser</button>
                <a
                    :href="`/constraintImporter/import?start=${startDate}&end=${endDate}`"
                    class="btn btn-success"
                    :disabled="!formValid()"
                    >Importer</a
                >
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";

export default {
    data() {
        return {
            startDate: moment().format("YYYY-MM-DD"),
            endDate: moment().format("YYYY-MM-DD"),
        };
    },

    methods: {
        formValid() {
            let momentStart = moment(this.startDate);
            let momentEnd = moment(this.endDate);

            if (this.startDate == null) return false;
            if (this.endDate == null) return false;
            if (momentStart.isAfter(momentEnd)) return false;

            return true;
        },
    },
};
</script>

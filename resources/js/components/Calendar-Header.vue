<template>
    <thead>
    <tr>
        <th width="220px">
            <div class="btn-group btn-group-xs">
                <button @click="changeWeek(-1)" :disabled="dataFirstDay <= 0" class="btn btn-default"><i class="fa fa-long-arrow-left"></i></button>
                <button @click="changeWeek(1)" :disabled="dataFirstDay >= (dataDuration - 7 * dataWeeksCount)" class="btn btn-default"><i class="fa fa-long-arrow-right"></i></button>
            </div>
            Sem. {{ (dataFirstDay / 7) + 1 }} - {{ (dataFirstDay / 7) + dataWeeksCount }}
            <div class="btn-group btn-group-xs">
                <button @click="changeWeeksCount(1)" :disabled="dataDuration / 7 === dataWeeksCount || dataWeeksCount === 4" class="btn btn-default"><i class="fa fa-circle-up"></i></button>
                <button @click="changeWeeksCount(-1)" :disabled="dataWeeksCount === 1" class="btn btn-default"><i class="fa fa-circle-down"></i></button>

            </div>
        </th>
        <th width="60px" v-for="date in dataDates">
            {{ date.format('DD-MM') }}
        </th>
    </tr>
    </thead>
</template>

<script>
    export default {
        props: [
            'dataDates',
            'dataDuration',
            'dataFirstDay',
            'dataWeeksCount'
        ],

        mounted() {
            window.addEventListener('keyup', (event) => {
                if(event.code === 'ArrowLeft') {
                    this.changeWeek(-1);
                }else if (event.code === 'ArrowRight') {
                    this.changeWeek(1);
                }
            });
        },

        computed: {
            duration() {
                return this.dataDates.length;
            }
        },

        methods: {
            changeWeek(weekIncrement) {
                // Si on affiche le premier jour : on ne veut pas aller plus bas
                if(this.dataFirstDay <= 0 && weekIncrement < 0) return;
                // Si on affiche le dernier jour : on ne veut pas aller plus haut
                if(this.dataFirstDay >= (this.dataDuration - 7 * this.dataWeeksCount) && weekIncrement > 0) return;

                //On avise le main component
                this.$emit('firstDayChanged', this.dataFirstDay + 7 * weekIncrement)
            },
            changeWeeksCount(increment) {
                if(this.dataWeeksCount === 1 && increment < 0)  return;
                if((this.dataDuration / 7 === this.dataWeeksCount || this.dataWeeksCount === 4) && increment > 0) return;

                this.$emit('weeksCountChanged', this.dataWeeksCount + increment)
            }
        }
    }
</script>

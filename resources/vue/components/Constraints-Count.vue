<template>
    <div>
        <div class="smallcaps">Décompte</div>
        <div><span class="count" v-text="count"></span> contrainte<span v-if="count > 1">s</span></div>
    </div>
</template>

<style>
    .smallcaps {
        font-variant: all-small-caps;
        font-size:24px;
        color:darkgrey;
    }
    .count {
        font-size:32px;
    }

    div {
        padding: 0;
        margin:0;
        line-height: 1.5em;
    }
</style>

<script>
    export default {

        mounted() {
            this.$root.$on('constraints-count', data => {
                this.count = data;
            });
        },

        data() {
            return {
                count: 0
            }
        }
    }
</script>
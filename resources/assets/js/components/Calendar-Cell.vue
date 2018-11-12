<template>
    <td @click.left="toggleSelect" @click.right.prevent="openModal"
        :class="{ 'bg-red' : selected.includes(dataKey) }"
    >
        <slot name="assignedShifts"><div>&nbsp;</div></slot>
        <slot name="constraints"><div>&nbsp;</div></slot>
    </td>
</template>

<script>
    export default {
        props: ['dataUserId', 'dataDate', 'dataKey'],

        methods: {
            openModal(event) {
                    let payload = {
                        dataUserId: this.dataUserId,
                        dataDate: this.dataDate
                    };

                    this.$emit('open', payload)
            },
            toggleSelect() {
                let index = this.selected.indexOf(this.dataKey);
                if(index !== -1) {
                    this.$store.commit('calendar/removeSelected', index)
                } else {
                    this.$store.commit('calendar/addSelected', this.dataKey)
                }
            }
        },

        computed: {
            selected() {
                return this.$store.state.calendar.selected
            }
        }
    }
</script>
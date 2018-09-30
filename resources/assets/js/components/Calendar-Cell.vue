<template>
    <td @click="handleClick($event)"
        :class="{ 'bg-red' : isSelected }"
    >
        <slot name="assignedShifts"><div>&nbsp;</div></slot>
        <slot name="constraints"><div>&nbsp;</div></slot>
    </td>
</template>

<script>
    export default {
        props: ['dataUserId', 'dataDate', 'dataKey'],

        data: () => ({
            clickCount: 0,
            clickTimer: null
        }),

        methods: {
            handleClick(event) {
                event.preventDefault();

                this.clickCount++;

                if (this.clickCount === 1) {
                    this.clickTimer = setTimeout(() => {
                        this.clickCount = 0;

                        this.$emit('select', this.dataKey)

                    }, 200)
                } else if (this.clickCount === 2) {
                    clearTimeout(this.clickTimer);
                    this.clickCount = 0;

                    let payload = {
                        dataUserId: this.dataUserId,
                        dataDate: this.dataDate
                    };

                    this.$emit('open', payload)
                }
            }
        },

        computed: {
            isSelected() {
                return this.$parent.$data.selected.includes(this.dataKey)
            }
        }
    }
</script>
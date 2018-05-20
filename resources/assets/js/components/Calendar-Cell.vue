<template>
    <td @click="$emit('open', {dataUserId, dataDate})">
        <slot name="assignedShifts"><div>&nbsp;</div></slot>
        <slot name="constraints"><div>&nbsp;</div></slot>
    </td>
</template>

<script>
    export default {
        props: ['dataUserId', 'dataDate'],
    }
</script>
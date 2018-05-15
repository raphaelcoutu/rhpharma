<template>
    <td class="pointer" @click="$emit('open', {dataUserId, dataDayId})">
        <slot></slot>
    </td>
</template>

<style>
    .pointer {
        cursor: pointer;
    }
</style>

<script>
    export default {
        props: ['dataUserId', 'dataDayId']
    }
</script>
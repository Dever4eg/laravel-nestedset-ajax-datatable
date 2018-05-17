<template>
    <nav class="">
        <span class="page-stats">{{pagination.from}} - {{pagination.to}} of {{pagination.total}}</span>

        <a v-if="pagination.prevPageUrl" class="btn btn-link" @click="$emit('prev');">Prev</a>
        <a class="btn btn-link" v-else :disabled="true">Prev</a>

        <a v-if="pagination.nextPageUrl" class="btn btn-link" @click="$emit('next');">Next</a>
        <a class="btn btn-link" v-else :disabled="true">Next</a>
    </nav>

</template>

<script>
    export default {
        name: "Pagination",
        props: ['pagination']
    }
</script>

<style scoped lang="scss">

</style>
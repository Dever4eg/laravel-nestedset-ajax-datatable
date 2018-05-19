<template>
    <div class="employee-edit">
        <h1>{{employee.fullname}}</h1>
        <button @click="$emit('close')">Close</button>
    </div>
</template>

<script>
    export default {
        name: "EmployeeView",
        props: ['employee'],
    }
</script>

<style scoped>

</style>
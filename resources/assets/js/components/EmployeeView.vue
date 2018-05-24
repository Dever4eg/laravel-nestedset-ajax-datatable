<template>
    <div class="employee-view">
        <div class="row">
            <div class="col-md-6">
                <h2 class="fullname">{{employee.fullname}}</h2>
                <h3 class="position">Position: {{employee.position}}</h3>
                <p>Employed since: {{employee.date}}</p>
                <p>Salary: {{employee.salary}}</p>
                <p>Chief: {{employee.chief.fullname}}</p>
            </div>
            <div class="col-md-6">
                <img class="img-fluid avatar" :src="'/storage/avatars/'+(employee.avatar ? employee.avatar : 'default.jpg')"
                     alt="avatar">
            </div>
        </div>
        <button class="btn btn-light" @click="$emit('close')">Cancel</button>
    </div>
</template>

<script>
    export default {
        name: "EmployeeView",
        props: ['employee'],
        methods: {

        }
    }
</script>

<style scoped lang="scss">
    .employee-view {

        .fullname {
            color: #444;
            text-decoration: underline;
        }
        .position {
            color: #555;
        }
        p {
            font-size: 14pt;
            color: #444;
        }
        .avatar {
            padding: 20px 0;
        }
    }
</style>
<template>
    <div class="employees-delete">
        <h4>Are you sure you want to delete the employee {{employee.fullname}}?</h4>
        <button class="btn btn-light pull-left" @click="$emit('close')">Cancel</button>
        <button class="btn btn-danger pull-right" @click="remove">Delete</button>
        <div class="clearfix"></div>
    </div>
</template>

<script>
    import PNotify from 'pnotify/dist/es/PNotify';
    PNotify.defaults.styling = 'bootstrap4';
    PNotify.defaults.delay = 1000;

    export default {
        name: "EmployeeDelete",
        props: ['employee', 'callback'],
        methods: {
            remove() {
                axios.delete('api/employees/destroy', {
                    params: {
                        id: this.employee.id
                    }
                }).then(response => {
                    PNotify.success({text: response.data});
                    this.callback();
                }).catch(error => {
                    PNotify.error({text: error});
                });

                this.$emit('close');
            }
        }
    }
</script>

<style scoped lang="scss">
    .employees-delete {
        padding: 20px;
    }
</style>
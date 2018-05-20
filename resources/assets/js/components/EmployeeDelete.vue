<template>
    <div>
        <h4>Are you sure you want to delete the employee {{employee.fullname}}?</h4>
        <button class="btn btn-light pull-left" @click="$emit('close')">Cancel</button>
        <button class="btn btn-danger pull-right" @click="remove">Delete</button>
    </div>
</template>

<script>
    import PNotify from 'pnotify/dist/es/PNotify';
    PNotify.defaults.styling = 'bootstrap4';
    PNotify.defaults.delay = 1000;

    export default {
        name: "EmployeeView",
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

<style scoped>

</style>
<template>
    <div class="employee-edit">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="fullname">Full name</label>
                    <input v-model="employee.fullname" type="text" class="form-control" id="fullname" placeholder="Full name">
                </div>


                <div class="form-group">
                    <label for="position">Position</label>
                    <input v-model="employee.position" type="text" class="form-control" id="position" placeholder="Position">
                </div>
                <div class="form-group">
                    <label for="salary">Salary</label>
                    <input v-model="employee.salary" type="number" class="form-control" id="salary" placeholder="Salary">
                </div>
                <div class="form-group">
                    <label for="date">Date of employment</label>
                    <input v-model="employee.date" type="date" class="form-control" id="date" placeholder="Date">
                </div>

                <div class="form-group">
                    <label for="chief">Chief</label>
                    <select class="form-control" id="chief"></select>
                </div>

            </div>
        </div>


        <a class="btn btn-light pull-left" @click="closeModal">Cancel</a>
        <button class="btn btn-warning pull-right" @click="">Save</button>

    </div>
</template>

<script>


    export default {
        name: "EmployeeEdit",
        props: ['employeeProp'],
        mounted() {
            if(this.employeeProp) this.employee = this.employeeProp;
            let select2 = $('#chief');
            select2.select2({
                ajax: {
                    url: 'api/employees/get-data',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            pageSize: 10,
                            sortKey: 'fullname',
                            sortDir: 'asc',
                            page: params.term ? params.term : 1,
                            search: params.term ? params.term : '',
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response.data.map(item => {
                                return {
                                    id: item.id,
                                    text: item.fullname,
                                    element: item
                                }
                            }),
                            pagination: {
                                more: (response.current_page * 10) < response.total
                            }
                        };
                    }
                },
                width: '100%',
                placeholder: "Select an chief",
                allowClear: true
            }).on('select2:select', (element) => {
                this.employee.chief_id = element.params.data.id;
            });
            if(this.employee.chief_id) {
                axios.get('api/employees/get-one', {params: {
                        id: this.employee.chief_id
                    }
                }).then(response => {
                    let chief = response.data;
                    let option = new Option( chief.fullname, chief.id, true, true);
                    select2.append(option);
                });
            }
        },
        data() {
            return {
                employee: {
                    id: '',
                    fullname: '',
                    position: '',
                    data: '',
                    salary: '',
                    chief_id: ''
                }
            }
        },
        methods: {
            closeModal() {
                $('#chief').select2('close');
                this.$emit('close');
            },
        }
    }
</script>

<style scoped lang="scss">
    .avatar {
        padding-bottom: 20px;
    }
</style>
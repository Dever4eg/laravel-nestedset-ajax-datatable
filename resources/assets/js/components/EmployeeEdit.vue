<template>
    <div class="employee-edit">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="fullname">Full name</label>
                    <input v-validate="'required'"
                           :class="{'is-invalid': errors.has('fullname') }"
                           v-model="employee.fullname" name="fullname"
                           type="text" class="form-control" id="fullname" placeholder="Full name">
                    <span class="error">{{ errors.first('fullname') }}</span>
                </div>
                <div class="form-group">
                    <label for="position">Position</label>
                    <input v-validate="'required'"
                           :class="{'is-invalid': errors.has('position') }"
                           v-model="employee.position" name="position"
                           type="text" class="form-control"
                           id="position" placeholder="Position">
                    <span class="error">{{ errors.first('position') }}</span>
                </div>
                <div class="form-group">
                    <label for="salary">Salary</label>
                    <input v-validate="'required|integer'"
                           :class="{'is-invalid': errors.has('salary') }"
                           v-model="employee.salary" name="salary"
                           type="number" class="form-control" id="salary" placeholder="Salary">
                    <span class="error">{{ errors.first('salary') }}</span>
                </div>
                <div class="form-group">
                    <label for="date">Date of employment</label>
                    <input v-validate="'required'"
                           :class="{'is-invalid': errors.has('date') }"
                           v-model="employee.date" name="date"
                           type="date" class="form-control" id="date" placeholder="Date">
                    <span class="error">{{ errors.first('date') }}</span>
                </div>

                <div class="form-group">
                    <label for="chief">Chief (non require)</label>
                    <select class="form-control" id="chief"></select>
                </div>

            </div>
        </div>


        <a class="btn btn-light pull-left" @click="closeModal">Cancel</a>
        <button class="btn btn-warning pull-right" @click="onSubmit">Save</button>

    </div>
</template>

<script>
    import PNotify from 'pnotify/dist/es/PNotify';
    PNotify.defaults.styling = 'bootstrap4';
    PNotify.defaults.delay = 1000;

    export default {
        name: "EmployeeEdit",
        props: ['employeeProp', 'callback'],
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
                    date: (function(d =new Date()) {
                        return d.getFullYear() +'-'+("0"+(d.getMonth()+1)).slice(-2) +'-'+ ("0" + d.getDate()).slice(-2);
                    }()),
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
            onSubmit () {
                this.$validator.validate().then(result => {
                    if(!result) return;
                    axios.post('api/employees/store', this.employee).then(response => {
                        PNotify.success({text: response.data});
                        // callback, (true) for update table
                        if(this.callback) this.callback(!this.employee.id);
                    }).catch(error => {
                        PNotify.error({text: error});
                    });
                    this.$emit('close');
                });
            }
        }
    }
</script>

<style scoped lang="scss">
    .avatar {
        padding-bottom: 20px;
    }
    .error {
        color: #b30001;
    }
</style>
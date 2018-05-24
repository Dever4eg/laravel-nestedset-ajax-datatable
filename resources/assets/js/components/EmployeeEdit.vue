<template>
    <div class="employee-edit">
        <div class="row">
            <div class="col-md-6">
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
                    <input v-validate="'required|integer|min:0'"
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
            <div class="col-md-6">
                <div class="form-group">
                    <label for="avatar">Avatar</label>
                    <input v-validate="'image|max:2048'"
                           :class="{'is-invalid': errors.has('avatar') }"
                           name="avatar" type="file" class="" id="avatar"
                           @change="onFileChanged">
                    <span class="error">{{ errors.first('avatar') }}</span>
                    <img id="avatar-img" :src="avatarSrc"
                         alt="avatar" class="img-fluid avatar">
                </div>
                <div v-if="progress.visibility" class="progress">
                    <div class="progress-bar" role="progressbar" :style="{width: progress.value*100 + '%'}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>


        <a class="btn btn-light pull-left" @click="closeModal">Cancel</a>
        <a class="btn btn-warning pull-right" @click="onSubmit" :class="submitBtn.disabled ? 'disabled' : ''" >Save</a>

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
                            api_token: window.api_token,
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
            this.avatarSrc = '/storage/avatars/'+(this.employee.avatar ? this.employee.avatar : 'default.jpg');

            if(this.employee.chief) {
                let chief = this.employee.chief;
                let option = new Option( chief.fullname, chief.id, true, true);
                select2.append(option);
            }
        },
        data() {
            return {
                progress: {
                    visibility: false,
                    value: 0,
                },
                avatarObj: '',
                avatarSrc: '',
                submitBtn: {
                    disabled: false,
                },
                employee: {
                    id: '',
                    fullname: '',
                    position: '',
                    date: (function(d =new Date()) {
                        return d.getFullYear() +'-'+("0"+(d.getMonth()+1)).slice(-2) +'-'+ ("0" + d.getDate()).slice(-2);
                    }()),
                    salary: '',
                    chief_id: '',
                    avatar: '',
                }
            }
        },
        methods: {
            closeModal() {
                $('#chief').select2('close');
                this.$emit('close');
            },
            onFileChanged (event) {
                 this.avatarSrc = URL.createObjectURL(event.target.files[0]);
                 this.avatarObj = event.target.files[0];
            },
            onSubmit () {
                this.$validator.validate().then(result => {
                    if(!result) return;

                    let formdata = new FormData();

                    for (var prop in this.employee)
                        formdata.append(prop, this.employee[prop]);
                    formdata.append('avatar', this.avatarObj);

                    axios.post('api/employees/store', formdata, {
                        onUploadProgress: progressEvent => {
                            this.progress.visibility = true;
                            this.submitBtn.disabled = true;
                            this.progress.value = progressEvent.loaded / progressEvent.total;
                        }
                    }).then(response => {
                        this.employee.avatar = response.data.avatar ? response.data.avatar : 'default.jpg';
                        this.closeModal();
                        PNotify.success({text: response.data.message});
                        // callback, (true) for update table
                        if(this.callback) this.callback(!this.employee.id);
                    }).catch(error => {
                        this.progress.visibility = false;
                        this.submitBtn.disabled = false;
                        console.log(error);
                        PNotify.error({text: error});
                    });

                });
            },

        }
    }
</script>

<style scoped lang="scss">
    .avatar {
        padding: 20px 0;
    }
    .error {
        color: #b30001;
    }
</style>
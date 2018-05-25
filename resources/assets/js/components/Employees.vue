<template>
    <div class="employees">

        <modals-container/>

        <div class="tableFilters row">
            <div class="col-md-6">
                <input class="form-control search" type="text" v-model="search" placeholder="Search" @input="getEmployees()">
            </div>
            <div class="col-md-6">
                <div class="form-inline pull-right">
                    <select class="form-control" v-model="PageSize" @change="getEmployees()">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                    </select>
                    <button @click="employeeEdit()" class="btn btn-primary form-control create-btn">New <i class="fa fa-plus-circle"></i></button>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

        <table class="table table-responsive-sm">
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th v-for="column in columns" :key="column.name" @click="sortBy(column.name)"
                        :style="'width:'+column.width+';'+'cursor:pointer;'">
                        {{column.label}}
                        <i class="fa" aria-hidden="true"
                           :class="column.name === sortKey ? (sortDir === 'asc' ? 'fa-sort-down' : 'fa-sort-up') : 'fa-sort'"></i>
                    </th>
                    <th><div class="">Control</div></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="employee in employees" :key="employee.id">
                    <td>
                        <img :src="avatarsUrl + 'thumbnails/'+(employee.avatar ? employee.avatar : 'default.jpg')" alt="avatar">
                    </td>
                    <td v-for="column in columns">
                        {{employee[column.name]}}
                    </td>
                    <td>
                        <div class="">
                            <a @click="employeeView(employee)" class="btn btn-primary btn-sm btn-control"><i class="fa fa-eye"></i></a>
                            <a @click="employeeEdit(employee)" class="btn btn-warning btn-sm btn-control"><i class="fa fa-edit"></i></a>
                            <a @click="employeeDelete(employee)" class="btn btn-danger btn-sm btn-control"><i class="fa fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <pagination class="pagination"
                :page-class="'page-item'"
                :page-link-class="'page-link'"
                :prev-class="'page-item'"
                :prev-link-class="'page-link'"
                :next-class="'page-item'"
                :next-link-class="'page-link'"
                :page-count="PageCount"
                :click-handler="getEmployees"
                :prev-text="'Prev'"
                :next-text="'Next'"
                ref="paginate">
        </pagination>
    </div>
</template>

<script>
    import Pagination from 'vuejs-paginate';
    import EmployeeView from './EmployeeView.vue';
    import EmployeeEdit from './EmployeeEdit.vue';
    import EmployeeDelete from './EmployeeDelete.vue';



    export default {
        name: "Employees",
        components: {pagination: Pagination},
        props: ['avatarsUrl'],
        created() {
            this.getEmployees();
        },
        data() {
            let columns = [
                {width: '23%', label: 'Fullname', name: 'fullname' },
                {width: '23%', label: 'Position', name: 'position'},
                {width: '16%', label: 'Date', name: 'date'},
                {width: '13%', label: 'Salary', name: 'salary'}
            ];

            return {
                employees: [],
                columns: columns,

                sortKey: 'fullname',
                sortDir: 'asc',
                PageSize: 10,
                PageCount: 1,

                search: '',
            }
        },

        methods: {
            getEmployees(page = 1) {
                axios.get('/api/employees/get-data', {params: {
                        pageSize:   this.PageSize,
                        search:     this.search,
                        sortKey:    this.sortKey,
                        sortDir:    this.sortDir,
                        page:       page
                    }})
                    .then(response => {
                        response = response.data;

                        this.employees = response.data;
                        this.PageCount = response.last_page;
                        this.$refs.paginate.selected = response.current_page-1;

                    })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            sortBy(key) {
                if(key !== this.sortKey) {
                    this.sortKey = key;
                    this.sortDir = 'asc';
                } else
                    this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';

                this.getEmployees();
            },
            employeeView(employee) {
                this.$modal.show(EmployeeView, {
                    employee: employee,
                    avatarsUrl: this.avatarsUrl,
                }, {
                    scrollable: true,
                    'clickToClose': false,
                    height: 'auto',
                });
            },
            employeeEdit(employee) {
                this.$modal.show(EmployeeEdit, {
                    employeeProp: employee,
                    avatarsUrl: this.avatarsUrl,
                    callback: (update_table = false) => {
                        !update_table || this.getEmployees();
                    }
                }, {
                    scrollable: true,
                    'clickToClose': false,
                    height: 'auto'
                });
            },
            employeeDelete(employee) {
                this.$modal.show(EmployeeDelete, {
                    employee: employee,
                    callback: this.getEmployees
                }, {
                    'clickToClose': false,
                    height: 'auto'
                });
            },
        },
    }
</script>

<style scoped lang="scss">
    .employees {
        .tableFilters {
            margin-bottom: 10px;
            .search {
                width: 100%;
                float: left;
                margin-bottom: 10px;
            }
        }
        .table {
            width: 100%;
        }
        .btn-control{
            min-width: 32px;
            margin: 2px;
            i.fa {
                color: #fff;
            }
        }
        .create-btn {
            margin-left: 10px;
            width: 100px;
        }
        select {
            width: 100px;
        }
    }

</style>
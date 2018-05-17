<template>
    <div class="employees">

        <div class="tableFilters">
            <input class="search" type="text" v-model="tableData.search" placeholder="Search"
                   @input="getEmployees()">

            <div class="control">
                <div class="select">
                    <select v-model="tableData.length" @change="getEmployees()">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                    </select>
                </div>
            </div>
        </div>
        <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
            <tbody>
            <tr v-for="employee in employees" :key="employee.fullname">
                <td>{{employee.fullname}}</td>
                <td>{{employee.position}}</td>
                <td>{{employee.date}}</td>
                <td>{{employee.salary}}</td>
            </tr>
            </tbody>
        </datatable>
        <pagination :pagination="pagination"
                    @prev="getEmployees(pagination.prevPageUrl)"
                    @next="getEmployees(pagination.nextPageUrl)">
        </pagination>
    </div>
</template>

<script>
    import Datatable from './Datatable.vue';
    import Pagination from './Pagination.vue';
    export default {
        name: "Employees",
        components: {datatable: Datatable, pagination: Pagination},
        created() {
            this.getEmployees();
        },
        data() {
            let sortOrders = {};
            let columns = [
                {width: '25%', label: 'Fullname', name: 'fullname' },
                {width: '25%', label: 'position', name: 'position'},
                {width: '25%', label: 'date', name: 'date'},
                {width: '25%', label: 'salary', name: 'salary'}
            ];
            columns.forEach((column) => {
                sortOrders[column.name] = -1;
            });
            return {
                employees: [],
                columns: columns,
                sortKey: '',
                sortOrders: sortOrders,
                tableData: {
                    draw: 0,
                    length: 10,
                    search: '',
                    column: 0,
                    dir: 'asc',
                },
                pagination: {
                    lastPage: '',
                    currentPage: '',
                    total: '',
                    lastPageUrl: '',
                    nextPageUrl: '',
                    prevPageUrl: '',
                    from: '',
                    to: ''
                },
            }
        },
        methods: {
            getEmployees(url = '/api/employees/getData') {
                this.tableData.draw++;
                axios.get(url, {params: this.tableData})
                    .then(response => {
                        let data = response.data;
                        if (this.tableData.draw == data.draw) {
                            this.employees = data.data.data;
                            this.configPagination(data.data);
                        }
                    })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            configPagination(data) {
                this.pagination.lastPage = data.last_page;
                this.pagination.currentPage = data.current_page;
                this.pagination.total = data.total;
                this.pagination.lastPageUrl = data.last_page_url;
                this.pagination.nextPageUrl = data.next_page_url;
                this.pagination.prevPageUrl = data.prev_page_url;
                this.pagination.from = data.from;
                this.pagination.to = data.to;
            },
            sortBy(key) {
                this.sortKey = key;
                this.sortOrders[key] = this.sortOrders[key] * -1;
                this.tableData.column = this.getIndex(this.columns, 'name', key);
                this.tableData.dir = this.sortOrders[key] === 1 ? 'asc' : 'desc';
                this.getEmployees();
            },

            getIndex(array, key, value) {
                return array.findIndex(i => i[key] == value)
            },
        }
    }

</script>

<style scoped lang="scss">
    .employees
    {
        .tableFilters
        {
            margin-bottom: 10px;
            input
            {
                width: 400px;
            }
            .control
            {
                float: right;
            }
        }

        .table
        {
            width: 100%;
        }
    }
</style>
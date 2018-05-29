<template>
    <div class="employees-tree">
        <div v-for="item in data">
            <drag :transfer-data="{dragItem: item}">
                <drop @drop="(transferData, nativeEvent) => onDrop(transferData.dragItem, item)">
                    <div class="item">
                        <a v-if="item.hasChildren" @click="collapse(item)" class="btn collapse-btn" >
                            <i  :class="item.collapsed ? 'fa fa-minus-square' : 'fa fa-plus-square'"
                                class="pull-left"></i>
                        </a>

                        <span class="item-field-title">Fullname: </span>
                        <span class="item-field-text">{{item.fullname}}</span>

                        <span class="item-field-title">Position: </span>
                        <span class="item-field-text">{{item.position}}</span>

                    </div>
                </drop>
            </drag>

            <div class="item-children-group" v-if="item.children.length !== 0 && item.collapsed">
                <employees-tree :parentItem="item" :employees="item.children"></employees-tree>
            </div>
        </div>
    </div>
</template>

<script>
    import { Drag, Drop } from 'vue-drag-drop';

    export default {
        name: "EmployeesTree",
        props: ['employees', 'parentItem'],
        components: {drag: Drag, drop: Drop},
        data() {
            return {
                data: [],
                parent: '',
            }
        },
        mounted() {
            this.data = this.employees;
            this.parent = this.parentItem;

            if(!this.data) {
                axios.get('api/employees/get-tree')
                    .then( response => {
                        this.data = response.data;
                    });
            }

        },
        methods: {
            collapse(item) {
                item.collapsed = !item.collapsed;
                if ( item.hasChildren && item.children.length === 0) {
                    this.lazyLoad(item);
                }
            },
            lazyLoad(item) {
                axios.get('api/employees/get-tree', {params: {
                    chief_id: item.id
                }}).then(response => {
                    item.children = response.data;
                });
            },

            onDrop(dragItem, dropItem) {

                axios.post('api/employees/store', {
                    id: dragItem.id,
                    fullname: dragItem.fullname,
                    position: dragItem.position,
                    salary: dragItem.salary,
                    date: dragItem.date,
                    chief_id: dropItem.id
                }).then((response) => {
                    dragItem.chief_id = dropItem.id;
                    dropItem.hasChildren = true;

                    this.$set(dropItem.children, dropItem.children.length, dragItem);
                    this.$delete(this.data, this.data.indexOf(dragItem));

                    this.lazyLoad(this.parent);
                    this.lazyLoad(dropItem);
                });
            },
        }
    }
</script>

<style scoped lang="scss">
    .employees-tree {
        .collapse-btn {
            color: #0077ff;
            padding: 0;
        }
        .item-children-group {
            padding-left: 40px;
        }
        .item {
            padding: 10px 20px;
            margin: 5px;
            border: 1px solid #eee;
            border-radius: 5px;
            cursor: move;
            &.drag-over {
                background-color: red;
            }
            &:hover {
                background-color: #f6fff6;
            }

        }
        .item-field-title {

        }
        .item-field-text {
            padding: 5px;
            border-radius: 3px;
            background-color: #f7f7ff;
        }
    }
</style>
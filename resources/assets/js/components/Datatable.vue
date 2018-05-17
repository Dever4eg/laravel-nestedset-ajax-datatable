<template>
    <table class="table is-bordered data-table">
        <thead>
        <tr>
            <th v-for="column in columns" :key="column.name" @click="$emit('sort', column.name)"
                :style="'width:'+column.width+';'+'cursor:pointer;'">
                {{column.label}}
                <i class="fa" aria-hidden="true"
                   :class="sortKey === column.name ? (sortOrders[column.name] > 0 ? 'fa-sort-down' : 'fa-sort-up') : 'fa-sort'">
                </i>
            </th>
        </tr>
        </thead>
        <slot></slot>
    </table>
</template>

<script>
    export default {
        name: "Datatable",
        props: ['columns', 'sortKey', 'sortOrders']
    }
</script>

<style scoped>

</style>
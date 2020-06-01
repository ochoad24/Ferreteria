<template>
  <div class="main-content">
    <div class="page-header">
      <h3 class="page-title">Categoría</h3>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Components</a></li>
        <li class="breadcrumb-item active">Categoría</li>
      </ol>
    </div>
    <div class="card">
      <div class="card-header">
        <h6>Categoría</h6>
      </div>
      <div class="card-body">
        <table-component :data="fetchData" table-class="table">
          <table-column show="name" label="Nombre" :filterable="true"/>
          <table-column show="email" label="Email" :filterable="true"/>
          <table-column show="role" label="Rol" :filterable="true"/>
          <table-column show="created_at" label="Fecha de registro"/>
          <table-column
            :sortable="false"
            :filterable="false"
            label=""
          >
            <template slot-scope="row">
              <div class="table__actions">
                <a :href="`#${row.name}`">Editar</a>
                <a :href="`#${row.name}`">Eliminar</a>
              </div>
            </template>
          </table-column>
        </table-component>
      </div>
    </div>
  </div>
</template>

<script>
import { TableComponent, TableColumn } from 'vue-table-component'

export default {
  components: {
    TableComponent,
    TableColumn
  },
  methods: {
    async fetchData ({ page, filter, sort }) {
      const response = await axios.get(`/api/admin/categoria/get?page=${page}&filter=${filter}&order=${JSON.stringify(sort)}`)

      return {
        data: response.data.data,
        pagination: {
          totalPages: response.data.last_page,
          currentPage: page,
          count: response.data.count
        }
      }
    }
  }
}
</script>

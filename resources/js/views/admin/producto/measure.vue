<template>
  <div class="main-content">
    <div class="page-header">
      <h3 class="page-title">Medidas</h3>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Productos</a></li>
        <li class="breadcrumb-item active">Medidas</li>
      </ol>
    </div>
    <div class="card">
      <div class="card-header">
        <!-- <h6>Categoría</h6> -->
        <button 
          type="button"
          @click="$refs.medida_modal.open()"
          class="btn btn-theme">Nuevo
        </button>
      </div>
      <div class="card-body">
        <table-component :data="fetchData" table-class="table">
          <table-column show="name" label="Nombre"/>
          <table-column
            :sortable="false"
            :filterable="false"
            label="Estado"
            show="status"
          >
            <template slot-scope="row">
              <a v-if="`${row.status}==1`" class="btn btn-success btn-sm">Activo</a>
              <a class="btn btn-danger btn-sm" v-else>Desactivado</a>
            </template>
          </table-column>
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
      <!-- MODALES -->
      <sweet-modal
        ref="medida_modal"
        :title="tituloModal">
          <!-- START FORM -->
          <div class="card-body">
            <div class="form-group">
            <label>Nombre</label>
            <input
              :class="{ 'is-invalid': $v.name.$error }"
              v-model.trim="name"
              class="form-control"
              @input="$v.name.$touch()">
            <div v-if="$v.name.$error">
              <span v-if="!$v.name.required" class="help-block help-block-error">
                El campo es requerido
              </span>
              <span v-if="!$v.name.minLength" class="help-block help-block-error">
                El minimo del caracteres es de {{ $v.name.$params.minLength.min }} letras.
              </span>
              </div>
            </div>
          </div>
          <!-- END FORM -->
          <button
            slot="button"
            type="button"
            class="btn btn-danger"
            @click="$refs.medida_modal.close()">
            Cancelar
          </button>
          <button
            slot="button"
            type="button"
            class="btn btn-primary ladda-button" data-style="expand-right"
             v-on:click="saveMeasure">
            Guardar
          </button>
      </sweet-modal>
    </div>
  </div>
</template>

<script>
import { TableComponent, TableColumn } from 'vue-table-component'
import { SweetModal, SweetModalTab } from 'sweet-modal-vue'
import { required, sameAs, minLength, between } from 'vuelidate/lib/validators'
import ladda from 'ladda'

export default {
  components: {
    TableComponent,
    TableColumn,
    SweetModal,
    SweetModalTab
  },
  data () {
    return {
      tituloModal:'Nueva medida',
      name:''
    }
  },
  install (Vue, options) {
    Vue.component('SweetModal', SweetModal)
    Vue.component('SweetModalTab', SweetModalTab)
  },
  validations: {
    name: {
      required,
      minLength: minLength(4)
    },
  },
  methods: {
    async fetchData ({ page, filter, sort }) {
      const response = await axios.get(`/api/admin/measure/get?page=${page}&filter=${filter}&fieldName=${sort.fieldName}&order=${sort.order}`)

      return {
        data: response.data.data,
        pagination: {
          totalPages: response.data.last_page,
          currentPage: page,
          count: response.data.count
        }
      }
    },
    saveMeasure(){
      var l = ladda.create(document.querySelector('.ladda-button'));
      this.$v.$touch()
      let me=this;
      if (this.$v.$invalid) {
        toastr.error('Revise los campos','Error')
      } else {
        l.start();
        axios.post('/api/admin/measure', {
            name: this.name,
        })
        .then(function (response) {
            toastr.success(response.data.message,'Listo')
            l.stop();
            me.$refs.medida_modal.close();
        })
        .catch(function (error) {
            l.stop();
            toastr.error(error.response.data.message,'Error')    
        });
      }
    },
  }
}
</script>

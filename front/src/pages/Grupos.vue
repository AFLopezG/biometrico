<template>
<q-page>
  <div class="col-12">
    <div class="text-h5 text-center">LISTA DE GRUPOS</div>
    <q-table :rows="grupos" :columns="columns">
      <template v-slot:body-cell-opcion="props">
          <q-td key="opcion" :props="props">
            <q-btn dense icon="edit" color="yellow" @click="grupo2=props.row;dialogModificar=true;" />
            <q-btn dense icon="delete" color="red" @click="EliminarGrupo(props.row)"/>
        </q-td>
      </template>
      <template v-slot:top-right>
        <q-btn label="REGISTRAR" color="green" icon="person_add" @click="listreg" :loading="loading"/>

        <q-input borderless dense debounce="300" v-model="filter" placeholder="Buscar">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
    </q-table>

    <q-dialog v-model="dialogRegistro" >
      <q-card style="min-width: 350px">
        <q-card-section>
          <div class="text-h6">REGISTRO DE GRUPO</div>
        </q-card-section>
        <q-form @submit.prevent="onSubmit"  class="q-gutter-md">

        <q-card-section class="q-pt-none">
          <div class="row">

          <div class="col-6"><q-input v-model="grupo.tipo" label="Nombre" outlined dense required/></div>
          <div class="col-6"><q-input v-model="grupo.detalle"  label="Descripcion" dense outlined required/></div>
          <div class="col-6"><q-select v-model="grupo.rango" label="Rango" :options="rangos" outlined dense required/></div>
          <div class="col-6"><q-input v-model="grupo.sindical" label="Ap Sindical" outlined dense required/></div>
          <div class="col-6"><q-input v-model="grupo.seguro" label="Ap ProAccident" outlined dense required/></div>
          <div class="col-6"><q-input v-model="grupo.deportico" label="Ap Deportico" outlined dense required/></div>
          <div class="col-6"><q-input v-model="grupo.decano" label="Ap Decano" outlined dense required/></div>
          </div>
        </q-card-section>

        <q-card-actions align="right" class="text-primary">
          <q-btn flat color="red" label="Cancel" v-close-popup icon="cancel"/>
          <q-btn flat color="green" label="Registrar" icon="send" type="submit" :loading="loading"/>

        </q-card-actions>
        </q-form>
      </q-card>
    </q-dialog>

    <q-dialog v-model="dialogModificar" >
      <q-card style="min-width: 350px">
        <q-card-section>
          <div class="text-h6">MODIFICAR DE AFILIADO</div>
        </q-card-section>
        <q-form @submit.prevent="updateGrupo"  class="q-gutter-md">

          <q-card-section class="q-pt-none">
            <div class="row">

              <div class="col-6"><q-input v-model="grupo2.tipo" label="Nombre" outlined dense required/></div>
              <div class="col-6"><q-input v-model="grupo2.detalle"  label="Descripcion" dense outlined required/></div>
              <div class="col-6"><q-select v-model="grupo2.rango" label="Rango" :options="rangos" outlined dense required/></div>
              <div class="col-6"><q-input v-model="grupo2.sindical" label="Ap Sindical" outlined dense required/></div>
              <div class="col-6"><q-input v-model="grupo2.seguro" label="Ap ProAccident" outlined dense required/></div>
              <div class="col-6"><q-input v-model="grupo2.deportico" label="Ap Deportico" outlined dense required/></div>
              <div class="col-6"><q-input v-model="grupo2.decano" label="Ap Decano" outlined dense required/></div>
            </div>
          </q-card-section>

          <q-card-actions align="right" class="text-primary">
            <q-btn flat color="red" label="Cancel" v-close-popup icon="cancel"/>
            <q-btn flat color="green" label="Modificar" icon="pencil" type="submit" :loading="loading"/>

          </q-card-actions>
        </q-form>
      </q-card>
    </q-dialog>
  </div>
</q-page>

</template>

<script>
import {date} from 'quasar'

export default {
  name: `Grupos.vue`,
  data() {
    return {
      loading:false,
      dialogRegistro:false,
      dialogModificar:false,
      rangos:['SEMANAL','MENSUAL'],
      grupo:{},
      grupo2:{},
      grupos:[],
      filter:'',
      columns:[
        {name:'opcion',label:'OPCION'},
        {name:'tipo',label:'NOMBRE',field:'tipo'},
        {name:'detalle',label:'DESCRIPCION',field:'detalle'},
        {name:'rango',label:'RANGO',field:'rango'},
        {name:'sindical',label:'AP SINDICAL',field:'sindical'},
        {name:'seguro',label:'AP PROACCIDENT',field:'seguro'},
        {name:'deportico',label:'AP DEPORTICO',field:'deportico'},
        {name:'decano',label:'AP DECANO',field:'decano'},
        {name:'monto',label:'TOTAL',field:'monto'},
        {name:'multa',label:'MULTA',field:'multa'},
      ]
    };
  },
  created() {
    this.listadoGrupo()
    },
  methods:{
    listreg(){
      this.grupo={}
      this.grupo.rango=this.rangos[0];
      this.dialogRegistro=true
    },
      listadoGrupo(){
        this.$api.get('grupo').then((response) => {
            this.grupos=response.data
        })

        },
    onSubmit(){
       this.loading=true
        this.$api.post('grupo',this.grupo).then((response) => {
          this.$q.notify({
            message: 'Grupo Registrado',
            icon:"done",
            color: 'green'
          })
          this.dialogRegistro=false
          this.listadoGrupo()
          this.loading=false
        })
      },
    updateGrupo(){
      this.loading=true
      this.$api.put('grupo/'+this.grupo2.id,this.grupo2).then((response) => {
        this.$q.notify({
          message: 'Grupo Modificado',
          icon:"done",
          color: 'green'
        })
        this.dialogModificar=false
        this.listadoGrupo()
        this.loading=false

      })
    },
    EliminarGrupo(af){
      this.$q.dialog({
        title: 'Eliminar Grupo',
        message: 'Esta seguro de eliminar?',
        cancel: true,
        persistent: false
      }).onOk(() => {
        // console.log('>>>> OK')
        this.$api.delete('grupo/'+af.id).then((response) => {
          this.$q.notify({
            message: 'Grupo Eliminado',
            icon:"done",
            color: 'red'
          })
          this.listadoGrupo()
        })

        }).onOk(() => {
        // console.log('>>>> second OK catcher')
      }).onCancel(() => {
        // console.log('>>>> Cancel')
      }).onDismiss(() => {
        // console.log('I am triggered on both OK and Cancel')
      })
    }

  }


}
</script>

<style scoped>

</style>

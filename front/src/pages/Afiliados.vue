<template>
<q-page>
  <div class="col-12">
    <div class="text-h5 text-center">LISTA DE AFILIADOS</div>
    <q-table :rows="afiliados" :columns="columns">
      <template v-slot:body-cell-opcion="props">
          <q-td key="opcion" :props="props">
            <q-btn dense icon="edit" color="yellow" @click="afiliado2=props.row;dialogModificar=true;" />
            <q-btn dense icon="delete" color="red" @click="EliminarAfiliado(props.row)"/>
        </q-td>
      </template>
      <template v-slot:top-right>
        <q-btn label="REGISTRAR" color="green" icon="person_add" @click="listreg"/>

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
          <div class="text-h6">REGISTRO DE AFILIADO</div>
        </q-card-section>
        <q-form @submit.prevent="onSubmit"  class="q-gutter-md">

        <q-card-section class="q-pt-none">
          <div class="row">

          <div class="col-6"><q-input v-model="afiliado.ci" label="CI" outlined dense required/></div>
          <div class="col-6"><q-select v-model="afiliado.expedido" :options="exped" label="Expedido" dense outlined/></div>
          <div class="col-6"><q-input v-model="afiliado.nombres" label="Nombres" outlined dense required/></div>
          <div class="col-6"><q-input v-model="afiliado.apellidos" label="Apellidos" outlined dense required/></div>
          <div class="col-6"><q-input v-model="afiliado.fechaing" label="Fecha Ing" type="date" outlined dense required/></div>
          </div>
        </q-card-section>

        <q-card-actions align="right" class="text-primary">
          <q-btn flat color="red" label="Cancel" v-close-popup icon="cancel"/>
          <q-btn flat color="green" label="Registrar" icon="send" type="submit" />

        </q-card-actions>
        </q-form>
      </q-card>
    </q-dialog>

    <q-dialog v-model="dialogModificar" >
      <q-card style="min-width: 350px">
        <q-card-section>
          <div class="text-h6">MODIFICAR DE AFILIADO</div>
        </q-card-section>
        <q-form @submit.prevent="updateAfiliado"  class="q-gutter-md">

          <q-card-section class="q-pt-none">
            <div class="row">

              <div class="col-6"><q-input v-model="afiliado2.ci" label="CI" outlined dense required readonly/></div>
              <div class="col-6"><q-select v-model="afiliado2.expedido" :options="exped" label="Expedido" dense outlined/></div>
              <div class="col-6"><q-input v-model="afiliado2.nombres" label="Nombres" outlined dense required/></div>
              <div class="col-6"><q-input v-model="afiliado2.apellidos" label="Apellidos" outlined dense required/></div>
              <div class="col-6"><q-input v-model="afiliado2.fechaing" label="Fecha Ing" type="date" outlined dense required/></div>
            </div>
          </q-card-section>

          <q-card-actions align="right" class="text-primary">
            <q-btn flat color="red" label="Cancel" v-close-popup icon="cancel"/>
            <q-btn flat color="green" label="Modificar" icon="pencil" type="submit" />

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
  name: `Afiliados.vue`,
  data() {
    return {
      dialogRegistro:false,
      dialogModificar:false,
      afiliado:{fechaing:date.formatDate( Date.now(),'YYYY-MM-DD')},
      afiliado2:{},
      afiliados:[],
      filter:'',
      exped:['OR','LP','PT','PD','BE','CB','CH','TJ','SC','EX'],
      columns:[
        {name:'opcion',label:'OPCION'},
        {name:'ci',label:'CI',field:'ci'},
        {name:'expedido',label:'EXPEDIDO',field:'expedido'},
        {name:'nombres',label:'NOMBRES',field:'nombres'},
        {name:'apellidos',label:'APELLIDOS',field:'apellidos'},
        {name:'fechaing',label:'FECHA ING',field:'fechaing'},
      ]
    };
  },
  created() {
    this.listadoAfiliado()
    },
  methods:{
    listreg(){
      this.afiliado={fechaing:date.formatDate( Date.now(),'YYYY-MM-DD')};
      this.afiliado.expedido=this.exped[0];
      this.dialogRegistro=true
    },
      listadoAfiliado(){
        this.$api.get('afiliado').then((response) => {
            this.afiliados=response.data
        })

        },
    onSubmit(){
        this.listadoAfiliado()
        this.afiliados.forEach(r=>{
          if(r.ci==this.afiliado.ci){
            this.$q.notify({
              message: 'El carnet se encuentra registrado',
              icon:"info",
              color: 'red'
            })

            return false
          }
        })
        this.$api.post('afiliado',this.afiliado).then((response) => {
          this.$q.notify({
            message: 'Afiliado Registrado',
            icon:"done",
            color: 'green'
          })
          this.dialogRegistro=false
          this.listadoAfiliado()
        })

      },
    updateAfiliado(){
      this.$api.put('afiliado/'+this.afiliado2.id,this.afiliado2).then((response) => {
        this.$q.notify({
          message: 'Afiliado Modificado',
          icon:"done",
          color: 'green'
        })
        this.dialogModificar=false
        this.listadoAfiliado()
      })
    },
    EliminarAfiliado(af){
      this.$q.dialog({
        title: 'Eliminar Afiliado',
        message: 'Esta seguro de eliminar?',
        cancel: true,
        persistent: false
      }).onOk(() => {
        // console.log('>>>> OK')
        this.$api.delete('afiliado/'+af.id).then((response) => {
          this.$q.notify({
            message: 'Afiliado Eliminado',
            icon:"done",
            color: 'red'
          })
          this.listadoAfiliado()
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

<template>
<q-page>
  <div class="col-12">
    <div class="text-h5 text-center">LISTA DE AFILIADOS  {{grupo.tipo}}</div>
    <div class="row">
      <q-select  outlined v-model="grupo" :options="grupos" label="GRUPO" />
       <q-btn color="info" label="BUSCAR" icon="search" @click="listadoAfiliado" />
      
    </div>
    <q-table :rows="afiliados" :columns="columns">

      <template v-slot:top-right>

        <q-input borderless dense debounce="300" v-model="filter" placeholder="Buscar">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
    </q-table>
  </div>
</q-page>

</template>

<script>
import {date} from 'quasar'

export default {
  name: `Listado.vue`,
  data() {
    return {
      loading:false,
      afiliados:[],
      grupos:[],
      grupo:{},
      filter:'',
      exped:['OR','LP','PT','PD','BE','CB','CH','TJ','SC','EX'],
      columns:[
        {name:'ci',label:'CI',field:'ci'},
        {name:'expedido',label:'EXPEDIDO',field:'expedido'},
        {name:'nombres',label:'NOMBRES',field:'nombres'},
        {name:'apellidos',label:'APELLIDOS',field:'apellidos'},
        {name:'codigo',label:'TELEFONO',field:'codigo'},
        {name:'placa',label:'PLACA',field:'placa'},
      ]
    };
  },
  created() {
    //window.location.reload();
    this.listadoGrupo()
    this.listadoAfiliado()
    },
  methods:{
    listadoGrupo(){
      this.grupos=[]
        this.$api.get('grupo').then((response) => {
            response.data.forEach(r => {
              r.label=r.tipo
             this.grupos.push(r)
            });

            this.grupo=this.grupos[0]
        })

        },

      listadoAfiliado(){
        this.$api.post('listGrupoAf',{id:this.grupo.id}).then((response) => {
            this.afiliados=response.data
        })

        },




  }


}
</script>

<style scoped>

</style>

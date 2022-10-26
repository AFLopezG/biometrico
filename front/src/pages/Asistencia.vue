<template>
  <q-page>
      <q-form
        @submit="consultar"
        class="q-gutter-md"
      >
    <div class="row">

      <div class="col-3"><q-input v-model="fecha" type="date" label="Fecha" outlined dense required/></div>

      <div class="col-3"><q-btn color="green" icon="search" label="Buscar"  type="submit"/></div>
    </div>

    </q-form>
  <q-table :rows="asistencias" :columns="columns" dense :rows-per-page-options="[20,50,100,0]">
  </q-table>
    <div id="print" class="hidden"></div>
  </q-page>
  </template>

  <script>
  import Printd from 'printd'
  import {date} from 'quasar'

  export default {
    name: `Asistencia`,
    data() {
      return {
        asistencias: [],
        fecha:date.formatDate( Date.now(),'YYYY-MM-DD'),
        columns:[
          {name:'id',label:'ID',field:'id'},
          {name:'fecha',label:'FECHA',field:'fecha'},
          {name:'hora',label:'HORA',field:'hora'},
          {name:'afiliado',label:'AFILIADO',field:row=>row.afiliado.nombres+' '+row.afiliado.apellidos},
          {name:'ci',label:'CI',field:row=>row.afiliado.ci+' '+row.afiliado.expedido},
          {name:'telefono',label:'Telefono',field:row=>row.afiliado.codigo},
        ]
      };
    },
    created() {
        this.consultar()
    },

    methods:{
        consultar(){
          this.$api.post('reporte',{fecha:this.fecha}).then((res) => {
            this.asistencias=res.data})
        },

    },
  }
  </script>

  <style scoped>

  </style>

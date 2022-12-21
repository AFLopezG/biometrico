<template>
  <q-page>
      <q-form
        @submit="consultar"
        class="q-gutter-md"
      >
    <div class="row">
      <div class="col-12 col-sm-3"><q-input v-model="fecha" type="date" label="Fecha" outlined dense required/></div>
      <div class="col-12 col-sm-3 flex flex-center"><q-btn color="green" icon="search" label="Buscar"  type="submit"/></div>
      <!--<div class="col-12 col-sm-3 flex flex-center"><q-btn target="_blank" type="a" :href="`${url}repAsistencia/${fecha}`" color="primary" icon="print" label="Imprimir lista"/></div>-->
    </div>

    </q-form>
    <div class="row">
        <div class="col-3 flex flex-center"><q-btn target="_blank" type="a" :href="`${url}repAsistencia/${fecha}/1`" color="primary" icon="print" label="Grupo A y B"/></div>
        <div class="col-3 flex flex-center"><q-btn target="_blank" type="a" :href="`${url}repAsistencia/${fecha}/2`" color="secondary" icon="print" label="GRUPO C"/></div>
        <div class="col-3 flex flex-center"><q-btn target="_blank" type="a" :href="`${url}repAsistencia/${fecha}/3`" color="green" icon="print" label="GRUPO D"/></div>
        <div class="col-3 flex flex-center"><q-btn target="_blank" type="a" :href="`${url}repAsistencia/${fecha}/4`" color="info" icon="print" label="GRUPO E"/></div>
      </div>
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
        url: process.env.API,
        asistencias: [],
        fecha:date.formatDate( Date.now(),'YYYY-MM-DD'),
        columns:[
          {name:'codigo',label:'MOVIL',field:'codigo'},
          {name:'fecha',label:'FECHA',field:'fecha'},
          {name:'afiliado',label:'AFILIADO',field:row=>row.nombres+' '+row.apellidos},
          {name:'ci',label:'CI',field:row=>row.ci+' '+row.expedido},
          {name:'telefono',label:'Telefono',field:row=>row.telefono},
        ]
      };
    },
    created() {
        this.consultar()
    },

    methods:{
        consultar(){
          this.$api.post('reporte',{fecha:this.fecha}).then((res) => {
            console.log(res.data)
            this.asistencias=res.data})
        },

    },
  }
  </script>

  <style scoped>

  </style>

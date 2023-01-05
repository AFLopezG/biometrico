<template>
  <q-page>
      <q-form
        @submit="consultar"
        class="q-gutter-md"
      >
    <div class="row">
      <div class="col-12 col-sm-3"><q-input v-model="fecha" type="date" label="Fecha" outlined dense required/></div>
      <div class="col-12 col-sm-3 flex flex-center"><q-btn :loading="loading" color="green" icon="search" label="Buscar"  type="submit"/></div>
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
    <template v-slot:body-cell-option="props">
      <q-btn-group>
        <q-btn round :loading="loading" dense color="negative" icon="delete" @click="eliminar(props.row)" />
        <q-btn round :loading="loading" dense color="info" icon="print" @click="imprimir(props.row)" />
      </q-btn-group>
    </template>
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
        loading: false,
        columns:[
          {name:'option',label:'Opciónes',field:'option',align:'center'},
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
      imprimir(row){
        this.loading=true
        this.$api.post('printAsistencia',{
          afiliado_id:row.afiliado_id,
        }).then((res) => {
          this.loading=false
          let pago=res.data.data
          console.log(pago)
          let cadena="<style>\
        .titulo1{font-size:10px; text-align: center;}\
        .titulo2{font-size:14px; text-align: center; font-weight: bold;}\
        .titulo3{font-size:10px; text-align: center;}\
        .titulo5{font-size:8px; text-align: center;}\
        .titulo4{font-size:12px; text-align: center; font-weight: bold;}\
        .texto1{font-size:10px; text-align: center; font-weight: normal;}\
        .texto2{font-size:14px; text-align: center; font-weight: normal;}\
        .texto3{font-size:8px; text-align: center; font-weight: normal;}\
        table{width:100%}\
        img{width:70px;height:70px;}\
        </style>\
          <div id='print'>\
          <table>\
          <tr><td style='width:20%'><img src='imagenes/logo.png'></td>\
          <td class='titulo1'  style='width:50%'>SINDICATO MIXTO DE TRANSPORTE<br><span class='titulo2'>26 DE JULIO</span><br><span class='titulo3' FUNDADO EL 26 DE JULIO DE 1970 <br> RESOLUCION SUPREMA 221174</span><br><br><span class='titulo2'> ASISTENCIA</span></td>\
          <td class='titulo1'> FECHA<br>"+pago.fecha +" " +pago.hora+"<br><span class='titulo5'> N° Movil "+pago.afiliado.codigo+" </span><br><span   >No "+pago.id+"</span></td></tr>\
          </table>\
          <table><tr>\
          <td class='col-4 titulo4'>GRUPO<br><span class='texto1'>"+pago.grupo.tipo+"</span></td><td class='col-4 titulo4'>PLACA<br><span class='texto1'>"+pago.vehiculo.placa+"</span></td>\
          </tr></table>\
          <div class='col-12 titulo4'>AFILIADO</div><div class='col-12 texto2'>"+pago.afiliado.nombres +' ' +pago.afiliado.apellidos+"</div>\
          <div class='titulo4'>Muchas gracias por su Asistencia</div>\
          </div><br><br>\
          <table><tr>\
          <td class='texto3'>Secretario de Hacienda<br>VoBo</td><td class='texto3'>Agente de Control</td>\
          </tr></table>\
          </div>"
          document.getElementById('print').innerHTML = cadena
          d.print( document.getElementById('print') )
        })
      },
      eliminar(row){
        this.$q.dialog({
          title: 'Eliminar',
          message: '¿Está seguro de eliminar el registro?',
          cancel: true,
          persistent: true
        }).onOk(() => {
          this.$api.delete(`asistencia/${row.id}`).then((res) => {
            this.$q.notify({
              message: 'Registro eliminado',
              color: 'green-4',
              textColor: 'white',
              icon: 'check'
            })
            this.consultar()
          })
        })
      },
    },
  }
  </script>

  <style scoped>

  </style>

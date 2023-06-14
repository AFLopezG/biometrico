<template>
    <q-page class="q-pa-xs">
        <div class="text-h5" align="center">CANCELACION DE AFILIADOS</div>
      <q-form @submit.prevent="consultar" class="q-gutter-md">
        <div class="row" >
          <div class="col-3"><q-input v-model="ci"  label="Cedula Identidad"  outlined dense required/></div>
          <div class="col-3 flex flex-center"><q-btn color="green" icon="search" label="Buscar"  type="submit"/></div>
        </div>
      </q-form>
    <div class="row" v-if="afiliado.id!=undefined">
      <div class="col-4"><b>CI: </b> {{afiliado.ci}}</div>
      <div class="col-4"><b>EXP: </b> {{afiliado.expedido}}</div>
      <div class="col-4"><b>Nombres: </b> {{afiliado.nombres}}</div>
      <div class="col-4"><b>Apellidos: </b> {{afiliado.apellidos}}</div>
      <div class="col-4"><b>Telefono: </b> {{afiliado.telefono}}</div>
    </div>
    <q-table :rows="afiliado.vehiculos" :columns="columns" dense :rows-per-page-options="[20,50,100,0]" :filter="filter" v-if="afiliado.id!=undefined">
      <template v-slot:top-right>
            <q-input borderless dense debounce="300" v-model="filter" placeholder="Buscar">
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
          </template>
      <template v-slot:body-cell-opcion="props">
        <q-td :props="props" auto-width>
          <q-btn icon="payments" dense color="green" @click="irPago(props.row)" />
    </q-td>
      </template>
    </q-table>




      <div id="print" class="hidden"></div>
    </q-page>
    </template>

    <script>
    import Printd from 'printd'
    import {date} from 'quasar'
    import moment from 'moment'
    import xlsx from "json-as-xlsx"

    export default {
      name: `Pagos`,
      data() {
        return {
          url: process.env.API,
          tipos:[{label:'Nuevo Ingreso',monto:500},{label:'Cuota',monto:20}],
          filterPagos:'',
          pagos: [],
          moment: moment,
          afiliado:{},
          driver:{label:''},
          drivers:[],
          filterDr:[],
          filter:'',
          cog:'SI',
          ci:'',
          titulos:[],
          resumen:[],
          tipo:{},
          columns:[
            {name:'opcion',label:'OPCION',field:'opcion'},
            {name:'placa',label:'placa',field:'placa'},
            {name:'codmovil',label:'codmovil',field:'codmovil'},
            {name:'codcolor',label:'codcolor',field:'codcolor'},
            {name:'tipo',label:'tipo',field:'tipo'},
            {name:'modelo',label:'modelo',field:'modelo'},
            {name:'marca',label:'marca',field:'marca'},
            {name:'grupo',label:'grupo',field:row=>row.grupo.tipo},
          ]
        };
      },
      created() {
      },
      methods:{
        filterFn (val, update) {
          if (val === '') {
            update(() => {
              this.drivers = this.filterDr

              // here you have access to "ref" which
              // is the Vue reference of the QSelect
            })
            return
          }

          update(() => {
            const needle = val.toLowerCase()
            this.drivers = this.filterAf.filter(v => v.label.toLowerCase().indexOf(needle) > -1)
          })
        },
        consultar(){
            this.afiliado={}
            this.$api.get('searchAfiliado/'+this.ci).then((res) => {
                this.afiliado=res.data
            })},
      irPago(vehi){
            vehi.vehiculo_id=vehi.id
              console.log(vehi)
              this.$q.dialog({
            title: 'REALIZAR EL PAGO',
            message: 'Esta Seguro de realizar  ?',

            cancel: true,
            persistent: false
          }).onOk(()=>{
              this.$api.post('pago',vehi).then((res) => {
               // console.log(res.data)
               // return false
                this.$q.notify({
                message: 'Pago Realizado Correctamente',
                icon:"info",
                color: 'green'
              })
              }).catch((error)=>{
          this.$q.notify({
           message: error.message,
           icon:"info",
           color: 'red'
         })})
          }).onCancel(() => {
            // console.log('>>>> Cancel')
          }).onDismiss(() => {
            // console.log('I am triggered on both OK and Cancel')
          })
        }
            ,
        printPago(pago){
      console.log(pago)
          const d = new Printd()
          let cadena="<style>\
        .titulo1{font-size:10px; text-align: center;}\
        .titulo2{font-size:14px; text-align: center; font-weight: bold;}\
        .titulo3{font-size:10px; text-align: center;}\
        .titulo5{font-size:15px; text-align: center;}\
        .titulo4{font-size:12px; text-align: center; font-weight: bold;}\
        .texto4{font-size:20px; text-align: center; font-weight: normal;}\
        .texto5{font-size:28px; text-align: center; font-weight: normal;}\
        .texto1{font-size:10px; text-align: center; font-weight: normal;}\
        .texto2{font-size:14px; text-align: center; font-weight: normal;}\
        .texto3{font-size:8px; text-align: center; font-weight: normal;}\
        table{width:100%}\
        img{width:70px;height:70px;}\
        </style>\
          <div id='print'><table>\
          <tr><td style='width:20%'><img src='images/logo.png'></td>\
          <td class='titulo1' >SINDICATO MIXTO DE TRANSPORTE<br><span class='titulo2'>26 DE JULIO</span><br><span class='titulo3' FUNDADO EL 26 DE JULIO DE 1970 <br> RESOLUCION SUPREMA 27465</span><br><br><span class='titulo2'> HOJA DE APORTES</span></td>\
          <td class='titulo1'> FECHA<br>"+pago.fecha +"<br><span   >No "+pago.id+"</span></td></tr>\
          </table>\
          <table><tr>\
          <td class='col-4 titulo4'>TOTAL Bs<br><span class='texto1'>"+(parseFloat(pago.monto)).toFixed(2)+"</span></td>\
          </tr></table>\
          <div class='col-12 titulo4'>CHOFER</div><div class='col-12 texto2'>"+pago.driver.nombres +"</div>\
          </div><br><br>\
          <table><tr>\
          <td class='texto3'>Secretario de Hacienda<br>VoBo</td><td class='texto3'>Agente de Control</td>\
          </tr></table>\
          </div>"
          document.getElementById('print').innerHTML = cadena
          d.print( document.getElementById('print') )
          },




      },
      computed:{

      }
    }
    </script>

    <style scoped>

    </style>

<template>
    <q-page class="q-pa-xs">
        <div class="text-h5" align="center">CANCELACION DE CHOFERES</div>
      <q-form @submit="consultar" class="q-gutter-md">
        <div class="row">
          <div class="col-3"><q-input v-model="ini" type="date" label="Fecha Desde" outlined dense required/></div>
          <div class="col-3"><q-input v-model="fin" type="date" label="Fecha Hast"  outlined dense required/></div>
          <div class="col-3 flex flex-center"><q-btn color="green" icon="search" label="Buscar"  type="submit"/></div>
        </div>
      </q-form>
        <div class="row">
            <div class="col-3"><q-select  outlined v-model="tipo" :options="tipos" label="Motivo" dense/></div>
            <div class="col-3"><q-select  outlined v-model="driver" :options="drivers" label="Chofer" use-input input-debounce="0" @filter="filterFn" dense/></div>
            <div class="col-3"> <q-btn color="green"  label="Registrar" dense @click="registrar"/>    </div>
        </div>
    <q-table :rows="pagos" :columns="columns" dense :rows-per-page-options="[20,50,100,0]" :filter="filter">
      <template v-slot:top-right>
            <q-input borderless dense debounce="300" v-model="filter" placeholder="Buscar">
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
          </template>
      <template v-slot:body-cell-opcion="props">
        <q-td :props="props" auto-width>
          <q-btn icon="print" dense color="info" @click="printPago(props.row)" v-if="props.row.anulado==false"/>
      <q-btn icon="delete" dense color="red" @click="anularPago(props.row)" v-if="props.row.anulado==false"/>
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
          tipos:[{label:'Nuevo Ingreso',monto:500},{label:'Cuota',monto:23}],
          filterPagos:'',
          pagos: [],
          moment: moment,
          semanal:[],
          driver:{label:''},
          drivers:[],
          filterDr:[],
          filter:'',
          cog:'SI',
          titulos:[],
          resumen:[],
          tipo:{},
          ini:moment().day("Monday").format("YYYY-MM-DD"),
          fin:moment().day("Saturday").add(1, 'days').format("YYYY-MM-DD"),
          columns:[
            {name:'opcion',label:'OPCION',field:'opcion'},
            {name:'id',label:'ID',field:'id'},
            {name:'monto',label:'MONTO',field:'monto'},
            {name:'fecha',label:'FECHA',field:'fecha'},
            {name:'motivo',label:'motivo',field:'motivo'},
            {name:'driver',label:'driver',field:row=>row.driver.nombres},
          ]
        };
      },
      created() {
        this.tipo=this.tipos[1]
        this.consultar()
        this.listadodrivers()
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

            this.$api.post('consultachofer',{ini:this.ini,fin:this.fin}).then((res) => {
                this.pagos=res.data
            })},
            listadodrivers(){
                this.drivers=[]
          this.$api.get('listDriver').then((response) => {
            response.data.forEach(r => {
                r.label=r.nombres
                this.drivers.push(r)
            })
            this.filterDr=this.drivers
          })

        },
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

        registrar(){
            if(this.driver.id == undefined)
            return false
            this.$api.post('pagoextra',{monto:this.tipo.monto,motivo:this.tipo.label,fecha:moment().format("YYYY-MM-DD"),driver_id:this.driver.id}).then((res) => {
                this.printPago(res.data)
                this.driver={label:''}
                this.tipo=this.tipos[1]
                this.consultar()
            })

        },

        anularPago(pag){
          this.$q.dialog({
            title: 'ANULAR PAGO',
            message: 'Cual es el motivo ?',
            prompt: {
              model: '',
              type: 'text' // optional
            },
            cancel: true,
            persistent: false
          }).onOk(data => {
            //console.log(data)
            if(data=='')
              return false
            this.$api.post('anularExtra' ,{pago:pag.id,motivo:data}).then(res=>{
              console.log(res.data)
              this.consultar()
            })
          }).onCancel(() => {
            // console.log('>>>> Cancel')
          }).onDismiss(() => {
            // console.log('I am triggered on both OK and Cancel')
          })
        }
      },
      computed:{

      }
    }
    </script>

    <style scoped>

    </style>

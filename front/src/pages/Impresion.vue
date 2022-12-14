<template>
<q-page>
<q-table :rows="pagos" :columns="columns" dense :rows-per-page-options="[20,50,100,0]" :filter="filter">
  <template v-slot:top-right>
    <q-input outlined dense debounce="300" v-model="filter" placeholder="Buscar">
      <template v-slot:append>
        <q-icon name="search" />
      </template>
    </q-input>
  </template>
  <template v-slot:body-cell-opcion="props">
    <q-td :props="props" auto-width>
      <q-btn icon="delete_outline" dense size="10px" color="red" @click="anularPago(props.row)" v-if="props.row.anulado=='' || props.row.anulado==null"/>
    </q-td>
  </template>
  <template v-slot:body-cell-multa="props">
    <q-td :props="props" auto-width>
      <q-chip text-color="white" dense :color="props.row.multa==0?'green':'red'" :label="props.row.multa==0?'No':'Si'" />
    </q-td>
  </template>
  <template v-slot:body-cell-anulado="props">
    <q-td :props="props" auto-width>
      <q-chip text-color="white" dense :color="props.row.anulado==0?'green':'red'" :label="props.row.anulado==0?'No':'Si'" />
    </q-td>
  </template>
</q-table>

</q-page>
</template>

<script>
import Printd from 'printd'
import {useCounterStore} from "src/stores/globalStore";
import { io } from "socket.io-client";

export default {
  name: `Impresion`,
  data() {
    return {
      pagos: [],
      filter:'',
      store:useCounterStore(),
      socket: io(process.env.API_SOCKET),
      columns:[
        {name:'opcion',label:'Opciones',field:'opcion'},
        {name:'id',label:'id',field:'id'},
        {name:'monto',label:'Monto',field:row => row.monto+' Bs'},
        {name:'multa',label:'Multa',field:'multa'},
        {name:'fecha',label:'Fecha',field:'fecha'},
        {name:'hora',label:'Hora',field:'hora'},
        {name:'grupo',label:'Grupo',field:row=>row.grupo.tipo},
        {name:'afiliado',label:'Afiliado',field:row=>row.afiliado.nombres+' '+row.afiliado.apellidos},
        {name:'vehiculo',label:'Vehiculo',field:row=>row.vehiculo.placa},
        {name:'anulado',label:'Anulado',field:'anulado'},
        {name:'motivo',label:'Motivo',field:'motivo'},
      ]
    };
  },
  mounted() {
    if (this.store.socket==false) {
      this.store.socket = true
      this.socket.on('chat message', (data) => {
        console.log(data[0])
        this.pagos.unshift(data[0])
        let pago= data[0]
        const d = new Printd()
        pago.impreso = 1
        let obs=pago.multa>0?'TIENE MULTA POR RETRASO':''
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
          <td class='titulo1'  style='width:50%'>SINDICATO MIXTO DE TRANSPORTE<br><span class='titulo2'>26 DE JULIO</span><br><span class='titulo3' FUNDADO EL 26 DE JULIO DE 1970 <br> RESOLUCION SUPREMA 221174</span><br><br><span class='titulo2'> HOJA DE APORTES</span></td>\
          <td class='titulo1'> FECHA<br>"+pago.fecha +" " +pago.hora+"<br><span class='titulo5'> Cel:73039359</span><br><span   >No "+pago.id+"</span></td></tr>\
          </table>\
          <table><tr>\
          <td class='col-4 titulo4'>GRUPO<br><span class='texto1'>"+pago.grupo.tipo+"</span></td><td class='col-4 titulo4'>PLACA<br><span class='texto1'>"+pago.vehiculo.placa+"</span></td><td class='col-4 titulo4'>TOTAL Bs<br><span class='texto1'>"+(parseFloat(pago.monto)).toFixed(2)+"</span></td>\
          </tr></table>\
          <div class='col-12 titulo4'>AFILIADO</div><div class='col-12 texto2'>"+pago.afiliado.nombres +' ' +pago.afiliado.apellidos+"</div>\
          <div class='titulo4'>OBSERVACION: <span class='texto1'>"+obs+"</span></div>\
          </div><br><br>\
          <table><tr>\
          <td class='texto3'>Secretario de Hacienda<br>VoBo</td><td class='texto3'>Agente de Control</td>\
          </tr></table>\
          </div>"
        document.getElementById('print').innerHTML = cadena
        d.print( document.getElementById('print') )

      });
    }
  },
  created() {
    this.pagoGet()
    // setInterval(() => {
    //   this.$api.get('pago').then((response) => {
    //     this.pagos = response.data;
    //    // const d = new Printd()
    //
    //     this.pagos.forEach((pago) => {
    //       if (pago.impreso==0) {
    //         const d = new Printd()
    //         this.$api.put('pago/' + pago.id,{impreso:1})
    //         pago.impreso = 1
    //         let obs=pago.multa>0?'TIENE MULTA POR RETRASO':''
    //         let cadena="<style>\
    //         .titulo1{font-size:10px; text-align: center;}\
    //         .titulo2{font-size:14px; text-align: center; font-weight: bold;}\
    //         .titulo3{font-size:10px; text-align: center;}\
    //         .titulo5{font-size:8px; text-align: center;}\
    //         .titulo4{font-size:12px; text-align: center; font-weight: bold;}\
    //         .texto1{font-size:10px; text-align: center; font-weight: normal;}\
    //         .texto2{font-size:14px; text-align: center; font-weight: normal;}\
    //         .texto3{font-size:8px; text-align: center; font-weight: normal;}\
    //         table{width:100%}\
    //         img{width:70px;height:70px;}\
    //         </style>\
    //           <div id='print'>\
    //           <table>\
    //           <tr><td style='width:20%'><img src='imagenes/logo.png'></td>\
    //           <td class='titulo1'  style='width:50%'>SINDICATO MIXTO DE TRANSPORTE<br><span class='titulo2'>26 DE JULIO</span><br><span class='titulo3' FUNDADO EL 26 DE JULIO DE 1970 <br> RESOLUCION SUPREMA 221174</span><br><br><span class='titulo2'> HOJA DE APORTES</span></td>\
    //           <td class='titulo1'> FECHA<br>"+pago.fecha +" " +pago.hora+"<br><span class='titulo5'> Cel:73039359</span><br><span   >No "+pago.id+"</span></td></tr>\
    //           </table>\
    //           <table><tr>\
    //           <td class='col-4 titulo4'>GRUPO<br><span class='texto1'>"+pago.grupo.tipo+"</span></td><td class='col-4 titulo4'>PLACA<br><span class='texto1'>"+pago.vehiculo.placa+"</span></td><td class='col-4 titulo4'>TOTAL Bs<br><span class='texto1'>"+(parseFloat(pago.monto) + parseFloat(pago.multa)).toFixed(2)+"</span></td>\
    //           </tr></table>\
    //           <div class='col-12 titulo4'>AFILIADO</div><div class='col-12 texto2'>"+pago.afiliado.nombres +' ' +pago.afiliado.apellidos+"</div>\
    //           <div class='titulo4'>OBSERVACION: <span class='texto1'>"+obs+"</span></div>\
    //           </div><br><br>\
    //           <table><tr>\
    //           <td class='texto3'>Secretario de Hacienda<br>VoBo</td><td class='texto3'>Agente de Control</td>\
    //           </tr></table>\
    //           </div>"
    //         document.getElementById('print').innerHTML = cadena
    //         d.print( document.getElementById('print') )
    //       }
    //     });
    //   });
    // }, 2000);
  },

  methods:{
    pagoGet(){
      this.$api.get('pago').then((response) => {
        this.pagos = response.data
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
        if(data==''){
          this.$q.notify({
            message: 'Debe ingresar un motivo',
            color: 'negative',
            position: 'top',
            timeout: 2000
          })
          return false
        }
        this.$api.post('anularPago' ,{pago:pag.id,motivo:data}).then(res=>{
          this.$q.notify({
            message: 'Pago anulado',
            color: 'positive',
            position: 'top',
            timeout: 2000
          })
          this.pagoGet()
        })
      }).onCancel(() => {
        // console.log('>>>> Cancel')
      }).onDismiss(() => {
        // console.log('I am triggered on both OK and Cancel')
      })
    }
  },
}
</script>

<style scoped>

</style>

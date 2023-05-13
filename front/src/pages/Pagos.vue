<template>
<q-page class="q-pa-xs">
  <q-form @submit="consultar" class="q-gutter-md">
    <div class="row">
      <div class="col-3"><q-input v-model="ini" type="date" label="Fecha Desde" outlined dense required/></div>
      <div class="col-3"><q-input v-model="fin" type="date" label="Fecha Hast"  outlined dense required/></div>
      <div class="col-3"><q-toggle v-model="cog" :label="cog+' COBRAR MULTA'" true-value="SI" false-value="NO" @click="cambiarCog"/>
      </div>
      <div class="col-3 flex flex-center"><q-btn color="green" icon="search" label="Buscar"  type="submit"/></div>
    </div>
  </q-form>
  <div class="row">
    <div class="col-3 flex flex-center"><q-btn target="_blank" type="a" :href="`${url}reportlistPago/1/${ini}/${fin}`" color="primary" icon="print" label="Grupo A y B"/></div>
    <div class="col-3 flex flex-center"><q-btn target="_blank" type="a" :href="`${url}reportlistPago/2/${ini}/${fin}`" color="secondary" icon="print" label="GRUPO C"/></div>
    <div class="col-3 flex flex-center"><q-btn target="_blank" type="a" :href="`${url}reportlistPago/3/${ini}/${fin}`" color="green" icon="print" label="GRUPO D"/></div>
    <div class="col-3 flex flex-center"><q-btn target="_blank" type="a" :href="`${url}reportlistPago/4/${ini}/${fin}`" color="info" icon="print" label="GRUPO E"/></div>
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
  <q-card>
    <q-card-section>
      <div class="row">
      <div class="col-6 text-h6">Total: {{total}} Bs</div>
      <div class="col-6"> <q-btn color="info" icon="print" @click="printPagoResumen"/>
      </div>
      </div>
      <q-table  :rows="resumen" row-key="name" />

    </q-card-section>
    <q-card-section>
      <div class="row">
        <div class="col-3 flex flex-center"><q-btn target="_blank" type="a" :href="`${url}reportlist/1/${ini}/${fin}`" color="primary" icon="print" label="Grupo A y B" no-caps/></div>
        <div class="col-3 flex flex-center"><q-btn target="_blank" type="a" :href="`${url}reportlist/2/${ini}/${fin}`" color="secondary" icon="print" label="Grupo C" no-caps/></div>
        <div class="col-3 flex flex-center"><q-btn target="_blank" type="a" :href="`${url}reportlist/3/${ini}/${fin}`" color="green" icon="print" label="Grupo D" no-caps/></div>
        <div class="col-3 flex flex-center"><q-btn target="_blank" type="a" :href="`${url}reportlist/4/${ini}/${fin}`" color="info" icon="print" label="Grupo E" no-caps/></div>
      </div>
    </q-card-section>
  </q-card>
<div class="col-4"> <q-btn color="info" icon="download" label="Export Excel" @click="exportTable"/>
</div>
<q-table
  title="Pagos semanales"
  :rows="semanal"
  row-key="name"
  :filter="filterPagos"
>
  <template v-slot:top-right>
    <q-input borderless dense debounce="300" v-model="filterPagos" placeholder="Buscar">
      <template v-slot:append>
        <q-icon name="search" />
      </template>
    </q-input>
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
      filterPagos:'',
      pagos: [],
      moment: moment,
      semanal:[],
      filter:'',
      cog:'SI',
      titulos:[],
      resumen:[],
      ini:moment().day("Monday").format("YYYY-MM-DD"),
      fin:moment().day("Saturday").add(1, 'days').format("YYYY-MM-DD"),
      columns:[
        {name:'opcion',label:'OPCION',field:'opcion'},
        {name:'id',label:'ID',field:'id'},
        {name:'monto',label:'MONTO',field:'monto'},
        {name:'multa',label:'MULTA',field:'multa'},
        {name:'fecha',label:'FECHA',field:'fecha'},
        {name:'hora',label:'HORA',field:'hora'},
        {name:'grupo',label:'GRUPO',field:row=>row.grupo.tipo},
        {name:'afiliado',label:'AFILIADO',field:row=>row.afiliado.nombres+' '+row.afiliado.apellidos},
        {name:'vehiculo',label:'VEHICULO',field:row=>row.vehiculo.placa},
        {name:'estado',label:'ESTADO',field:'estado'},
        {name:'anulado',label:'ANULADO',field:'motivo'},
      ]
    };
  },
  created() {
  //   console.log(moment.weekdays());
  //   console.log(moment().day("Monday").format("YYYY-MM-DD"));
  //   console.log(moment().day("Saturday").add(1, 'days').format("YYYY-MM-DD"));
    this.consultar()
    this.recuperaCog()
  },
  methods:{

    recuperaCog(){
      this.$api.get(`cog` ).then(res=>{
        if(res.data.state=='active')
        this.cog='SI'
        else
        this.cog='NO'
      })

    },
    cambiarCog(){
      let valor=''
      if(this.cog=='SI')
        valor='active'
      else
        valor='inactive'
      this.$api.post(`modcog`,{'estado':valor}).then(res=>{

      })

    },
    printPagoResumen(){
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
          <div id='print'>\
          <div class='titulo2'>PAGOS POR GRUPO "+this.ini+" al " +this.fin+" </div><br>\
          <table>\
           <tr><th>GRUPO</th><th>SINDICAL</th><th>DECANO</th><th>DEPORTIVO</th><th>PROACCIDENTE</th><th>TOTAL</th></tr>"
           this.resumen.forEach(r => {
            cadena+="<tr><td>"+r.grupo+"</td><td>"+r.sindical+"</td><td>"+r.decano+"</td><td>"+r.deportivo+"</td><td>"+r.proaccidente+"</td><td>"+r.total+"</td></tr>"
           });
          //cadena+="<tr><th>TOTALES</th><th>"+this.totalsindical+"</th><th>"+this.totaldecano+"</th><th>"+this.totaldeportivo+"</th><th>"+this.totalproaccidente+"</th><th>"+this.total+"</th></tr>\
          cadena+="</table></div>"
          document.getElementById('print').innerHTML = cadena
          d.print( document.getElementById('print') )
    },
    imprimir(grupo_id){
      this.$api.post(`pagoConsulta`,{ini:this.ini,fin:this.fin,grupo_id:grupo_id}).then(res=>{
        console.log(res.data)
        // this.$q.notify({
        //   message: 'Imprimiendo',
        //   color: 'green-4',
        //   textColor: 'white',
        //   icon: 'done'
        // })
        // Printd.print(res.data)
      })
    },
    consultar(){
      this.$api.post('consultapago',{ini:this.ini,fin:this.fin}).then((res) => {
        this.pagos=res.data
        this.$api.post('resumenPago',{ini:this.ini,fin:this.fin}).then((res2) => {
          this.resumen=res2.data
          this.resumen.push({grupo:'TOTALES','sindical':this.totalsindical,'decano':this.totaldecano,'deportivo':this.totaldeportivo,'proaccidente':this.totalproaccidente,'total':this.total});
        })

      })
      this.consultimp()
    },
    consultimp(){
      this.titulos=[]
      this.$api.post('datoimp',{ini:this.ini,fin:this.fin}).then((res) => {
      console.log(res.data)
      this.semanal=res.data
      for (let j in this.semanal[0]) {
       // const element = array[index];
        this.titulos.push({label:j,value:j})

      }

      console.log(this.titulos)

    })

    },
    exportTable () {
      if(this.titulos.length==0)
      return false
let datacaja = [
  {
    sheet: "reporte",
    columns: this.titulos,
    content: this.semanal
  },

    ]

    let settings = {
      fileName: "Pagos", // Name of the resulting spreadsheet
      extraLength: 20, // A bigger number means that columns will be wider
      writeOptions: {}, // Style options from https://github.com/SheetJS/sheetjs#writing-options
    }

    xlsx(datacaja, settings) // Will download the excel file
      },
    printPago(pago){
      console.log(pago)
          const d = new Printd()
          let obs=pago.multa>0?'TIENE MULTA POR RETRASO':''
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
          <div id='print'>\
          <table><tr><td class='texto5'>N° Movil "+pago.vehiculo.codmovil+"</td><td class='texto4'>"+pago.grupo.tipo+"</td></tr><table/>\
          <table>\
          <tr><td style='width:20%'><img src='imagenes/logo.png'></td>\
          <td class='titulo1'  style='width:50%'>SINDICATO MIXTO DE TRANSPORTE<br><span class='titulo2'>26 DE JULIO</span><br><span class='titulo3' FUNDADO EL 26 DE JULIO DE 1970 <br> RESOLUCION SUPREMA 27465</span><br><br><span class='titulo2'> HOJA DE APORTES</span></td>\
          <td class='titulo1'> FECHA<br>"+pago.fecha +" " +pago.hora+"<br><span class='titulo5'> N° Movil "+pago.vehiculo.codmovil+" </span><br><span   >No "+pago.id+"</span></td></tr>\
          </table>\
          <table><tr>\
          <td class='col-4 titulo4'>GRUPO<br><span class='texto1'>"+pago.grupo.tipo+"</span></td><td class='col-4 titulo4'>PLACA<br><span class='texto5'>"+pago.vehiculo.placa+"</span></td><td class='col-4 titulo4'>TOTAL Bs<br><span class='texto1'>"+(parseFloat(pago.monto)).toFixed(2)+"</span></td>\
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
        this.$api.post('anularPago' ,{pago:pag.id,motivo:data}).then(res=>{
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
    total(){
      return this.pagos.reduce((a,b)=>a+parseFloat(b.monto),0)
    },
    totalMulta(){
      return this.pagos.reduce((a,b)=>a+parseFloat(b.multa),0)
    },
    totalSemanal(){
      return this.semanal.reduce((a,b)=>a+parseFloat(b.monto),0)
    },
    totalMultaSemanal(){
      return this.semanal.reduce((a,b)=>a+parseFloat(b.multa),0)
    },
    totalsindical(){
      return this.resumen.reduce((a,b)=>a+parseFloat(b.sindical),0)
    },
    totaldecano(){
      return this.resumen.reduce((a,b)=>a+parseFloat(b.decano),0)
    },
    totaldeportivo(){
      return this.resumen.reduce((a,b)=>a+parseFloat(b.deportivo),0)
    },
    totalproaccidente(){
      return this.resumen.reduce((a,b)=>a+parseFloat(b.proaccidente),0)
    },
  }
}
</script>

<style scoped>

</style>

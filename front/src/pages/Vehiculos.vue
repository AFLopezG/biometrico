<template>
<q-page>
  <div class="col-12">
    <div class="text-h5 text-center">LISTA DE VEHICULOS</div>
    <div class="row">
      <div class="col-4"><q-select v-model="datocolor" :options="colores" label="CodMovil"  outlined dense/></div>
      <div class="col-4"><q-btn color="green" icon="print" label="EXCEL" @click="ExportExcel" /></div>
      <div class="col-4"><q-btn color="red" icon="print" label="PDF" @click="ExportPDF" /></div>
    </div>
    <div class="row">
      <div class="col-4"><q-select v-model="grupo" :options="grupos" label="CodMovil"  outlined dense/></div>
      <div class="col-4"><q-btn color="green" icon="print" label="EXCEL" @click="ExportExcel2" /></div>
      <div class="col-4"><q-btn color="red" icon="print" label="PDF" @click="ExportPDF2" /></div>
    </div>
    <q-table :rows="vehiculos" :columns="columns" :filter="filter">
      <template v-slot:body-cell-opcion="props">
          <q-td key="opcion" :props="props">
            <q-btn dense icon="edit" color="yellow" @click="upVehiculo(props.row)" />
            <q-btn dense icon="delete" color="red" @click="EliminarVehiculo(props.row)"/>
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
          <div class="text-h6">REGISTRO DE VEHICULO</div>
        </q-card-section>
        <q-form @submit.prevent="onSubmit"  class="q-gutter-md">

        <q-card-section class="q-pt-none">
          <div class="row">

          <div class="col-6"><q-input v-model="vehiculo.placa" label="PLACA" outlined dense required/></div>
          <div class="col-6"><q-input v-model="vehiculo.codmovil" label="COD MOVIL" outlined dense required/></div>
          <div class="col-6"><q-select v-model="vehiculo.tipo"  label="TIPO" :options="tipovehiculo" dense outlined/></div>
          <div class="col-6"><q-input v-model="vehiculo.modelo" label="MODELO" outlined dense required/></div>
            <div class="col-6"><q-select v-model="vehiculo.marca" label="MARCA" :options="marca" outlined dense required/></div>
            <div class="col-6"><q-input v-model="vehiculo.color" label="COLOR" outlined dense required/></div>
            <div class="col-6"><q-select v-model="vehiculo.codcolor" label="COD COLOR" :options="colores" outlined dense required/></div>
            <div class="col-6"><q-input v-model="vehiculo.capacidad" label="CAPACIDAD" type="number" outlined dense required/></div>
            <div class="col-6"><q-select v-model="vehiculo.afiliado" :options="afiliados" label="Afiliado" dense outlined @filter="filterFn"  use-input input-debounce="0"/></div>
            <div class="col-6"><q-select v-model="vehiculo.grupo" :options="grupos" label="Grupo" dense outlined /></div>
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
        <q-form @submit.prevent="updateVehiculo"  class="q-gutter-md">

          <q-card-section class="q-pt-none">
            <div class="row">

              <div class="col-6"><q-input v-model="vehiculo2.placa" label="PLACA" outlined dense required /></div>
              <div class="col-6"><q-input v-model="vehiculo2.codmovil" label="COD MOVIL" outlined dense required /></div>
              <div class="col-6"><q-select v-model="vehiculo2.tipo" :options="tipovehiculo"  label="TIPO" dense outlined/></div>
              <div class="col-6"><q-input v-model="vehiculo2.modelo" label="MODELO" outlined dense required/></div>
              <div class="col-6"><q-select v-model="vehiculo2.marca" label="MARCA" :options="marca" outlined dense required/></div>
              <div class="col-6"><q-input v-model="vehiculo2.color" label="COLOR" outlined dense required/></div>
              <div class="col-6"><q-select v-model="vehiculo2.codcolor" label="COD COLOR" :options="colores" outlined dense required/></div>
              <div class="col-6"><q-input v-model="vehiculo2.capacidad" label="CAPACIDAD" type="number" outlined dense required/></div>
              <div class="col-6"><q-select v-model="vehiculo2.afiliado" :options="afiliados" label="Afiliado" dense outlined @filter="filterFn"  use-input input-debounce="0"/></div>
              <div class="col-6"><q-select v-model="vehiculo2.grupo" :options="grupos" label="Grupo" dense outlined /></div>
            </div>
          </q-card-section>

          <q-card-actions align="right" class="text-primary">
            <q-btn flat color="red" label="Cancel" v-close-popup icon="cancel"/>
            <q-btn flat color="green" label="Modificar" icon="pencil" type="submit" :loading="loading"/>

          </q-card-actions>
        </q-form>
      </q-card>
    </q-dialog>
    <div id="print" class="hidden"></div>

  </div>
</q-page>

</template>

<script>
import {date} from 'quasar'
import xlsx from "json-as-xlsx"
import Printd from 'printd'

export default {
  name: `Vehiculos.vue`,
  data() {
    return {
      loading:false,
      dialogRegistro:false,
      dialogModificar:false,
      tipovehiculo:['MINIBUS','VAGONETA','FURGON'],
      marca:['HYUNDAI','MERCEDEZ BENZ','KING LONG','FOTON','TOYOTA','MITSUBISHI','JAC'],
      colores:['NINGUNO','BLANCO','CELESTE','ROJO','AZUL'],
      vehiculo:{},
      vehiculo2:{},
      vehiculos:[],
      afiliados:[],
      filterAf:[],
      grupos:[],
      datoprint:[],
      datocolor:'NINGUNO',
      printgrupo:[],
      printgrupo2:[],
      filter:'',
      columns:[
        {name:'opcion',label:'OPCION'},
        {name:'placa',label:'PLACA',field:'placa'},
        {name:'codmovil',label:'COD MOVIL',field:'codmovil'},
        {name:'codcolor',label:'COD COLOR',field:'codcolor'},
        {name:'tipo',label:'TIPO',field:'tipo'},
        {name:'modelo',label:'MODELO',field:'modelo'},
        {name:'marca',label:'MARCA',field:'marca'},
        {name:'color',label:'COLOR',field:'color'},
        {name:'capacidad',label:'CAPACIDAD',field:'capacidad'},
        {name:'afiliado',label:'AFILIADO',field:row=>row.afiliado.nombres+' '+row.afiliado.apellidos},
        {name:'grupo',label:'GRUPO',field:row=>row.grupo.tipo},
      ]
    };
  },
  created() {
    this.listadoAfiliado()
    this.listadoGrupos()
    this.listadoVehiculos()
    },
  methods:{
    ExportExcel(){
      if(this.datocolor=='NINGUNO' || this.datocolor=='' && this.datocolor==undefined)
      return false
      this.datoprint=[]
      let i=0
      this.vehiculos.forEach(x => {
        if(x.codcolor==this.datocolor && this.datocolor!='NINGUNO'){
          i++
          x.numero=i
          this.datoprint.push(x)
        }
      });
      console.log(this.datoprint)

let datacaja = [
  {
    sheet: "Lista"+this.datocolor,
    columns: [
        {label:'NUM',value:'numero'},
        {label:'COD MOVIL',value:'codmovil'},
        {label:'AFILIADO',value:row=>row.afiliado.nombres+' '+row.afiliado.apellidos},
        {label:'TELEFONO',value:row=>row.afiliado.telefono},
        {label:'PLACA',value:'placa'},
    ],
    content: this.datoprint
  },

    ]

    let settings = {
      fileName: "Listado", // Name of the resulting spreadsheet
      extraLength: 7, // A bigger number means that columns will be wider
      writeOptions: {}, // Style options from https://github.com/SheetJS/sheetjs#writing-options
    }

    xlsx(datacaja, settings) // Will download the excel file
    },
    ExportExcel2(){
      if(this.grupo.id == '' && this.grupo.id==undefined)
      return false
      this.printgrupo=[]
      this.printgrupo2=[]
      let i=0
      let j=0
      this.vehiculos.forEach(x => {
        if(x.grupo.tipo==this.grupo.tipo ){
          if(x.afiliado.estado=='ACTIVO')
          {i++
          x.numero=i          
          this.printgrupo.push(x)}
          else
          {j++
          x.numero=j
          this.printgrupo2.push(x)}
        }
      });
      console.log(this.printgrupo)

let datacaja = [
  {
    sheet: "Lista Activo"+this.grupo.tipo,
    columns: [
        {label:'NUM',value:'numero'},
        {label:'COD MOVIL',value:'codmovil'},
        {label:'AFILIADO',value:row=>row.afiliado.nombres+' '+row.afiliado.apellidos},
        {label:'TELEFONO',value:row=>row.afiliado.telefono},
        {label:'PLACA',value:'placa'},
    ],
    content: this.printgrupo
  },
  {
    sheet: "Lista Pasivo"+this.grupo.tipo,
    columns: [
        {label:'NUM',value:'numero'},
        {label:'COD MOVIL',value:'codmovil'},
        {label:'AFILIADO',value:row=>row.afiliado.nombres+' '+row.afiliado.apellidos},
        {label:'TELEFONO',value:row=>row.afiliado.telefono},
        {label:'PLACA',value:'placa'},
    ],
    content: this.printgrupo2
  },

    ]

    let settings = {
      fileName: "ListadoGrupo", // Name of the resulting spreadsheet
      extraLength: 7, // A bigger number means that columns will be wider
      writeOptions: {}, // Style options from https://github.com/SheetJS/sheetjs#writing-options
    }

    xlsx(datacaja, settings) // Will download the excel file
    },
    ExportPDF(){
      if(this.datocolor=='NINGUNO' || this.datocolor=='' && this.datocolor==undefined)
      return false
      this.datoprint=[]
      let i=0
      this.vehiculos.forEach(x => {
        if(x.codcolor==this.datocolor && this.datocolor!='NINGUNO'){
          i++
          x.numero=i
          this.datoprint.push(x)
        }
      })
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
        table{width:100%;border-collapse: collapse;}\
        table, th, td { border: 1px solid;}\
        img{width:70px;height:70px;}\
        </style>\
          <div id='print'>\
          <div class='titulo2'>GRUPO "+this.datocolor+"</div><br>\
          <table><tr><th>Num</th><th>Cod Vehiculo</th><th>Nombre Completo</th><th>Telefono</th><th>Placa</th></tr>"
            this.datoprint.forEach(x => {
              cadena+="<tr><td>"+x.numero+"</td><td>"+x.codmovil+"</td><td>"+x.afiliado.nombres+' '+x.afiliado.apellidos+"</td><td>"+x.afiliado.telefono+"</td><td>"+x.placa+"</td></tr>"
            });
          cadena+="</table>\
          </div>"
          document.getElementById('print').innerHTML = cadena
          d.print( document.getElementById('print') )
      //console.log(this.datoprint)
    },
    ExportPDF2(){
      if(this.grupo.id =='' && this.grupo.id==undefined)
      return false
      this.printgrupo=[]
      let i=0
      this.vehiculos.forEach(x => {
        if(x.grupo.tipo==this.grupo.tipo){
          i++
          x.numero=i
          this.printgrupo.push(x)
        }
      })
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
        table{width:100%;border-collapse: collapse;}\
        table, th, td { border: 1px solid;}\
        img{width:70px;height:70px;}\
        </style>\
          <div id='print'>\
          <div class='titulo2'>GRUPO "+this.grupo.tipo+"</div><br>\
          <table><tr><th>Num</th><th>Cod Vehiculo</th><th>Nombre Completo</th><th>Telefono</th><th>Placa</th></tr>"
            this.printgrupo.forEach(x => {
              cadena+="<tr><td>"+x.numero+"</td><td>"+x.codmovil+"</td><td>"+x.afiliado.nombres+' '+x.afiliado.apellidos+"</td><td>"+x.afiliado.telefono+"</td><td>"+x.placa+"</td></tr>"
            });
          cadena+="</table>\
          </div>"
          document.getElementById('print').innerHTML = cadena
          d.print( document.getElementById('print') )
      //console.log(this.datoprint)
    },
    filterFn (val, update) {
      if (val === '') {
        update(() => {
          this.afiliados = this.filterAf

          // here you have access to "ref" which
          // is the Vue reference of the QSelect
        })
        return
      }

      update(() => {
        const needle = val.toLowerCase()
        this.afiliados = this.filterAf.filter(v => v.label.toLowerCase().indexOf(needle) > -1)
      })
    },
    listreg(){
      this.vehiculo={};
      this.dialogRegistro=true
    },
      listadoAfiliado(){
        this.afiliados=[]
        this.$api.get('afiliado').then((response) => {
           response.data.forEach(r=>{
             r.label=r.nombres+' '+r.apellidos
             this.afiliados.push(r)

           })
          this.filterAf=this.afiliados
        })

        },
    listadoVehiculos(){
      this.$api.get('vehiculo').then((response) => {
        this.vehiculos=response.data
      })

    },
    listadoGrupos(){
      this.grupos=[]
      this.$api.get('grupo').then((response) => {
        response.data.forEach(r=>{
          r.label=r.tipo
          this.grupos.push(r)

        })
      })

    },
    onSubmit(){
       if(this.vehiculo.grupo=={})
       {
         this.$q.notify({
           message: 'Debe Seleccionar Grupo',
           icon:"info",
           color: 'red'
         })
         return false
       }
       else{
         this.vehiculo.grupo_id=this.vehiculo.grupo.id
       }
      if(this.vehiculo.afiliado=={})
      {
        this.$q.notify({
          message: 'Debe Seleccionar Afiliado',
          icon:"info",
          color: 'red'
        })
        return false
      }
      else{
        this.vehiculo.afiliado_id=this.vehiculo.afiliado.id
      }
       this.loading=true
        this.listadoVehiculos()
        this.vehiculos.forEach(r=>{
          if(r.placa==this.vehiculo.placa){
            this.$q.notify({
              message: 'La placa se encuentra registrado',
              icon:"info",
              color: 'red'
            })
            this.loading=false

            return false
          }
        })

        this.$api.post('vehiculo',this.vehiculo).then((response) => {
          this.$q.notify({
            message: 'Vehiculo Registrado',
            icon:"done",
            color: 'green'
          })
          this.dialogRegistro=false
          this.listadoVehiculos()
          this.loading=false

        })

      },
    upVehiculo(ve){
      this.vehiculo2=ve;
      this.vehiculo2.codcolor=ve.codcolor
      this.vehiculo2.tipo=ve.tipo
      this.vehiculo2.grupo.label=this.vehiculo2.grupo.tipo
      this.vehiculo2.afiliado.label=this.vehiculo2.afiliado.nombres+' '+this.vehiculo2.afiliado.apellidos
      this.dialogModificar=true;
    },
    updateVehiculo(){
      this.loading=true
      this.vehiculo2.afiliado_id=this.vehiculo2.afiliado.id
      this.vehiculo2.grupo_id=this.vehiculo2.grupo.id
      this.$api.put('vehiculo/'+this.vehiculo2.id,this.vehiculo2).then((response) => {
        this.$q.notify({
          message: 'Vehiculo Modificado',
          icon:"done",
          color: 'green'
        })
        this.dialogModificar=false
        this.listadoAfiliado()
        this.loading=false

      })
    },
    EliminarVehiculo(af){
      this.$q.dialog({
        title: 'Eliminar Vehiculo',
        message: 'Esta seguro de eliminar?',
        cancel: true,
        persistent: false
      }).onOk(() => {
        // console.log('>>>> OK')
        this.$api.delete('vehiculo/'+af.id).then((response) => {
          this.$q.notify({
            message: 'Vehiculo Eliminado',
            icon:"done",
            color: 'red'
          })
          this.listadoVehiculos()
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

<template>
<q-page>
  <div class="col-12">
    <div class="text-h5 text-center">LISTA DE AFILIADOS</div>
    
    <q-table :rows="afiliados" :columns="columns" :filter="filter">
      <template v-slot:body-cell-estado="props">
        <q-td key="estado" :props="props">
          <q-badge :color="props.row.estado=='ACTIVO'?'green':'red'" :label="props.row.estado" />
          
      </q-td>
    </template>
      <template v-slot:body-cell-opcion="props">
          <q-td key="opcion" :props="props">
            <q-btn dense icon="toggle_on" color="accent" @click="cambioEstado(props.row)" />
            <q-btn dense icon="edit" color="yellow" @click="afiliado2=props.row;dialogModificar=true;" />
            <q-btn dense icon="delete" color="red" @click="EliminarAfiliado(props.row)"/>
        </q-td>
      </template>
      <template v-slot:top-right>
        <q-btn label="REGISTRAR" color="green" icon="person_add" @click="listreg" :loading="loading"/>
        <q-btn color="green" label="PDF" icon="download" @click="imprimirLista"/>
        <q-btn color="red" label="PDF" icon="download" @click="imprimirLista2"/>
        
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
          <div class="col-6"><q-input v-model="afiliado.codigo" label="Num Movil" outlined dense required/></div>
          <div class="col-6"><q-input v-model="afiliado.telefono" label="Telefono" outlined dense required/></div>
          <div class="col-6"><q-input v-model="afiliado.fechaing" label="Fecha Ing" type="date" outlined dense required/></div>
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
        <q-form @submit.prevent="updateAfiliado"  class="q-gutter-md">

          <q-card-section class="q-pt-none">
            <div class="row">

              <div class="col-6"><q-input v-model="afiliado2.ci" label="CI" outlined dense required readonly/></div>
              <div class="col-6"><q-select v-model="afiliado2.expedido" :options="exped" label="Expedido" dense outlined/></div>
              <div class="col-6"><q-input v-model="afiliado2.nombres" label="Nombres" outlined dense required/></div>
              <div class="col-6"><q-input v-model="afiliado2.apellidos" label="Apellidos" outlined dense required/></div>
              <div class="col-6"><q-input v-model="afiliado2.codigo" label="Num Movil" outlined dense required/></div>
              <div class="col-6"><q-input v-model="afiliado2.telfono" label="Telefono" outlined dense required/></div>
              <div class="col-6"><q-input v-model="afiliado2.fechaing" label="Fecha Ing" type="date" outlined dense required/></div>
            </div>
          </q-card-section>

          <q-card-actions align="right" class="text-primary">
            <q-btn flat color="red" label="Cancel" v-close-popup icon="cancel"/>
            <q-btn flat color="green" label="Modificar" icon="pencil" type="submit" :loading="loading"/>

          </q-card-actions>
        </q-form>
      </q-card>
    </q-dialog>
  </div>
  <div id="print" class="hidden"></div>

</q-page>

</template>

<script>
import {date} from 'quasar'
import Printd from 'printd'


export default {
  name: `Afiliados.vue`,
  data() {
    return {
      loading:false,
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
        {name:'estado',label:'ESTADO',field:'estado'},
        {name:'codigo',label:'NUM MOVIL',field:'codigo'},
        {name:'telfono',label:'TELEFONO',field:'telefono'},
        {name:'nombres',label:'NOMBRES',field:'nombres'},
        {name:'apellidos',label:'APELLIDOS',field:'apellidos'},
        {name:'fechaing',label:'FECHA ING',field:'fechaing'},
      ]
    };
  },
  created() {
    //window.location.reload();
    this.listadoAfiliado()
    },
  methods:{
    imprimirLista(){
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
        table, th, td {\
        border: 1px solid;\
        border-collapse: collapse;\
        }\
        img{width:70px;height:70px;}\
        </style>\
          <div id='print'>\
          <div class='titulo2'>AFILIADOS ACTIVOS </div><br>\
          <table>\
           <tr><th>CI</th><th>EXPEDIDO</th><th>NOMBRES</th><th>APELLIDOS</th><th>TELEFONO</th></tr>"
           this.afiliados.forEach(r => {
            if(r.estado=='ACTIVO')
            cadena+="<tr><td>"+r.ci+"</td><td>"+r.expedido+"</td><td>"+r.nombres+"</td><td>"+r.apellidos+"</td><td>"+r.telefono+"</td></tr>"
           });
          //cadena+="<tr><th>TOTALES</th><th>"+this.totalsindical+"</th><th>"+this.totaldecano+"</th><th>"+this.totaldeportivo+"</th><th>"+this.totalproaccidente+"</th><th>"+this.total+"</th></tr>\
          cadena+="</table></div>"
          document.getElementById('print').innerHTML = cadena
          d.print( document.getElementById('print') )
    },
    imprimirLista2(){
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
        table, th, td {\
        border: 1px solid;\
        border-collapse: collapse;\
        }\
        img{width:70px;height:70px;}\
        </style>\
          <div id='print'>\
          <div class='titulo2'>AFILIADOS PASIVOS </div><br>\
          <table>\
           <tr><th>CI</th><th>EXPEDIDO</th><th>NOMBRES</th><th>APELLIDOS</th><th>TELEFONO</th></tr>"
           this.afiliados.forEach(r => {
            if(r.estado=='PASIVO')
            cadena+="<tr><td>"+r.ci+"</td><td>"+r.expedido+"</td><td>"+r.nombres+"</td><td>"+r.apellidos+"</td><td>"+r.telefono+"</td></tr>"
           });
          //cadena+="<tr><th>TOTALES</th><th>"+this.totalsindical+"</th><th>"+this.totaldecano+"</th><th>"+this.totaldeportivo+"</th><th>"+this.totalproaccidente+"</th><th>"+this.total+"</th></tr>\
          cadena+="</table></div>"
          document.getElementById('print').innerHTML = cadena
          d.print( document.getElementById('print') )
    },
  
    cambioEstado(af){
      this.$api.post('cambioEstado/'+af.id).then((r) => {
        this.listadoAfiliado()
      })

    },
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
       this.loading=true
        this.listadoAfiliado()
        this.afiliados.forEach(r=>{
          if(r.ci==this.afiliado.ci){
            this.$q.notify({
              message: 'El carnet se encuentra registrado',
              icon:"info",
              color: 'red'
            })

            this.loading=false
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
          this.loading=false

        })

      },
    updateAfiliado(){
      this.loading=true
      this.$api.put('afiliado/'+this.afiliado2.id,this.afiliado2).then((response) => {
        this.$q.notify({
          message: 'Afiliado Modificado',
          icon:"done",
          color: 'green'
        })
        this.dialogModificar=false
        this.listadoAfiliado()
        this.loading=false

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

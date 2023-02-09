<template>
<q-page>
  <div class="row">
    <div class="col-12">
      <div class="text-h5 text-center bg-green-5 text-white">Asistencia Manual</div>
    </div>
    <div class="col-12">
      <q-table :rows="afiliados" :columns="columns" :filter="filter" flat bordered dense :rows-per-page-options="[0]" >
        <template v-slot:body-cell-opcion="props">
          <q-td key="opcion" :props="props">
            <q-btn dense icon="print" color="primary" @click="asistencia(props.row)" />
          </q-td>
        </template>
        <template v-slot:top-right>
          <q-input outlined dense debounce="300" v-model="filter" placeholder="Buscar">
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>
      </q-table>
    </div>
  </div>
</q-page>

</template>

<script>
import {date} from 'quasar'
import Printd from "printd";

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
        {name:'opcion',label:'OPCION',align:'center'},
        {name:'ci',label:'CI',field:'ci',align:'left'},
        {name:'expedido',label:'EXPEDIDO',field:'expedido',align:'left'},
        {name:'codigo',label:'NUM MOVIL',field:'codigo',align:'left'},
        {name:'telfono',label:'TELEFONO',field:'telefono',align:'left'},
        {name:'nombres',label:'NOMBRES',field:'nombres',align:'left'},
        {name:'apellidos',label:'APELLIDOS',field:'apellidos',align:'left'},
        {name:'fechaing',label:'FECHA ING',field:'fechaing',align:'left'},
      ]
    };
  },
  created() {
    //window.location.reload();
    this.listadoAfiliado()
    },
  methods:{
    asistencia(afiliado){
      // console.log(afiliado)
      this.$api.post('asistencialocal',{
        afiliado_id:afiliado.id,
      }).then((res) => {
        console.log(res.data)
        let pago=res.data
        const d = new Printd()
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
          <td class='titulo1'  style='width:50%'>SINDICATO MIXTO DE TRANSPORTE<br><span class='titulo2'>26 DE JULIO</span><br><span class='titulo3' FUNDADO EL 26 DE JULIO DE 1970 <br> RESOLUCION SUPREMA 27465</span><br><br><span class='titulo2'> ASISTENCIA</span></td>\
          <td class='titulo1'> FECHA<br>"+pago.fecha +" " +pago.hora+"<br><span class='titulo5'> N° Movil "+pago.afiliado.codigo+" </span><br><span   >No "+pago.id+"</span></td></tr>\
          </table>\
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
        .catch((err) => {
        this.$q.notify({
          message: err.response.data.message,
          color: 'negative',
          position: 'top',
          timeout: 2000
        })
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

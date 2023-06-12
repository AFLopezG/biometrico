<template>
    <q-page>
      <div class="col-12">
        <div class="text-h5 text-center text-bold">LISTA DE CHOFERES</div>
        <q-table dense :rows="drivers" :columns="columns" :filter="filter" :rows-per-page-options="[0]">
          <template v-slot:body-cell-opcion="props">
              <q-td key="opcion" :props="props" auto-width>
                <q-btn dense icon="edit" color="yellow" @click="updriver(props.row)">
                  <q-tooltip>Modificar</q-tooltip>
                </q-btn>
                <q-btn dense icon="delete" color="red" @click="Eliminardriver(props.row)">
                  <q-tooltip>Eliminar</q-tooltip>
                </q-btn>
                <q-btn dense icon="people" color="primary" @click="afiliadosAdd(props.row)">
                  <q-tooltip>Afiliados</q-tooltip>
                </q-btn>
                <q-btn dense icon="o_photo_camera" color="green" @click="foto(props.row)">
                  <q-tooltip>Foto</q-tooltip>
                </q-btn>
            </q-td>
          </template>
          <template v-slot:body-cell-foto="props">
            <q-td key="foto" :props="props" auto-width>
<!--              <pre>{{props.row.foto}}</pre>-->
              <q-img v-if="props.row.foto!=null && props.row.foto!=''"  :src="`${$url}../images/${props.row.foto}`" style="width: 50px;" />
            </q-td>
          </template>
          <template v-slot:body-cell-afiliados="props">
            <q-td key="afiliados" :props="props" auto-width>
              <ul style="padding: 0;list-style: none;">
                <li v-for="a in props.row.afiliado_driver" :key="a.id"
                 style="list-style: none;text-transform: lowercase;"
                ><b>{{a.fechaingreso}}</b> {{a.fechabaja}} {{a.afiliado.apellidos}} {{a.afiliado.nombres}}
                <q-btn v-if="a.fechabaja==null" dense rounded size="7px" icon="o_delete" color="red" @click="afiliadosDriverFechaBaja(a.id)">
                  <q-tooltip>Dar de baja</q-tooltip>
                </q-btn>
                </li>
              </ul>
            </q-td>
          </template>
          <template v-slot:top-right>
            <q-btn label="Registrar" no-caps color="green" icon="person_add" @click="listreg" :loading="loading"/>
             <q-btn color="red" icon="download" label="PDF" @click="imprimirLista"/>
            
            <q-input outlined dense debounce="300" v-model="filter" placeholder="Buscar">
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
          </template>
        </q-table>
<!--        <pre>{{drivers}}</pre>-->

        <q-dialog v-model="dialogRegistro" >
          <q-card style="width: 70vh;min-width: 350px">
            <q-card-section>
              <div class="text-h6">REGISTRO DE CHOFER</div>
            </q-card-section>
            <q-form @submit.prevent="onSubmit"  class="q-gutter-md">
            <q-card-section class="q-pt-none">
              <div ><q-input v-model="driver.ci" label="Cedula identidad" outlined dense required/></div>
              <div ><q-input v-model="driver.nombres" label="Nombre Completo" outlined dense required/></div>
              <div ><q-input v-model="driver.celular"  label="Celular"  dense outlined required/></div>
              <div> <q-select map-options emit-value dense outlined option-value="id" v-model="driver.afiliado_id" :options="afiliados" label="Afiliado" use-input input-debounce="0" @filter="filterFn" /></div>
<!--              <pre>{{driver}}</pre>-->
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
              <div class="text-h6">MODIFICAR DE CHOFER</div>
            </q-card-section>
            <q-form @submit.prevent="updatedriver"  class="q-gutter-md">

              <q-card-section class="q-pt-none">

                  <div ><q-input v-model="driver2.ci" label="Cedula" outlined dense required /></div>
                  <div ><q-input v-model="driver2.nombres" label="Nombre Completo" outlined dense required /></div>
                  <div ><q-input v-model="driver2.celular"   label="Celular" dense outlined required/></div>
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
      <q-dialog v-model="dialogAfiliados">
        <q-card style="width: 70vh;min-width: 350px">
          <q-card-section>
            <div class="text-h6">AFILIADOS</div>
          </q-card-section>
          <q-card-section class="q-pt-none">
            <q-form @submit.prevent="afiliadosRegister"  class="q-gutter-md">
              <q-select map-options emit-value dense outlined option-value="id" v-model="driver.afiliado_id" :options="afiliados" label="Afiliado" use-input input-debounce="0" @filter="filterFn" />
              <q-card-actions align="right" class="text-primary">
                <q-btn flat color="red" label="Cancel" v-close-popup icon="cancel"/>
                <q-btn flat color="green" label="Registrar" icon="send" type="submit" :loading="loading"/>
              </q-card-actions>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>
      <q-dialog v-model="dialogPhoto">
        <q-card>
          <q-card-section>
            <div class="text-h6">FOTO</div>
          </q-card-section>
          <q-card-section class="q-pt-none">
            <q-uploader
              accept=".jpg, .png"
              multiple
              auto-upload
              label="Arrastra una imagen o haz click para seleccionar"
              @uploading="uploadingFn"
              @failed="errorFn"
              ref="uploader"
              max-files="1"
              auto-expand
              :url="`${$url}upload/${driver.id}`"
              stack-label="upload image"/>
          </q-card-section>
        </q-card>
      </q-dialog>
  <div id="print" class="hidden"></div>

    </q-page>

    </template>

    <script>
    import {date} from 'quasar'
    import xlsx from "json-as-xlsx"
    import Printd from 'printd'

    export default {
      name: `drivers.vue`,
      data() {
        return {
          loading:false,
          dialogPhoto:false,
          dialogRegistro:false,
          dialogAfiliados:false,
          dialogModificar:false,
          driver:{},
          driver2:{},
          drivers:[],
          afiliados:[],
          filterAf:[],
          datoprint:[],
          filter:'',
          columns:[
            {name:'opcion',label:'Opcion',align:'left',field:'opcion'},
            {name:'foto',label:'foto',field:'foto',align:'left'},
            {name:'afiliados',label:'afiliado',field:'afiliados',align:'left'},
            {name:'ci',label:'ci',field:'ci',align:'left'},
            {name:'nombre',label:'Nombre',field:'nombres',align:'left'},
            {name:'celular',label:'Celular',field:'celular',align:'left'},
          ]
        };
      },
      created() {
        this.listadoAfiliado()
        this.listadodrivers()
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
          <div class='titulo2'>CHOFERES </div><br>\
          <table>\
           <tr><th>CI</th><th>NOMBRES</th><th>TELEFONO</th></tr>"
           this.drivers.forEach(r => {
            cadena+="<tr><td>"+r.ci+"</td><td>"+r.nombres+"</td><td>"+r.celular+"</td></tr>"
           });
          //cadena+="<tr><th>TOTALES</th><th>"+this.totalsindical+"</th><th>"+this.totaldecano+"</th><th>"+this.totaldeportivo+"</th><th>"+this.totalproaccidente+"</th><th>"+this.total+"</th></tr>\
          cadena+="</table></div>"
          document.getElementById('print').innerHTML = cadena
          d.print( document.getElementById('print') )
    },
        uploadingFn (e) {
          e.xhr.onload = () => {
            if (e.xhr.readyState === e.xhr.DONE) {
              if (e.xhr.status === 200) {
                this.dialogPhoto=false
                this.driver.foto = e.xhr.response
              }
            }
          }
        },
        errorFn (err) {
          console.log(err)
          // this.$q.notify({
          //   color: 'red-4',
          //   textColor: 'white',
          //   icon: 'cloud_done',
          //   position: 'top',
          //   message: 'Error al subir la imagen, intente nuevamente el nombre no debe contener espacios o ñ'
          // })
        },
        afiliadosRegister(){
          this.loading=true
          this.$api.post('afiliadosRegister',{
            afiliado_id:this.driver.afiliado_id,
            driver_id:this.driver.id
          }).then((response) => {
            this.loading=false
            this.$q.notify({
              color: 'green-4',
              textColor: 'white',
              icon: 'cloud_done',
              message: 'Registro exitoso',
              position: 'top'
            })
            this.dialogAfiliados=false
            this.listadodrivers()
          })
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
          this.driver={};
          this.dialogRegistro=true
        },
        afiliadosDriverFechaBaja(id){
          this.$q.dialog({
            title: 'Seguro que desea dar de baja?',
            message: 'Esta accion no se puede revertir',
            cancel: true,
            persistent: true
          }).onOk(data => {
            this.loading=true
            this.$api.post('afiliadosDriverFechaBaja',{
              id:id,
              fecha_baja:data
            }).then((response) => {
              this.$q.notify({
                color: 'green-4',
                textColor: 'white',
                icon: 'cloud_done',
                message: 'Registro exitoso',
                position: 'top'
              })
              this.listadodrivers()
            }).finally(()=>{
              this.loading=false
            })
          })
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
        listadodrivers(){
          this.$api.get('driver').then((response) => {
            // console.log(response.data)
            this.drivers=response.data
          })

        },

        onSubmit(){
           this.loading=true
            // this.listadodrivers()
            this.drivers.forEach(r=>{
              if(r.ci==this.driver.ci){
                this.$q.notify({
                  message: 'El ci encuentra registrado',
                  icon:"info",
                  color: 'red'
                })
                this.loading=false
                return false
              }
            })
          if (this.driver.afiliado_id==undefined){
            this.$q.notify({
              message: 'Seleccione un afiliado',
              icon:"info",
              color: 'red',
              position:"top"
            })
            this.loading=false
            return false
          }
          // console.log(this.driver)
          // return false
            this.$api.post('driver',this.driver).then((response) => {
              this.$q.notify({
                message: 'Chofer Registrado',
                icon:"done",
                color: 'green'
              })
              this.dialogRegistro=false
              this.listadodrivers()
              this.loading=false

            })

          },
        updriver(ve){
          this.driver2=ve;
          this.dialogModificar=true;
        },
        updatedriver(){
          this.loading=true
          this.$api.put('driver/'+this.driver2.id,this.driver2).then((response) => {
            this.$q.notify({
              message: 'driver Modificado',
              icon:"done",
              color: 'green'
            })
            this.dialogModificar=false
            this.listadoAfiliado()
            this.loading=false

          })
        },
        foto(driver){
          this.driver=driver
          this.dialogPhoto=true
        },
        afiliadosAdd(chofer){
          this.dialogAfiliados=true
          this.driver=chofer
        },
        Eliminardriver(af){
          this.$q.dialog({
            title: 'Eliminar driver',
            message: 'Esta seguro de eliminar?',
            cancel: true,
            persistent: false
          }).onOk(() => {
            // console.log('>>>> OK')
            this.$api.delete('driver/'+af.id).then((response) => {
              this.$q.notify({
                message: 'driver Eliminado',
                icon:"done",
                color: 'red'
              })
              this.listadodrivers()
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

<template>
    <q-page>
      <div class="col-12">
        <div class="text-h5 text-center text-bold">LISTA DE CHOFERES</div>

        <q-table :rows="drivers" :columns="columns" :filter="filter">
          <template v-slot:body-cell-opcion="props">
              <q-td key="opcion" :props="props">
                <q-btn dense icon="edit" color="yellow" @click="updriver(props.row)" />
                <q-btn dense icon="delete" color="red" @click="Eliminardriver(props.row)"/>
            </q-td>
          </template>
          <template v-slot:top-right>
            <q-btn label="Registrar" no-caps color="green" icon="person_add" @click="listreg" :loading="loading"/>
            <q-input outlined dense debounce="300" v-model="filter" placeholder="Buscar">
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
          </template>
        </q-table>
        <pre>{{drivers}}</pre>

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
              <div> <q-select map-options emit-value dense outlined v-model="driver.afiliado" :options="afiliados" label="Afiliado" use-input input-debounce="0" @filter="filterFn" /></div>
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
          dialogRegistro:false,
          dialogModificar:false,
          driver:{},
          driver2:{},
          drivers:[],
          afiliados:[],
          filterAf:[],
          datoprint:[],
          filter:'',
          columns:[
            {name:'opcion',label:'OPCION'},
            // {name:'afiliado',label:'afiliado',field:row=>row.afiliados[0].nombres+' '+row.afiliados[0].apellidos},
            {name:'foto',label:'foto',field:'foto'},
            {name:'ci',label:'ci',field:'ci'},
            {name:'nombre',label:'NOMBRE',field:'nombres'},
            {name:'celular',label:'CELULAR',field:'celular'},
          ]
        };
      },
      created() {
        this.listadoAfiliado()
        this.listadodrivers()
        },
      methods:{
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
          console.log(this.driver)
          return false
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

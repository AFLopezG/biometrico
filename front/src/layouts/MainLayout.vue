<template>
  <q-layout view="lHh Lpr lFf">
    <q-header>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />
        <q-toolbar-title>
          26 de julio
        </q-toolbar-title>
        <div>Sindicato de Mixto Transporte</div>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
    >
      <q-list>
        <q-item-label class="q-pa-xs">
          <div class="row">
            <div class="col-3 flex flex-center">
              <!--              <q-avatar size="48px">-->
              <q-icon color="black" size="40px" name="o_directions_bus_filled" />
              <!--              </q-avatar>-->
            </div>
            <div class="col-9">
              <div class="text-h6 text-bold " >COBRO BIO</div>
              <div class="text-caption">v 1.0</div>
            </div>
          </div>
        </q-item-label>
        <q-separator />
        <q-toolbar class="bg-primary text-white shadow-2">
          <q-toolbar-title>Opciones</q-toolbar-title>
        </q-toolbar>
        <q-list bordered>
<!--
          <q-item clickable v-ripple active-class="bg-primary text-white" to="misDatos">
            <q-item-section avatar>
              <q-icon name="o_save" />
            </q-item-section>
            <q-item-section>USUARIOS</q-item-section>
          </q-item>
-->
          <q-item clickable v-ripple active-class="bg-primary text-white" to="usuarios" v-if="store.booluser">
            <q-item-section avatar>
              <q-icon name="o_people" />
            </q-item-section>
            <q-item-section>USUARIOS</q-item-section>
          </q-item>
          <q-item clickable v-ripple active-class="bg-primary text-white" to="afiliados" v-if="store.boolafiliado">
            <q-item-section avatar>
              <q-icon name="o_fact_check" />
            </q-item-section>
            <q-item-section>AFILIADOS</q-item-section>
          </q-item>
          <q-item clickable v-ripple active-class="bg-primary text-white" to="grupos" v-if="store.boolgrupo">
            <q-item-section avatar>
              <q-icon name="o_dns" />
            </q-item-section>
            <q-item-section>GRUPOS</q-item-section>
          </q-item>
          <q-item clickable v-ripple active-class="bg-primary text-white" to="vehiculos" v-if="store.boolvehiculo">
            <q-item-section avatar>
              <q-icon name="o_ballot" />
            </q-item-section>
            <q-item-section>VEHICULOS</q-item-section>
          </q-item>
          <q-item clickable v-ripple active-class="bg-primary text-white" to="asistenciaManual" v-if="store.boolasistencia">
            <q-item-section avatar>
              <q-icon name="check" />
            </q-item-section>
            <q-item-section>Asistencia Manual</q-item-section>
          </q-item>
          <q-item clickable v-ripple active-class="bg-primary text-white" to="asistencia" v-if="store.boolasistencia">
            <q-item-section avatar>
              <q-icon name="o_speaker_notes" />
            </q-item-section>
            <q-item-section>ASISTENCIA</q-item-section>
          </q-item>
          <q-item clickable v-ripple active-class="bg-primary text-white" to="pagos" v-if="store.boolpago">
            <q-item-section avatar>
              <q-icon name="o_speaker_notes" />
            </q-item-section>
            <q-item-section>PAGOS</q-item-section>
          </q-item>
          <q-item clickable v-ripple active-class="bg-primary text-white" to="impresion" v-if="store.boolprint">
            <q-item-section avatar>
              <q-icon name="o_list_alt" />
            </q-item-section>
            <q-item-section>IMPRESION</q-item-section>
          </q-item>
          <q-item clickable v-ripple active-class="bg-primary text-white" @click="logout" v-if="store.isLoggedIn">
            <q-item-section avatar>
              <q-icon name="o_logout" />
            </q-item-section>
            <q-item-section>Salir</q-item-section>
          </q-item>
        </q-list>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  <div id="print" class="hidden"></div>

  </q-layout>
</template>
<script>
import {globalStore} from "stores/globalStore";

export default {
  data () {
    return {
      leftDrawerOpen: false,
      store:globalStore()
    }
  },
  methods: {
    logout(){
      this.$q.dialog({
        title: 'Cerrar sesión',
        message: '¿Está seguro que desea cerrar sesión?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        this.$q.loading.show()
        this.$api.post('logout').then(() => {
          globalStore().user={}
          localStorage.removeItem('tokenBio')
          globalStore().isLoggedIn=false
          this.$router.push('/login')
          this.$q.loading.hide()
          globalStore().isLoggedIn=false
          globalStore().booluser=false
          globalStore().boolafiliado=false
          globalStore().boolgrupo=false
          globalStore().boolvehiculo=false
          globalStore().boolpago=false
          globalStore().boolprint=false
          globalStore().boolasistencia=false
        })

      }).onCancel(() => {
      })
    },
      eventSearch(){
        this.$api.post('eventSearch').then(res=>{
          // console.log(res.data)
          this.store.eventNumber=res.data
        })
      },
      toggleLeftDrawer() {
        this.leftDrawerOpen = !this.leftDrawerOpen
      }
    }
}
</script>

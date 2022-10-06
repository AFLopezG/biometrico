<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
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
          Pago biometrico
        </q-toolbar-title>

        <div>Quasar v{{ $q.version }}</div>
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
              <q-icon color="primary" size="40px" name="o_restaurant" />
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
          <q-item clickable v-ripple active-class="my-menu-link" to="misDatos">
            <q-item-section avatar>
              <q-icon name="o_save" />
            </q-item-section>
            <q-item-section>USUARIOS</q-item-section>
          </q-item>
          <q-item clickable v-ripple active-class="my-menu-link" to="afiliados">
            <q-item-section avatar>
              <q-icon name="o_fact_check" />
            </q-item-section>
            <q-item-section>AFILIADOS</q-item-section>
          </q-item>
          <q-item clickable v-ripple activºe-class="my-menu-link" to="grupos">
            <q-item-section avatar>
              <q-icon name="o_dns" />
            </q-item-section>
            <q-item-section>GRUPOS</q-item-section>
          </q-item>
          <q-item clickable v-ripple active-class="my-menu-link" to="vehiculos">
            <q-item-section avatar>
              <q-icon name="o_ballot" />
            </q-item-section>
            <q-item-section>VEHICULOS</q-item-section>
          </q-item>
          <q-item clickable v-ripple active-class="my-menu-link" to="critico">
            <q-item-section avatar>
              <q-icon name="o_speaker_notes" />
            </q-item-section>
            <q-item-section>PAGOS</q-item-section>
          </q-item>
          <q-item clickable v-ripple active-class="my-menu-link" to="impresion">
            <q-item-section avatar>
              <q-icon name="o_list_alt" />
            </q-item-section>
            <q-item-section>IMPRESION</q-item-section>
          </q-item>
        </q-list>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>
<script>
export default {
  data () {
    return {
      leftDrawerOpen: false
    }
  },
  methods: {
    toggleLeftDrawer () {
      this.leftDrawerOpen = !this.leftDrawerOpen
    }
  }
}
</script>

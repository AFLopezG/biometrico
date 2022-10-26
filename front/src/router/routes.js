import Impresion from "pages/Impresion";
import Afiliados from "pages/Afiliados";
import Vehiculos from "pages/Vehiculos";
import Grupos from "pages/Grupos";
import Pagos from "pages/Pagos";
import Listas from "pages/Listas";
import Asistencia from "pages/Asistencia";

const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue') },
      { path: '/impresion', component: Impresion},
      { path: '/afiliados', component: Afiliados},
      { path: '/vehiculos', component: Vehiculos},
      { path: '/grupos', component: Grupos},
      { path: '/pagos', component: Pagos},
      { path: '/listas', component: Listas},
      { path: '/asistencia', component: Asistencia},
    ]
  },


  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes

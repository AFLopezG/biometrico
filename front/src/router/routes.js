import Impresion from "pages/Impresion";
import Afiliados from "pages/Afiliados";
import Vehiculos from "pages/Vehiculos";

const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue') },
      { path: '/impresion', component: Impresion},
      { path: '/afiliados', component: Afiliados},
      { path: '/vehiculos', component: Vehiculos},
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

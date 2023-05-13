import Impresion from "pages/Impresion";
import Afiliados from "pages/Afiliados";
import Vehiculos from "pages/Vehiculos";
import Grupos from "pages/Grupos";
import Pagos from "pages/Pagos";
import Listas from "pages/Listas";
import Asistencia from "pages/Asistencia";
import User from "pages/User";
import Login from "pages/Login";
import AsistenciaManual from "pages/AsistenciaManual";
import Drivers from "pages/Drivers";

const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue'),meta: { requiresAuth: true } },
      { path: '/impresion', component: Impresion,meta: { requiresAuth: true }},
      { path: '/afiliados', component: Afiliados,meta: { requiresAuth: true }},
      { path: '/vehiculos', component: Vehiculos,meta: { requiresAuth: true }},
      { path: '/grupos', component: Grupos,meta: { requiresAuth: true }},
      { path: '/pagos', component: Pagos,meta: { requiresAuth: true }},
      { path: '/listas', component: Listas,meta: { requiresAuth: true }},
      { path: '/asistencia', component: Asistencia,meta: { requiresAuth: true }},
      { path: '/usuarios', component: User,meta: { requiresAuth: true }},
      { path: '/asistenciaManual', component: AsistenciaManual,meta: { requiresAuth: true }},
      { path: '/Choferes', component: Drivers,meta: { requiresAuth: true }},
    ]
  },
  {
    path: '/login',
    component: Login,
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes

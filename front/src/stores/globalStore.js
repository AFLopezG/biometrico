import { defineStore } from 'pinia';

export const globalStore = defineStore('global', {
  state: () => ({
    counter: 0,
    user: {},
    eventNumber: 0,
    isLoggedIn: !!localStorage.getItem('tokenBio'),
    socket: false,
    booluser:false,
    boolafiliado:false,
    boolgrupo:false,
    boolvehiculo:false,
    boolpago:false,
    boolprint:false,
  }),
  getters: {
    doubleCount: (state) => state.counter * 2,
  },
  actions: {
    increment() {
      this.counter++;
    },
  },
});

<template>
<q-page>
<q-table :rows="pagos">
</q-table>
  <pre>{{pagos}}</pre>
  <div id="print"></div>
</q-page>
</template>

<script>
import Printd from 'printd'
export default {
  name: `Impresion`,
  data() {
    return {
      pagos: [],
    };
  },
  created() {
    setInterval(() => {
      this.$api.get('pago').then((response) => {
        this.pagos = response.data;
        this.pagos.forEach((pago) => {
          if (pago.impreso==0) {
            const d = new Printd()
            this.$api.put('pago/' + pago.id,{impreso:1})
            pago.impreso = 1
            document.getElementById('print').innerHTML = `
            El Pago se relaizo de ${pago.monto} a ${pago.nombre} ${pago.apellido} con el numero de cuenta ${pago.cuenta} y el numero de referencia ${pago.referencia} el dia ${pago.fecha}
            `
            d.print( document.getElementById('print'))
          }
        });
      });
    }, 5000);

  },
}
</script>

<style scoped>

</style>

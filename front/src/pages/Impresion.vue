<template>
<q-page>
<q-table :rows="pagos" :columns="columns" dense :rows-per-page-options="[20,50,100,0]">
</q-table>
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
      columns:[
        {name:'id',label:'id',field:'id'},
        {name:'monto',label:'monto',field:'monto'},
        {name:'multa',label:'multa',field:'multa'},
        {name:'fecha',label:'fecha',field:'fecha'},
        {name:'hora',label:'hora',field:'hora'},
        {name:'grupo',label:'grupo',field:row=>row.grupo.tipo},
        {name:'afiliado',label:'afiliado',field:row=>row.afiliado.nombres+' '+row.afiliado.apellidos},
        {name:'vehiculo',label:'vehiculo',field:row=>row.vehiculo.placa},
      ]
    };
  },
  created() {
    setInterval(() => {
      this.$api.get('pago').then((response) => {
        this.pagos = response.data;
       // const d = new Printd()

        this.pagos.forEach((pago) => {
          if (pago.impreso==0) {
            const d = new Printd()
            this.$api.put('pago/' + pago.id,{impreso:1})
            pago.impreso = 1
            let obs=pago.multa>0?'TIENE MULTA POR RETRASO':''
            let cadena="<style>\
            .titulo1{font-size:10px; text-align: center;}\
            .titulo2{font-size:14px; text-align: center; font-weight: bold;}\
            .titulo3{font-size:8px; text-align: center;}\
            .titulo4{font-size:12px; text-align: center; font-weight: bold;}\
            .texto1{font-size:10px; text-align: center; font-weight: normal;}\
            .texto2{font-size:14px; text-align: center; font-weight: normal;}\
            .texto3{font-size:8px; text-align: center; font-weight: normal;}\
            table{width:100%}\
            </style>\
              <div >\
              <table>\
              <tr><td style='width:20%'></td>\
              <td class='titulo1'  style='width:50%'>SINDICATO MIXTO DE TRANSPORTE<br><span class='titulo2'>26 DE JULIO</span><br><span class='titulo3' FUNDADO EL 26 DE JULIO DE 1970 <br> RESOLUCION SUPREMA 221174</span><br><br><span class='titulo2'> HOJA DE APORTES</span></td>\
              <td class='titulo1'> FECHA<br>"+pago.fecha+"<br><span class='titulo3'>Strio de Conflictos Cel:73039359</span><br><span   >No "+pago.id+"</span></td></tr>\
              </table>\
              <table><tr>\
              <td class='col-4 titulo4'>GRUPO<br><span class='texto1'>"+pago.grupo.tipo+"</span></td><td class='col-4 titulo4'>PLACA<br><span class='texto1'>"+pago.vehiculo.placa+"</span></td><td class='col-4 titulo4'>TOTAL Bs<br><span class='texto1'>"+(parseFloat(pago.monto) + parseFloat(pago.multa)).toFixed(2)+"</span></td>\
              </tr></table>\
              <div class='col-12 titulo4'>CONDUCTOR</div><div class='col-12 texto2'>"+pago.afiliado.nombres +' ' +pago.afiliado.apellidos+"</div>\
              <div class='titulo4'>OBSERVACION: <span class='texto1'>"+obs+"</span></div>\
              </div><br><br>\
              <table><tr>\
              <td class='texto3'>Secretario de Hacienda<br>VoBo</td><td class='texto3'>Agente de Control</td>\
              </tr></table>\
              </div>"
            document.getElementById('print').innerHTML = cadena
            d.print( document.getElementById('print') )
          }
        });
      });
    }, 2000);

  },
}
</script>

<style scoped>

</style>

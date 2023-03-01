<template>
  <div>
    <input-text
        :value="displayedValue"
        @change="emitChangeEvent"
    ></input-text>
  </div>
</template>
<script>
import InputText from "./InputText";

export default {
  components: {
    InputText
  },
  props: {
    value: {type: String, required: true},
  },
  watch: {
    value: function(newVal, oldVal) {
      this.emitChangeEvent(newVal.slice(0, -2) + ',' + newVal.slice(-2));
    },
  },
  mounted() {
    if (typeof Number(this.value) === 'number' && Number(this.value) > 0) {
      this.emitChangeEvent(this.value.slice(0, -2) + ',' + this.value.slice(-2));
    }
  },
  data() {
    return {
      displayedValue: "",
    };
  },
  methods: {
    emitChangeEvent(event) {
      this.$emit('input', this.calculateNormalizedAmount(event));
    },
    calculateNormalizedAmount(amount) {
      const decs = Math.max(amount.lastIndexOf(","), amount.lastIndexOf("."));
      const dec = amount.slice(decs+1);
      const decf = decs===-1?"00":dec.length>2?dec.slice(0,2):dec.length===1?dec+"0":dec.length===0?"00":dec;
      amount = decs===-1?amount+decf:amount.slice(0,decs)+decf;
      const tempAmount = amount.replace(/[^0-9]/g, '');

      if (tempAmount.length < 1) {
        this.displayedValue = "0,00";
        return "0";
      }

      const stringAmount = tempAmount.toString();
      this.displayedValue = stringAmount.slice(0, -2) + ',' + stringAmount.slice(-2);
      return stringAmount;
    },
  },
}
</script>

<template>
  <div>
    <input-text
        :value="value/100"
        @change="onInput"
        @input="onInput"
    >
    </input-text>
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
  methods: {
    onInput(event) {
      this.$emit('input', this.calculateNormalizedAmount(event));
    },
    calculateNormalizedAmount(amount) {
      const tempAmount = Number(amount.replace(/,/, '.'));

      if (typeof tempAmount === 'number') {
        return parseInt(tempAmount * 100);
      } else {
        return 0;
      }
    },
  },
}
</script>

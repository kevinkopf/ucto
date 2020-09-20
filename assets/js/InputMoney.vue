<template>
  <div>
    <input-text
        v-model="value"
        @change="calculateNormalizedAmount"
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
  data() {
    return {
      value: '',
    };
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

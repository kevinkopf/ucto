<template>
  <div v-if="isActive"
       style="
       position: absolute;
       z-index: 999;
       background-color: rgba(255, 255, 255, 0.9);
       width: 100%;
       height: 100%;
      "
  >
    <img alt="Loading" class="rotating" src="/build/images/logo.svg">
  </div>
</template>
<script>
export default {
  props: {
    isActive: {type: Boolean, required: true}
  },
}
</script>
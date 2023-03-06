<template>
  <div v-if="isActive"
       style="
       position: fixed;
       top: 0;
       left: 0;
       z-index: 999;
       background-color: rgba(255, 255, 255, 0.9);
       width: 100%;
       height: 100%;
       padding-top: 100px;
      "
  >
    <img alt="Loading" class="rotating" src="/build/images/logo.svg">
  </div>
</template>
<script>
export default {
  created() {
    this.$root.$on("is-loading::true", () => {
      this.isActive=true;
    });
    this.$root.$on("is-loading::false", () => {
      this.isActive=false;
    });
  },
  data() {
    return {
      isActive: false,
    };
  },
}
</script>
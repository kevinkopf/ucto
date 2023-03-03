<template>
  <b-modal
      id="account-analytical-remove"
      title="Odstranit analytický účet?"
      :no-close-on-backdrop="true"
      cancel-title="Ponechat"
      ok-title="Odstranit"
      @ok="submitRemove"
  >
    <p class="my-4">Záznam bude nenávratně odstraněn.</p>
  </b-modal>
</template>
<script>
export default {
  props: {
    analyticalAccountRemoveUrl: {type: String, required: true},
  },
  data() {
    return {
      id: 0,
    };
  },
  mounted() {
    window.EventBus.$on('accountAnalyticalRemove', (id) => {
      this.id = id;
    });
  },
  methods: {
    submitRemove() {
      if (this.id > 0) {
        window.location.replace(this.analyticalAccountRemoveUrl + '?id=' + this.id);
      }
    },
  },
}
</script>

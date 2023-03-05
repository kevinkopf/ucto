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
import axios from 'axios';
import qs from 'qs';

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
      axios({
        method: 'post',
        url: this.analyticalAccountRemoveUrl,
        headers: {'content-type': 'application/x-www-form-urlencoded'},
        data: qs.stringify({id: this.id}),
      }).then((response) => {

      }).catch((error) => {
        console.log(error);
      });
    },
  },
}
</script>

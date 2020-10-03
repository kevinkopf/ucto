<template>
  <div class="row mb-3">
    <div class="col-2">
      <input-date
          v-model="payload.date"
          label="Sestavit ke dni"
      >
      </input-date>
    </div>
    <div class="col-2">
      <button class="btn btn-outline-secondary" type="button" @click="submit">Go!</button>
    </div>
  </div>
</template>
<script>
import axios from 'axios';
import InputDate from "./InputDate";

export default {
  components: {InputDate,},
  props: {
    submitUrl: {type: String, required: true},
  },
  data() {
    return {
      payload: {
        date: new Date(),
      },
    };
  },
  methods: {
    submit() {
      const _this = this;
      let [y, m, d] = this.payload.date.split("-");

      axios.post(_this.submitUrl, {
        year: y,
        month: m,
        day: d,
      }).then(function (response) {
        console.log(response);
      }).catch(function (error) {
        console.log(error);
      });
    },
  },
};
</script>

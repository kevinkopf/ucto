<template>
  <div class="input-group">
    <input-date
        v-model="payload.date"
        label="Sestavit ke dni"
    >
    </input-date>
    <span class="input-group-append">
      <button class="btn btn-success" type="button" @click="submit">Go!</button>
    </span>
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

      axios.post(_this.submitUrl, {
        year: _this.payload.date.getFullYear(),
        month: _this.payload.date.getMonth()+1,
        day: _this.payload.date.getDate(),
      }).then(function (response) {
        console.log(response);
      }).catch(function (error) {
        console.log(error);
      });
    },
  },
};
</script>

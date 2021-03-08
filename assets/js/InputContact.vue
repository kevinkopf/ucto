<template>
  <div class="form-group">
    <label class="typo__label" for="ajax">Kontakt</label>
    <multiselect
        v-model="selectedValue"
        id="ajax"
        label="name"
        track-by="id"
        placeholder="Type to search"
        open-direction="bottom"
        :options="preselectedOptions"
        :multiple="false"
        :searchable="true"
        :loading="isLoading"
        :internal-search="false"
        :close-on-select="true"
        :options-limit="300"
        :limit="3"
        :limit-text="limitText"
        :max-height="600"
        :show-no-results="false"
        :hide-selected="true"
        :preserveSearch="true"
        @search-change="defaultSearch"
        @input="input"
    >
      <span slot="noResult">
        Oops! No elements found. Consider changing the search query.
      </span>
    </multiselect>
  </div>
</template>
<script>
import axios from 'axios';
import qs from 'qs';
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.min.css';

export default {
  components: {
    Multiselect,
  },
  props: {
    value: {type: Object, default: null},
    url: {type: String, required: true},
  },
  data() {
    return {
      selectedValue: this.value,
      isLoading: false,
      preselectedOptions: [],
    }
  },
  watch: {
    value: function (newValue, oldValue) {
      this.selectedValue = newValue;
    },
  },
  methods: {
    input(event) {
      this.$emit('input', this.selectedValue);
    },
    limitText(count) {
      return `and ${count} other countries`
    },
    defaultSearch(query) {
      this.isLoading = true;

      axios({
        method: 'post',
        url: this.url,
        headers: {'content-type': 'application/x-www-form-urlencoded'},
        data: qs.stringify({name: query,}),
      }).then(response => {
        this.preselectedOptions = response.data;
        this.isLoading = false;
      }).catch(error => {
        this.preselectedOptions = [];
        this.isLoading = false
      });
    }
  }
}
</script>

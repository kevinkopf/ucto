<template>
  <div>
    <label class="typo__label" for="ajax">{{ label }}</label>
    <multiselect
        v-model="value"
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
        @input="$emit('input', value)"
    >
      <template
          slot="option"
          slot-scope="props"
      >
        <div class="option__desc">
                    <span class="option__title">
                        {{ props.option.numeral }} -- {{ props.option.name }}
                    </span>
        </div>
      </template>

      <span slot="singleLabel">
                {{ value.numeral }} -- {{ value.name }}
            </span>

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

export default {
  components: {
    Multiselect,
  },
  props: {
    label: {type: String, default: ''},
    url: {type: String, required: true},
  },
  data() {
    return {
      value: '',
      isLoading: false,
      preselectedOptions: [],
    }
  },
  methods: {
    limitText(count) {
      return `and ${count} other countries`
    },
    clearAll() {
      this.theSelectedOption = [];
    },
    defaultSearch(query) {
      this.isLoading = true;

      axios({
        method: 'post',
        url: this.url,
        headers: {'content-type': 'application/x-www-form-urlencoded'},
        data: qs.stringify({query: query,}),
      }).then(response => {
        this.preselectedOptions = response.data;
        this.isLoading = false;
      }).catch(error => {
        this.preselectedOptions = [];
        this.isLoading = false
      });
    },
  }
}
</script>

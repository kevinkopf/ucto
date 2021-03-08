<template>
  <div>
    <label class="typo__label" for="analyticalAccount">{{ label }} Analytický</label>
    <multiselect
        v-model="selectedAnalyticalAccountValue"
        id="analyticalAccount"
        label="name"
        track-by="id"
        placeholder="Type to search"
        open-direction="bottom"
        :disabled="!account"
        :options="preselectedAnalyticalAccountOptions"
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
        @search-change="accountSearch"
        @input="emitInputEvent"
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
        {{ selectedAnalyticalAccountValue.numeral }} -- {{ selectedAnalyticalAccountValue.name }}
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
    value: {required: true, validator: prop => typeof prop === 'object' || prop === null},
    account: {required: true, validator: prop => typeof prop === 'object' || prop === null},
    accountSearchUrl: {type: String, required: true},
    label: {type: String, default: ''},
  },
  watch: {
    account: function(newVal, oldVal) {
      this.preselectedAnalyticalAccountOptions = newVal.analyticals;
    },
  },
  data() {
    return {
      selectedAnalyticalAccountValue: this.value ? this.value : '',
      isLoading: false,
      preselectedAnalyticalAccountOptions: [],
    }
  },
  methods: {
    limitText(count) {
      return `and ${count} other countries`
    },
    clearAll() {
      this.theSelectedOption = [];
    },
    accountSearch(query) {
      this.isLoading = true;

      axios({
        method: 'post',
        url: this.accountSearchUrl,
        headers: {'content-type': 'application/x-www-form-urlencoded'},
        data: qs.stringify({account: this.account.id, query: query,}),
      }).then(response => {
        this.preselectedAnalyticalAccountOptions = response.data;
        this.isLoading = false;
      }).catch(error => {
        this.preselectedAnalyticalAccountOptions = [];
        this.isLoading = false
      });
    },
    emitInputEvent(value, id) {
      this.$emit('input', value);
    },
  }
}
</script>

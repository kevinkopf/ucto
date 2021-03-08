<template>
  <div>
    <label class="typo__label" for="account">{{ label }}</label>
    <multiselect
        v-model="selectedAccountValue"
        id="account"
        label="name"
        track-by="id"
        placeholder="Type to search"
        open-direction="bottom"
        :options="preselectedAccountOptions"
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
        {{ selectedAccountValue.numeral }} -- {{ selectedAccountValue.name }}
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
    label: {type: String, default: ''},
    accountSearchUrl: {type: String, required: true},
  },
  watch: {
    value: function(value, oldVal) {
      this.selectedAccountValue = value ? value : '';
    },
  },
  data() {
    return {
      selectedAccountValue: this.value ? this.value : '',
      isLoading: false,
      preselectedAccountOptions: [],
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
        data: qs.stringify({query: query,}),
      }).then(response => {
        this.preselectedAccountOptions = response.data;
        this.isLoading = false;
      }).catch(error => {
        this.preselectedAccountOptions = [];
        this.isLoading = false
      });
    },
    emitInputEvent(value, id) {
      this.$emit('input', value);
    },
  }
}
</script>

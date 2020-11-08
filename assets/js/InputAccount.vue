<template>
  <div class="row">
    <div class="col-6">
      <label class="typo__label" for="account">{{ label }}</label>
      <multiselect
          v-model="selectedAccountValue"
          :value="accountValue"
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
          @select="selectAccount"
          @input="$emit('input', selectedAccountValue)"
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
    <div class="col-6">
      <label class="typo__label" for="analyticalAccount">{{ label }} Analytický</label>
      <multiselect
          v-model="selectedAnalyticalAccountValue"
          :value="analyticalAccountValue"
          id="analyticalAccount"
          label="name"
          track-by="id"
          placeholder="Type to search"
          open-direction="bottom"
          :disabled="!selectedAccountValue"
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
          @select="selectAnalyticalAccount"
          @input="$emit('input', selectedAnalyticalAccountValue)"
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
    accountValue: {
      type: Object, default: function () {
        return {};
      }
    },
    analyticalAccountValue: {
      type: Object, default: function () {
        return {};
      }
    },
    label: {type: String, default: ''},
    accountSearchUrl: {type: String, required: true},
  },
  data() {
    return {
      selectedAccountValue: '',
      selectedAnalyticalAccountValue: '',
      isLoading: false,
      preselectedAccountOptions: [],
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
        data: qs.stringify({query: query,}),
      }).then(response => {
        this.preselectedAccountOptions = response.data;
        this.isLoading = false;
      }).catch(error => {
        this.preselectedAccountOptions = [];
        this.isLoading = false
      });
    },
    selectAccount(selectedOption, id) {
      this.preselectedAnalyticalAccountOptions = selectedOption.analyticals;
      this.$emit('select-account', selectedOption);
    },
    selectAnalyticalAccount(selectedOption, id) {
      this.$emit('select-analytical-account', selectedOption);
    }
  }
}
</script>

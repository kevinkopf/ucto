<template>
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-1 p-2 border text-left font-weight-bold">

        </div>
        <div class="col-2 p-2 border-top border-right border-bottom text-left font-weight-bold">
          Účet
        </div>
        <div class="col-8 p-2 border-top border-right border-bottom text-left font-weight-bold">
          Název
        </div>
        <div class="col-1 p-2 border-top border-right border-bottom text-center font-weight-bold">
          Akce
        </div>
      </div>

      <template v-for="(account, index) in accounts.data">
        <div class="row">
          <div class="col-1 p-2 border-left border-bottom text-center">
            <button-arrow
                v-if="account.analyticals.length > 0"
                :indexkey="index">
            </button-arrow>
          </div>
          <div class="col-2 p-2 border-left border-bottom text-left">
            {{ account.numeral }}<br>
          </div>
          <div class="col-8 p-2 border-left border-bottom text-left">
            {{ account.name }}
          </div>
          <div class="col-1 p-2 border-left border-right border-bottom text-center">
            <link-icon
                :args="account"
                inline-template
            >
              <button
                  type="button"
                  class="pt-1 pr-2 pb-1 pl-2 cursor-pointer btn btn-success"
                  @click="emitEvent('accountAnalyticalAdd')"
                  v-b-modal.account-analytical-form
              >
                <i class="fas fa-plus"></i>
              </button>
            </link-icon>
          </div>
        </div>
        <b-collapse
            :id="'collapse-'+index"
            class="row"
        >
          <div class="col-12 border p-3">
            <div class="container p-2">
              <div class="row">
                <div class="col-1 border-left border-right border-top border-bottom">Účet</div>
                <div class="col-9 border-right border-top border-bottom">Název</div>
                <div class="col-2 border-right border-top border-bottom"></div>
              </div>
              <div class="row" v-for="analyticalAccount in account.analyticals">
                <div class="col-1 p-2 border-left border-right border-bottom">{{ analyticalAccount.numeral }}</div>
                <div class="col-9 p-2 border-right border-bottom">{{ analyticalAccount.name }}</div>
                <div class="col-2 p-2 border-right border-bottom">
                  <link-icon
                      :args="{account: account, analyticalAccount: analyticalAccount}"
                      inline-template
                  >
                    <button
                        type="button"
                        class="pt-1 pr-2 pb-1 pl-2 cursor-pointer btn btn-outline-primary"
                        @click="emitEvent('accountAnalyticalEdit')"
                        v-b-modal.account-analytical-form
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                  </link-icon>
                  <link-icon
                      :args="{account: account, analyticalAccount: analyticalAccount}"
                      inline-template
                  >
                    <button
                        type="button"
                        class="pt-1 pr-2 pb-1 pl-2 cursor-pointer btn btn-outline-primary"
                        @click="emitEvent('accountAnalyticalCopy')"
                        v-b-modal.account-analytical-form
                    >
                      <i class="fas fa-copy"></i>
                    </button>
                  </link-icon>
                  <link-icon
                      :args="analyticalAccount.id"
                      inline-template
                  >
                    <button
                        type="button"
                        class="pt-1 pr-2 pb-1 pl-2 cursor-pointer btn btn-danger"
                        @click="emitEvent('accountAnalyticalRemove')"
                        v-b-modal.account-analytical-remove
                    >
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </link-icon>
                </div>
              </div>
            </div>
          </div>
        </b-collapse>
      </template>
    </div>
  </div>
</template>
<script>
import axios from 'axios';
import qs from 'qs';
import ButtonArrow from "./ButtonArrow";
import ComponentLoadingImage from "./ComponentLoadingImage";
import LinkIcon from "./LinkIcon";

export default {
  components: {
    ButtonArrow,
    ComponentLoadingImage,
    LinkIcon,
  },
  props: {
    apiAccountsListingUrl: {type: String, required: true},
  },
  data() {
    return {
      accounts: {
        data: [],
      },
      isLoading: false,
    };
  },
  watch: {
    isLoading(newVal, oldVal) {
      if (newVal === true) {
        this.$root.$emit("is-loading::true");
      } else {
        this.$root.$emit("is-loading::false");
      }
    },
  },
  created() {
    this.$root.$on("account-form::submitted::success", () => this.fetchAccounts());
    this.$root.$on("account-remove::success", () => this.fetchAccounts());
  },
  mounted() {
    this.fetchAccounts();
  },
  methods: {
    emptyAccounts() {
      this.accounts.data = [];
    },
    fetchAccounts() {
      this.emptyAccounts();
      this.isLoading = true;

      axios({
        method: 'post',
        url: this.apiAccountsListingUrl,
        headers: {'content-type': 'application/x-www-form-urlencoded'},
        data: qs.stringify({page: 1, limit: 0}),
      }).then((response) => {
        this.accounts = response.data;
        this.isLoading = false;
      }).catch((error) => {
        this.emptyAccounts();
      });
    },
  },
}
</script>

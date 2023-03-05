<template>
  <div class="card">
    <component-loading-image :is-active="isLoading"></component-loading-image>
    <div class="card-header">
      <slot/>

      <div class="card-tools">
        <component-pagination
            :current-page.sync="transactions.pages.current"
            :total-pages="transactions.pages.total"
            :lower-limit="absolutePageLimits.lower"
            :upper-limit="absolutePageLimits.upper"
            @update:currentPage="fetchTransactions"
        >
        </component-pagination>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-1 p-2 border text-center font-weight-bold">
          Datum
        </div>
        <div class="col-5p5 p-2 border text-left font-weight-bold">
          Popis
        </div>
        <div class="col-2 p-2 border text-left font-weight-bold">
          Doklad
        </div>
        <div class="col-2 p-2 border text-center font-weight-bold">
          Kontakt
        </div>
        <div class="col-1p5 p-2 border text-center font-weight-bold">
          Akce
        </div>
      </div>

      <template v-for="(transaction, index) in transactions.data">
        <b-modal
            :id="'transaction-remove-'+transaction.id"
            title="Odstranit transakci?"
            :no-close-on-backdrop="true"
            cancel-title="Ponechat"
            ok-title="Odstranit"
            @ok="submitRemove(transaction.id)"
        >
          <p class="my-4">Záznam bude nenávratně odstraněn.</p>
        </b-modal>
        <div class="row">
          <div class="col-1 p-2 border text-center">
            {{ transaction.taxableSupplyDate }}
          </div>
          <div class="col-5p5 p-2 border text-left">
            <button-arrow
                :indexkey="index"
            >
            </button-arrow>
            {{ transaction.description }}
          </div>
          <div class="col-2 p-2 border text-left">
            {{ transaction.documentNumber }}
          </div>
          <div class="col-2 p-2 border text-center">
            {{ transaction.contact.name }}
          </div>
          <div class="col-1p5 p-2 border text-center">
            <link-icon
                :args="transaction.id"
                inline-template
            >
              <button
                  type="button"
                  class="px-2 py-1 mx-1 cursor-pointer btn btn-outline-primary btn-sm"
                  :aria-label="id"
                  @click="emitEvent('transactionEdit')"
                  v-b-modal.transaction-form
              >
                <i class="fas fa-edit"></i>
              </button>
            </link-icon>
            <link-icon
                :args="transaction.id"
                inline-template
            >
              <button
                  type="button"
                  class="px-2 py-1 mx-1 cursor-pointer btn btn-outline-primary btn-sm"
                  @click="emitEvent('transactionClone')"
                  v-b-modal.transaction-form
              >
                <i class="cursor-pointer far fa-copy"></i>
              </button>
            </link-icon>
            <link-icon
                :args="transaction.id"
                inline-template
            >
              <button
                  type="button"
                  class="px-2 py-1 mx-1 cursor-pointer btn btn-outline-danger btn-sm"
                  @click="emitEvent('transactionRemove')"
                  v-b-modal="'transaction-remove-' + args"
              >
                <i class="cursor-pointer fas fa-trash-alt"></i>
              </button>
            </link-icon>
          </div>
        </div>
        <b-collapse :id="'collapse-'+index" class="row">
          <div class="col-12 border py-3">
            <div class="container-fluid">
              <div class="row">
                <div class="col-8 p-1 border font-weight-bold">
                  Popis položky
                </div>
                <div class="col-2 p-1 border text-center font-weight-bold">
                  Částka
                </div>
                <div class="col-1 p-1 border text-center font-weight-bold">
                  Má Dáti
                </div>
                <div class="col-1 p-1 border text-center font-weight-bold">
                  Dal
                </div>
              </div>
              <div
                  v-for="trow in transaction.rows"
                  class="row"
              >
                <div class="col-8 p-1 border">
                  {{ trow.description }}
                </div>
                <div class="col-2 p-1 border text-center">
                  {{ (trow.amount / 100) | formatPrice }}
                </div>
                <div class="col-1 p-1 border text-center">
                  {{ trow.debtorsAccount.numeral }}{{ trow.debtorsAnalyticalAccount ? '.' + trow.debtorsAnalyticalAccount.numeral : '' }}
                </div>
                <div class="col-1 p-1 border text-center">
                  {{ trow.creditorsAccount.numeral }}{{ trow.creditorsAnalyticalAccount ? '.' + trow.creditorsAnalyticalAccount.numeral : '' }}
                </div>
              </div>
            </div>
          </div>
        </b-collapse>
      </template>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
      <component-pagination
          :current-page.sync="transactions.pages.current"
          :total-pages="transactions.pages.total"
          :lower-limit="absolutePageLimits.lower"
          :upper-limit="absolutePageLimits.upper"
          @update:currentPage="fetchTransactions"
      >
      </component-pagination>
    </div>
    <!-- /.card-footer-->
  </div>
</template>
<script>
import axios from 'axios';
import qs from 'qs';
import ButtonArrow from "./ButtonArrow";
import ComponentLoadingImage from "./ComponentLoadingImage";
import ComponentPagination from "./ComponentPagination";
import LinkIcon from "./LinkIcon";

export default {
  components: {
    ButtonArrow,
    ComponentLoadingImage,
    ComponentPagination,
    LinkIcon,
  },
  props: {
    transactionsListUrl: {type: String, required: true},
    transactionRemoveUrl: {type: String, required: true},
  },
  data() {
    return {
      transactions: {
        data: [],
        pages: {
          current: 0,
          total: 1,
        },
      },
      pageLimits: {
        lower: 2,
        upper: 2,
      },
      isLoading: false,
    };
  },
  mounted() {
    this.fetchTransactions();
  },
  updated() {
    this.$root.$on("transaction-form::submitted::success", () => this.fetchTransactions());
  },
  computed: {
    absolutePageLimits: function () {
      return {
        lower: this.transactions.pages.current - this.pageLimits.lower,
        upper: this.transactions.pages.current + this.pageLimits.upper,
      };
    },
  },
  methods: {
    emptyTransactions() {
      this.transactions.data = [];
    },
    fetchTransactions(page = 1) {
      this.emptyTransactions();
      this.isLoading = true;
      page = page < 1 ? 1 : page;

      axios({
        method: 'post',
        url: this.transactionsListUrl,
        headers: {'content-type': 'application/x-www-form-urlencoded'},
        data: qs.stringify({page: page, limit: 0}),
      }).then((response) => {
        this.transactions = response.data;
        this.isLoading = false;
      }).catch((error) => {
        this.emptyTransactions();
      });
    },
    submitRemove(id) {
      axios({
        method: 'post',
        url: this.transactionRemoveUrl,
        headers: {'content-type': 'application/x-www-form-urlencoded'},
        data: qs.stringify({id: id}),
      }).then((response) => {
        this.fetchTransactions();
      }).catch((error) => {
        console.log(error);
      });
    }
  },
}
</script>

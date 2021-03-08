<template>
  <div class="row">
    <b-modal
        size="lg"
        id="transaction-form"
        title="Přidat novou transakci"
        :no-close-on-backdrop="true"
        :no-close-on-esc="true"
        :hide-footer="true"
        @close="resetModal"
    >
      <div class="modal-body">
        <form
            :action="form.submitUrl"
            method="post"
            ref="transactionForm"
            @submit="submit"
        >
          <div class="row">
            <div class="col-3">
              <input
                  type="hidden"
                  v-model="payload.id"
              >
              <input-date
                  v-model="payload.taxableSupplyDate"
                  label="Datum"
              ></input-date>
            </div>
            <div class="col-4">
              <input-wrapper
                  label="Číslo dokladu"
                  :validations="validations.documentNumber"
              >
                <input-text
                    v-model="payload.documentNumber"
                ></input-text>
              </input-wrapper>
            </div>
            <div class="col-5">
              <input-contact
                  v-model="payload.contact"
                  :url="contactSearchUrl"
              ></input-contact>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <input-wrapper
                  label="Popis"
                  :validations="validations.description"
              >
                <input-text
                    v-model="payload.description"
                    label="Popis"
                ></input-text>
              </input-wrapper>
            </div>
          </div>
          <hr>
          <template v-for="(row, index) in payload.rows">
            <div class="row">
              <div class="col-9">
                <input-wrapper
                    label="Detailnější Popis"
                >
                  <input-text
                      v-model="row.description"
                      lael="Detailnější Popis"
                  ></input-text>
                </input-wrapper>
              </div>
              <div class="col-3">
                <input-wrapper
                    label="Částka"
                >
                  <input-money
                      v-model="row.amount"
                      label="Částka"
                  ></input-money>
                </input-wrapper>
              </div>
              <div class="col-6">
                <input-account
                    v-model="payload.rows[index].debtorsAccount"
                    label="Má Dáti"
                    :account-search-url="accountSearchUrl"
                ></input-account>
              </div>
              <div class="col-6">
                <input-account-analytical
                    v-model="row.debtorsAnalyticalAccount"
                    label="Má Dáti"
                    :account="row.debtorsAccount"
                    :account-search-url="analyticalAccountSearchUrl"
                ></input-account-analytical>
              </div>
              <div class="col-6">
                <input-account
                    v-model="row.creditorsAccount"
                    label="Dal"
                    :account-search-url="accountSearchUrl"
                ></input-account>
              </div>
              <div class="col-6">
                <input-account-analytical
                    v-model="row.creditorsAnalyticalAccount"
                    label="Dal"
                    :account="row.creditorsAccount"
                    :account-search-url="analyticalAccountSearchUrl"
                ></input-account-analytical>
              </div>
              <div class="col-12 p-3 text-right">
                <button
                    type="button"
                    class="btn btn-outline-danger"
                    @click="removeRow(index)"
                >
                  <i class="p-1 cursor-pointer fas fa-trash-alt"></i>
                </button>
              </div>
            </div>
          </template>
          <hr>
          <div class="row">
            <div class="col-6">
              <button
                  type="button"
                  class="btn btn-primary btn-sm mt-3"
                  @click="addEmptyRow"
              >
                Přidat
              </button>
            </div>
            <div class="col-6 text-right">
              <button
                  type="button"
                  class="btn btn-warning btn-sm mt-3"
                  @click="resetModal"
              >
                Zanechat
              </button>
              <button
                  type="submit"
                  class="btn btn-success btn-sm mt-3"
              >
                Založit
              </button>
            </div>
          </div>
        </form>
      </div>
    </b-modal>
  </div>
</template>
<script>
import axios from 'axios';
import moment from 'moment-timezone';
import qs from 'qs';
import formMixin from "./formMixin";
import {required} from 'vuelidate/lib/validators';
import InputAccount from "./InputAccount";
import InputContact from "./InputContact";
import InputDate from "./InputDate";
import InputMoney from "./InputMoney";
import InputText from "./InputText";
import InputWrapper from "./InputWrapper";
import InputAccountAnalytical from "./InputAccountAnalytical";

export default {
  components: {InputAccountAnalytical, InputAccount, InputContact, InputDate, InputMoney, InputText, InputWrapper,},
  mixins: [formMixin],
  props: {
    transactionDetailUrl: {type: String, required: true},
    contactSearchUrl: {type: String, required: true},
    accountSearchUrl: {type: String, required: true},
    analyticalAccountSearchUrl: {type: String, required: true},
  },
  mounted() {
    window.EventBus.$on('transactionEdit', (id) => {
      this.populateDetails(id);
      this.payload.id = id;
    });
    window.EventBus.$on('transactionClone', (id) => {
      this.populateDetails(id);
    });
    this.payload.taxableSupplyDate = moment().tz('Europe/Prague').toDate();
  },
  methods: {
    z(value) {
      console.log(this.payload);
    },
    populateDetails(id) {
      axios({
        method: 'post',
        url: this.transactionDetailUrl,
        headers: {'content-type': 'application/x-www-form-urlencoded'},
        data: qs.stringify({id: id}),
      }).then((response) => {
        this.payload.taxableSupplyDate = moment(response.data.taxableSupplyDate, 'DD-MM-YYYY').tz('Europe/Prague').toDate();
        this.payload.documentNumber = response.data.documentNumber;
        this.payload.contact = response.data.contact;
        this.payload.description = response.data.description;
        this.payload.rows = response.data.rows;
      }).catch((error) => {
        console.log(error);
      });
    },
    resetModal() {
      this.payload = {
        id: 0,
        taxableSupplyDate: moment().tz('Europe/Prague').toDate(),
        documentNumber: '',
        contact: null,
        description: '',
        rows: [],
      };
      this.addEmptyRow();
      this.$root.$emit("bv::hide::modal", "transaction-form");
    },
    addEmptyRow() {
      this.payload.rows.push({
        description: "",
        amount: "",
        debtorsAccount: null,
        debtorsAnalyticalAccount: null,
        creditorsAccount: null,
        creditorsAnalyticalAccount: null,
      });
    },
    removeRow(index) {
      if (index > -1) {
        this.payload.rows.splice(index, 1);
      }

      if (this.payload.rows.length < 1) {
        this.addEmptyRow();
      }
    },
  },
  validations: {
    payload: {
      taxableSupplyDate: {required},
      documentNumber: {required},
      contact: {required},
      description: {required},
    },
  },
}
</script>

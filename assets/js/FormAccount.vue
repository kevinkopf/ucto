<template>
  <div class="row">
    <b-modal
        size="lg"
        id="account-analytical-form"
        ref="account-analytical-form"
        :title="'Analytický účet pro ' + payload.account.numeral + ' — ' + payload.account.name"
        :no-close-on-backdrop="true"
        :hide-footer="true"
        @close="resetModal"
    >
      <div class="modal-body">
        <form
            :action="form.submitUrl"
            method="post"
            ref="contactForm"
        >
          <div class="row">
            <div class="col-4">
              <input
                  type="hidden"
                  v-model="payload.id"
              >
              <input
                  type="hidden"
                  v-model="payload.account"
              >
              <input-wrapper
                  label="Účet"
                  :validations="validations.numeral"
              >
                <input-text
                    v-model="payload.numeral"
                ></input-text>
              </input-wrapper>
            </div>
            <div class="col-8">
              <input-wrapper
                  label="Název"
                  :validations="validations.name"
              >
                <input-text
                    v-model="payload.name"
                ></input-text>
              </input-wrapper>
            </div>
          </div>
          <div class="row">
            <div class="col-12 text-right">
              <button
                  type="button"
                  class="btn btn-warning btn-sm mt-3"
                  @click="closeModal"
              >
                Zanechat
              </button>
              <button
                  type="button"
                  class="btn btn-success btn-sm mt-3"
                  @click="submit"
                  @on-success="onSuccess"
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
import {required} from 'vuelidate/lib/validators';
import formMixin from "./formMixin";
import InputText from "./InputText";
import InputWrapper from "./InputWrapper";

export default {
  components: {InputWrapper, InputText,},
  mixins: [formMixin],
  mounted() {
    this.$root.$on('accountAnalyticalAdd', (account) => {
      console.log(account);
      this.payload.account = account;
    });
    this.$root.$on('accountAnalyticalEdit', (argumentObject) => {
      this.payload.id = argumentObject.analyticalAccount.id;
      this.payload.name = argumentObject.analyticalAccount.name;
      this.payload.numeral = argumentObject.analyticalAccount.numeral;
      this.payload.account = argumentObject.account;
    });
    this.$root.$on('accountAnalyticalCopy', (argumentObject) => {
      this.payload.name = argumentObject.analyticalAccount.name;
      this.payload.numeral = argumentObject.analyticalAccount.numeral;
      this.payload.account = argumentObject.account;
    });
  },
  methods: {
    onSuccess() {
      this.closeModal();
      this.$root.$emit("account-form::submitted::success", "account-analytical-form");
    },
    closeModal() {
      this.$refs['account-analytical-form'].hide();
      this.resetModal();
    },
    resetModal() {
      this.payload = {
        id: "",
        numeral: "",
        name: "",
        account: "",
      }
    },
  },
  validations: {
    payload: {
      numeral: {required},
      name: {required},
    },
  },
}
</script>

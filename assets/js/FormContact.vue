<template>
  <div class="row">
    <b-modal
        size="lg"
        id="contact-form"
        ref="contact-form"
        title="Založení kontaktu"
        :no-close-on-backdrop="true"
        :hide-footer="true"
        @close="resetModal"
    >
      <div class="modal-body">
        <form
            :action="form.submitUrl"
            method="post"
            ref="contactForm"
            @submit="submit"
        >
          <div class="row">
            <div class="col-4">
              <input
                  type="hidden"
                  v-model="payload.id"
              >
              <input-wrapper
                  label="IČ"
                  :validations="validations.registrationNumber"
              >
                <input-text
                    v-model="payload.registrationNumber"
                ></input-text>
              </input-wrapper>
            </div>
            <div class="col-2 text-center">
              <div class="form-group mt-4p5">
                <button
                    disabled
                    type="button"
                    class="btn btn-outline-success disabled"
                    @click="fetchDetailsByRegNumber()"
                >
                  ARES
                </button>
              </div>
            </div>
            <div class="col-6">
              <input-wrapper
                  label="Název"
                  :validations="validations.name"
              >
                <input-text
                    v-model="payload.name"
                ></input-text>
              </input-wrapper>
            </div>
            <div class="col-3">
              <input-checkbox
                  label="Plátce DPH"
                  v-model="payload.isVatPayer"
              ></input-checkbox>
            </div>
            <div class="col-4">
              <input-wrapper
                  label="Kód státu"
                  :validations="validations.vatNumberPrefix"
              >
                <input-text
                    v-model="payload.vatNumberPrefix"
                ></input-text>
              </input-wrapper>
            </div>
            <div class="col-5">
              <input-wrapper
                  label="Ident. číslo"
                  :validations="validations.vatNumber"
              >
                <input-text
                    v-model="payload.vatNumber"
                ></input-text>
              </input-wrapper>
            </div>
            <div class="col-12">
              <input-wrapper
                  label="Sídlo"
                  :validations="validations.address"
              >
                <input-text
                    v-model="payload.address"
                ></input-text>
              </input-wrapper>
            </div>
            <div class="col-6">
              <input-wrapper
                  label="Telefon"
                  :validations="validations.phone"
              >
                <input-text
                    v-model="payload.phone"
                ></input-text>
              </input-wrapper>
            </div>
            <div class="col-6">
              <input-wrapper
                  label="E-mail"
                  :validations="validations.email"
              >
                <input-text
                    v-model="payload.email"
                ></input-text>
              </input-wrapper>
            </div>
          </div>
          <div class="row">
            <div class="col-12 text-right">
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
import {required} from 'vuelidate/lib/validators';
import axios from "axios";
import formMixin from "./formMixin";
import InputCheckbox from "./InputCheckbox";
import InputText from "./InputText";
import InputWrapper from "./InputWrapper";

export default {
  components: {
    InputCheckbox,
    InputText,
    InputWrapper,
  },
  mixins: [formMixin],
  mounted() {
    window.EventBus.$on('contactEdit', (id) => {
      this.populateDetails(id);
      this.payload.id = id;
    });
  },
  props: {
    contactDetailsUrl: {type: String, required: true},
  },
  methods: {
    searchContacts(url, options) {
      axios({
        method: 'get',
        url: url,
      }).then(function (response) {
        options = response.data;
      }).catch(function (error) {
        options = [];
      });
    },
    fetchDetailsByRegNumber() {
      if (this.payload.registrationNumber.length > 0) {
        axios.get(
            'https://wwwinfo.mfcr.cz/cgi-bin/ares/darv_std.cgi?ico=' + this.payload.registrationNumber,
            {
              headers: {
                'Access-Control-Allow-Origin': '*',
              },
            }
        ).then((response) => {
          console.log(response);
        });
      }
    },
    populateDetails(id) {
      axios({
        method: 'get',
        url: this.contactDetailsUrl + '/' + id,
      }).then((response) => {
        this.payload.name = response.data.name;
        this.payload.address = response.data.address;
        this.payload.phone = response.data.phone;
        this.payload.email = response.data.email;
        this.payload.registrationNumber = response.data.registrationNumber;
        this.payload.isVatPayer = response.data.vatPayer;
        this.payload.vatNumberPrefix = response.data.vatNumberPrefix;
        this.payload.vatNumber = response.data.vatNumber;
      }).catch((error) => {
        console.log(error);
      });
    },
    resetModal() {
      this.payload = {
        id: "",
        name: "",
        address: "",
        phone: "",
        email: "",
        registrationNumber: "",
        isVatPayer: false,
        vatNumberPrefix: "",
        vatNumber: "",
      };

      this.$root.$emit("bv::hide::modal", "contact-form");
    },
  },
  validations: {
    payload: {
      name: {required},
      address: {required},
      registrationNumber: {required},
    },
  },
}
</script>

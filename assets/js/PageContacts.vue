<template>
  <div class="card">
    <div class="card-header">
      <slot/>

      <div class="card-tools">
        <component-pagination
            :current-page.sync="contacts.pages.current"
            :total-pages="contacts.pages.total"
            :lower-limit="absolutePageLimits.lower"
            :upper-limit="absolutePageLimits.upper"
            @update:currentPage="fetchContacts"
        >
        </component-pagination>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-1 p-2 border text-center font-weight-bold">

        </div>
        <div class="col-2 p-2 border text-left font-weight-bold">
          IČ / DIČ
        </div>
        <div class="col-8 p-2 border text-left font-weight-bold">
          Název
        </div>
        <div class="col-1 p-2 border text-center font-weight-bold">
          Akce
        </div>
      </div>

      <template v-for="(contact, index) in contacts.data">
        <b-modal
            :id="'contact-remove-'+contact.id"
            title="Odstranit kontakt?"
            :no-close-on-backdrop="true"
            cancel-title="Ponechat"
            ok-title="Odstranit"
            @ok="submitRemove(contactRemoveUrl + '?id=' + contact.id)"
        >
          <p class="my-4">Záznam bude nenávratně odstraněn.</p>
        </b-modal>
        <div class="row">
          <div class="col-1 p-2 border-left border-bottom text-center">
            <button-arrow
                :indexkey="index">
            </button-arrow>
          </div>
          <div class="col-2 p-2 border-left border-bottom text-left">
            {{ contact.registrationNumber }}<br>
            <span v-if="contact.vatPayer">
                    {{ contact.vatNumberPrefix }}{{ contact.vatNumber }}
                    <i class="fas fa-check text-success"></i>
                </span>
          </div>
          <div class="col-8 p-2 border-left border-bottom text-left">
            {{ contact.name }}
          </div>
          <div class="col-1 p-2 border-left border-right border-bottom text-center">
            <link-icon
                :args="contact.id"
                inline-template
            >
              <button
                  type="button"
                  class="pt-1 pr-2 pb-1 pl-2 cursor-pointer btn btn-outline-primary"
                  @click="emitEvent('contactEdit')"
                  v-b-modal.contact-form
              >
                <i class="fas fa-edit"></i>
              </button>
            </link-icon>
            <link-icon
                :args="contact.id"
                inline-template
            >
              <button
                  type="button"
                  class="pt-1 pr-2 pb-1 pl-2 cursor-pointer btn btn-outline-danger"
                  @click="emitEvent('contactRemove')"
                  v-b-modal="'contact-remove-' + args"
                  :disabled="contact.transactionsCount > 0"
              >
                <i class="cursor-pointer fas fa-trash-alt"></i>
              </button>
            </link-icon>
          </div>
        </div>
        <b-collapse
            :id="'collapse-'+index"
            class="row"
        >
          <div class="col-1 border-left border-bottom"></div>
          <div class="col-2 p-2 border-bottom border-right text-right font-weight-bold">
            Plátce DPH
          </div>
          <div class="col-9 p-2 border-bottom border-right">
            <template v-if="contact.vatPayer">
              <i class="fas fa-check text-success"></i>
            </template>
            <template v-if="!contact.vatPayer">
              <i class="far fa-times-circle text-danger"></i>
            </template>
          </div>
          <div class="col-1 border-left border-bottom"></div>
          <div class="col-2 p-2 border-bottom border-right text-right font-weight-bold">
            Sídlo
          </div>
          <div class="col-9 p-2 border-bottom border-right">
            {{ contact.address }}
          </div>
          <div class="col-1 border-left border-bottom"></div>
          <div class="col-2 p-2 border-bottom border-right text-right font-weight-bold">
            Telefon
          </div>
          <div class="col-9 p-2 border-bottom border-right">
            {{ contact.phone }}
          </div>
          <div class="col-1 border-left border-bottom"></div>
          <div class="col-2 p-2 border-bottom border-right text-right font-weight-bold">
            Email
          </div>
          <div class="col-9 p-2 border-bottom border-right">
            {{ contact.email }}
          </div>
        </b-collapse>
      </template>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
      <component-pagination
          :current-page.sync="contacts.pages.current"
          :total-pages="contacts.pages.total"
          :lower-limit="absolutePageLimits.lower"
          :upper-limit="absolutePageLimits.upper"
          @update:currentPage="fetchContacts"
      >
      </component-pagination>
    </div>
    <!-- /.card-footer-->
  </div>
</template>
<script>
import axios from "axios";
import qs from 'qs';
import ButtonArrow from "./ButtonArrow";
import ComponentPagination from "./ComponentPagination";
import LinkIcon from "./LinkIcon";

export default {
  components: {
    ButtonArrow,
    ComponentPagination,
    LinkIcon,
  },
  props: {
    contactsListUrl: {type: String, required: true},
    contactRemoveUrl: {type: String, required: true},
  },
  data() {
    return {
      contacts: {
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
      isLoading: true,
    };
  },
  mounted() {
    this.fetchContacts();
  },
  computed: {
    absolutePageLimits: function () {
      return {
        lower: this.contacts.pages.current - this.pageLimits.lower,
        upper: this.contacts.pages.current + this.pageLimits.upper,
      };
    },
  },
  methods: {
    emptyContacts() {
      this.contacts.data = [];
    },
    fetchContacts(page = 1) {
      this.emptyContacts();
      this.isLoading = true;
      page = page < 1 ? 1 : page;

      axios({
        method: 'post',
        url: this.contactsListUrl,
        headers: {'content-type': 'application/x-www-form-urlencoded'},
        data: qs.stringify({page: page, limit: 0}),
      }).then((response) => {
        this.contacts = response.data;
        this.isLoading = false;
      }).catch((error) => {
        this.emptyContacts();
      });
    },
    submitRemove(url) {
      window.location.replace(url);
    }
  },
}
</script>

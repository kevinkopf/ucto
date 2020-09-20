import axios from 'axios';
import {cloneDeep} from 'lodash';
import validationMixin from './validationMixin';

export default {
  mixins: [validationMixin],
  model: {
    prop: 'form',
    event: 'synchronize-form',
  },
  props: {
    form: {type: Object, required: true},
    token: {type: String, required: true},
  },
  data() {
    return {
      payload: cloneDeep(this.form.payload),
      view: cloneDeep(this.form.view),
    };
  },
  created() {
    this.tui();
  },
  watch: {
    form: {
      deep: true,
      handler() {
        this.sync();
      },
    },
  },
  computed: {
    payloadAsJson() {
      return JSON.stringify(this.payload);
    },
  },
  methods: {
    sync() {
      this.payload = cloneDeep(this.form.payload);
      this.view = cloneDeep(this.form.view);
    },
    submit(event) {
      if (!this.isValid()) {
        event.preventDefault();
        this.$v.$touch();
      }
      const form = event.target;
      form.appendChild(this.createPayloadInput());
      form.appendChild(this.createTokenInput());
      console.log(this.payload);
    },
    submitAjax(payload = this.payload, token = this.token, method = 'post') {
      const params = new URLSearchParams();
      params.append('payload', JSON.stringify(payload));
      params.append('token', token);

      return axios[method](this.form.submitUrl, params)
          .then((response) => {
            this.renderMessagesForSuccess(response);
            if (response.data.form) {
              this.$emit('synchronize-form', response.data.form);
            }
            return response;
          })
          .catch((error) => {
            this.renderMessagesForFailure(error);
            throw error;
          });
    },
    renderMessagesForSuccess(response) {
      if (response.data && response.data.messages) {
        this.renderMessages(response.data.messages);
      }
    },
    renderMessagesForFailure(error) {
      if (error.response && error.response.data && error.response.data.messages) {
        this.renderMessages(error.response.data.messages);
        return;
      }
      this.$toasted.error(window.generalPurposeErrorMessage);
    },
    renderMessages(messages) {
      messages.forEach((message) => {
        this.$toasted[message.type](message.body);
      });
    },
    createPayloadInput() {
      const input = document.createElement('INPUT');
      input.setAttribute('type', 'hidden');
      input.setAttribute('name', 'payload');
      input.setAttribute('value', this.payloadAsJson);
      return input;
    },
    createTokenInput() {
      const input = document.createElement('INPUT');
      input.setAttribute('type', 'hidden');
      input.setAttribute('name', 'token');
      input.setAttribute('value', this.token);
      return input;
    },
  },
};

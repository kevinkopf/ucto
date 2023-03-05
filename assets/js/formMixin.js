import axios from 'axios';
import {cloneDeep} from 'lodash';
import qs from 'qs';
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

      axios({
        method: 'post',
        url: this.form.submitUrl,
        headers: {'content-type': 'application/x-www-form-urlencoded'},
        data: qs.stringify({
          payload: JSON.stringify(this.payload),
          token: this.token
        }),
      }).then((response) => {
        console.log(response);
        if (this.onSuccess) {
          this.onSuccess();
        }
      }).catch((error) => {
        console.log(error);
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

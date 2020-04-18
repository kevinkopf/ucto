<template>
  <div :class="{ 'form-group': !simple, 'is-required': required }">
    <input-header
      v-if="!simple"
      v-bind="{ id, label, labelExtra, labelExtraShow, instructions, instructionsExtra, more }"
    >
      <slot/>
    </input-header>

    <div class="input-group">
      <input
        v-model="text"
        :readonly="readonly"
        ref="input"
        v-bind="attributes"
        @change="customChange"
        @input="input"
        @blur="blur"
        @keyup.esc="blur"
      >
      <input-button-search v-if="$attrs.type === 'search'"/>
      <slot name="button"/>
    </div>

    <validation-error
      v-model="isShowingError"
      v-bind="{ asyncValidation, asyncValidationComplete, serverError, validation, inputValue: value }"
    />
  </div>
</template>

<script>
import InputButtonSearch from './InputButtonSearch.vue';
import InputHeader from './InputHeader.vue';
import ValidationError from './ValidationError.vue';

export default {
  components: {
    InputButtonSearch,
    InputHeader,
    ValidationError,
  },

  props: {
    label: { type: String, default: '' },
    labelExtra: { type: String, default: '' },
    labelExtraShow: { type: Boolean, default: true },
    instructions: { type: String, default: '' },
    instructionsExtra: { type: String, default: '' },
    more: { type: String, default: '' },
    id: { type: String, required: true },
    name: { type: String, default: '' },
    value: { type: String, required: true },
    asyncValidation: { type: Boolean, default: false },
    asyncValidationComplete: { type: Boolean, default: false },
    serverError: { type: String, default: '' },
    validation: { type: Object, required: false, default: null },
    simple: { type: Boolean, default: false },
    readonly: { type: Boolean, default: false },
  },

  data() {
    return {
      isShowingError: false,
      text: this.value,
    };
  },

  computed: {
    required() {
      return Boolean(this.validation && this.validation.$params.required);
    },

    validationColor() {
      return {
        'is-invalid': this.isShowingError,
        'is-valid': !this.isShowingError && this.validation && this.validation.$dirty && this.value.length > 0 && (this.asyncValidation ? this.asyncValidationComplete : true),
      };
    },

    attributes() {
      return {
        id: this.id,
        name: this.name,
        value: this.value,
        required: this.required,
        class: ['form-control', this.validationColor],
        type: this.resolveType(),
        ...this.$attrs,
      };
    },
  },

  watch: {
    value() {
      this.text = this.value;
    },
  },
  methods: {
    input(event) {
      if (!this.asyncValidation) {
        this.$emit('input', event.target.value);
      }

      if (this.validation) {
        this.validation.$reset();
      }
    },

    blur(event) {
      if (event.target.value && this.attributes.type === 'url') {
        this.$emit('input', (prependHttp(event.target.value)));
      }

      this.$refs.input.blur();
      if (this.asyncValidation) {
        this.$emit('input', event.target.value);
      }

      this.$emit('blur');

      if (this.validation) {
        this.validation.$touch();
      }
    },
    resolveType() {
      if (this.$attrs.type) {
        return this.$attrs.type;
      }

      if (this.validation && this.validation.hasOwnProperty('email')) {
        return 'email';
      }

      if (this.validation && this.validation.hasOwnProperty('url')) {
        return 'url';
      }

      return 'text';
    },
    customChange(event) {
      this.$emit('change', this.text);
    },
  },
};
</script>

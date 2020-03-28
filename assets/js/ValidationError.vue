<template>
  <div
    v-if="error"
    class="invalid-feedback"
    v-html="error"
  />
</template>

<script>
export default {
  props: {
    asyncValidation: {
      type: Boolean,
      default: false,
    },
    asyncValidationComplete: {
      type: Boolean,
      default: false,
    },
    serverError: {
      type: String,
      default: '',
    },
    validation: {
      type: Object,
      required: false,
      default: null,
    },
    inputValue: {
      type: [String, Boolean, Array, Object, Date, Number],
      required: false,
      default: null,
    },
    value: {
      type: Boolean,
      default: null,
    },
    overridenErrorMessage: {
      type: String,
      default: null,
    },
  },

  data() {
    return {
      wasFieldManipulated: false,
    };
  },

  computed: {
    rules() {
      if (!this.validation) {
        return [];
      }

      return Object.keys(this.validation.$params);
    },

    failingRules() {
      if (this.validation && this.validation.$each && this.validation.$invalid) {
        return ['$each'];
      }

      return this.rules.filter(rule => !this.validation[rule]);
    },

    errorMessages() {
      return this.failingRules.map(rule => window.validationErrorTranslations[this.overridenErrorMessage ? this.overridenErrorMessage : rule]);
    },

    shouldShowServerError() {
      return Boolean(this.serverError) && !this.wasFieldManipulated;
    },

    shouldShowClientError() {
      const condition = this.errorMessages.length > 0 && this.validation.$dirty;

      if (this.asyncValidation) {
        return condition && this.asyncValidationComplete;
      }

      return condition;
    },

    error() {
      if (this.shouldShowServerError) {
        this.$emit('input', true);
        return this.serverError;
      }

      if (this.shouldShowClientError) {
        this.$emit('input', true);
        return this.errorMessages[0];
      }

      this.$emit('input', false);
      return '';
    },
  },

  watch: {
    inputValue() {
      this.wasFieldManipulated = true;
    },
  },
};
</script>

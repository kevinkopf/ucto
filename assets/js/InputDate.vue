<template>
  <div
    :class="{ 'is-required': required }"
    class="form-group"
  >
    <input-header
      v-bind="{ id, label, labelExtra, instructions, instructionsExtra, more }"
    >
      <slot/>
    </input-header>

    <div
      class="input-group"
      @click="wasInteractedWith = true"
    >
      <datepicker
        v-on-clickaway="blur"
        :disabled-dates="disabledDates"
        :language="localization"
        :input-class="{ 'form-control': true, 'bg-white': true, 'is-invalid': isShowingError }"
        :value="value"
        wrapper-class="w-50"
        monday-first
        @input="input"
      />
    </div>

    <input type="hidden" :id="id" :name="name" :value="value ? value.toISOString() : value">

    <validation-error
      v-model="isShowingError"
      v-bind="{ serverError, validation, inputValue: value }"
    />
  </div>
</template>

<script>
import { directive as onClickaway } from 'vue-clickaway2';
import Datepicker from 'vuejs-datepicker';
import * as localizations from 'vuejs-datepicker/dist/locale';
import InputHeader from './InputHeader.vue';
import ValidationError from './ValidationError.vue';

export default {
  components: {
    Datepicker,
    InputHeader,
    ValidationError,
  },

  directives: {
    onClickaway,
  },

  props: {
    label: { type: String, default: '' },
    labelExtra: { type: String, default: '' },
    instructions: { type: String, default: '' },
    instructionsExtra: { type: String, default: '' },
    more: { type: String, default: '' },
    id: { type: String, required: true },
    name: { type: String, default: '' },
    value: { type: Date, default: null },
    serverError: { type: String, default: '' },
    validation: { type: Object, required: false, default: null },
    disabledAfterToday: { type: Boolean, default: true },
  },

  data() {
    return {
      isShowingError: false,
      wasInteractedWith: false,
    };
  },

  computed: {
    localization() {
      return localizations[window.locale];
    },

    required() {
      return Boolean(this.validation && this.validation.$params.required);
    },

    validationColor() {
      return {
        'is-invalid': this.isShowingError,
        'is-valid': !this.isShowingError && this.validation && this.validation.$dirty && this.value.length > 0,
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

    disabledDates() {
      if (!this.disabledAfterToday) {
        return null;
      }

      return {
        from: new Date(),
      };
    },
  },

  methods: {
    input(event) {
      this.$emit('input', event);

      if (this.validation) {
        this.validation.$reset();
      }
    },

    blur() {
      if (this.validation) {
        if (!this.wasInteractedWith) {
          return;
        }

        this.validation.$touch();
      }
    },
  },
};
</script>

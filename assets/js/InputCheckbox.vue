<template>
  <div>
    <div
      v-if="!inlineLabel"
      class="text-center"
      style="height: 32px;  "
    >
      {{ label }}
    </div>
    <div class="form-check">
      <div
        class="custom-control custom-checkbox"
        :class="{ 'text-center pt-2': !inlineLabel }"
        style="height: 38px;"
      >
        <input
          v-bind="attributes"
          @change="updateValue"
        >
          <label
            :id="`label-for-${id}`"
            :for="id"
            class="custom-control-label"
          >
            <slot v-if="inlineLabel">
              {{ label }}
            </slot>
          </label>
      </div>
    </div>

    <validation-error
      v-model="isShowingError"
      v-bind="{ serverError, validation, inputValue: value }"
    />
  </div>
</template>

<script>
import ValidationError from './ValidationError.vue';

export default {
  components: { ValidationError },

  props: {
    label: { type: String, default: '' },
    id: { type: String, required: true },
    name: { type: String, default: '' },
    value: { type: Boolean, default: false },
    htmlValue: { type: String, default: '1' },
    serverError: { type: String, default: '' },
    validation: { type: Object, required: false, default: null },
    inlineLabel: { type: Boolean, default: true },
  },

  data() {
    return {
      isShowingError: false,
    };
  },

  computed: {
    attributes() {
      return {
        id: this.id,
        name: this.name,
        checked: this.value,
        value: this.htmlValue,
        required: this.required,
        class: ['custom-control-input'],
        type: 'checkbox',
        ...this.$attrs,
      };
    },
  },

  methods: {
    updateValue() {
      this.$emit('input', !this.value);

      if (this.validation) {
        this.validation.$touch();
      }
    },
  },
};
</script>

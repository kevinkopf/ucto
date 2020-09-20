<template>
  <div class="input-group">
    <input
        :class="{
        'is-valid': validations && validations.$success,
        'is-invalid': validations && validations.$errorMessage,
      }"
        :readonly="readonly"
        :type="type"
        :value="value"
        class="form-control"
        @input="$emit('input', $event.target.value)"
    >
  </div>
</template>

<script>
export default {
  props: {
    label: {type: String, default: ''},
    labelExtra: {type: String, default: ''},
    labelExtraShow: {type: Boolean, default: true},
    instructions: {type: String, default: ''},
    instructionsExtra: {type: String, default: ''},
    more: {type: String, default: ''},
    asyncValidation: {type: Boolean, default: false},
    asyncValidationComplete: {type: Boolean, default: false},
    serverError: {type: String, default: ''},
    validation: {type: Object, required: false, default: null},
    simple: {type: Boolean, default: false},
    readonly: {type: Boolean, default: false},
    type: {type: String, default: 'text'},
    validations: {type: Object, default: null},
    value: {type: String, required: true},
  },
};
</script>

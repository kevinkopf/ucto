<template>
  <div
    :class="{
      'form-group': !simple && !disableFormGroup,
      'is-required': typeof validations === 'object' && validations !== null && validations.$required,
    }"
  >
    <div
      v-if="!simple"
      class="d-flex flex-wrap"
    >
      <div class="d-flex mr-auto">
        <label class="pr-2">
          {{ label }}
        </label>

        <button-tooltip
          v-if="labelExtra"
          :text="labelExtra"
        />
      </div>

      <div class="d-inline-flex flex-wrap justify-content-end align-items-center mb-2">
        <p
          v-if="instructions"
          class="text-left text-secondary mb-0 ml-3"
        >
          <small>{{ instructions }}</small>
        </p>
      </div>
    </div>

    <slot/>

    <div
      v-if="typeof validations === 'object' && validations !== null && validations.$errorMessage"
      class="invalid-feedback text-center"
      v-html="validations.$errorMessage"
    />
  </div>
</template>

<script>
// import ButtonTooltip from '../ButtonTooltip.vue';

export default {
  components: {
    // ButtonTooltip,
  },

  props: {
    disableFormGroup: { type: Boolean, default: false },
    error: { type: String, default: null },
    instructions: { type: String, default: null },
    label: { type: String, default: null },
    labelExtra: { type: String, default: null },
    simple: { type: Boolean, default: false },
    validations: { type: Object, default: null },
  },
};
</script>

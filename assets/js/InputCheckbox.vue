<template>
  <div>
    <div>
        <input
            :id="id"
            :checked="value"
            type="checkbox"
            @change="$emit('input', $event.target.checked)"
        >
        <label
            :id="`label-for-${id}`"
            :for="id"
            class="custom-checkbox-label"
        >
          <slot>
            <span v-html="label"/>
          </slot>
          <span
              v-if="validations && (validations.$params.required || validations.$required)"
              class="required-mark ml-1"
          />
        </label>
    </div>
    <div
        v-if="validations && validations.$errorMessage"
        class="invalid-feedback"
        v-html="validations && validations.$errorMessage"
    />
  </div>

</template>

<script>

export default {

  props: {
    id: {type: String, required: true},
    label: {type: String, required: true},
    value: {type: Boolean, default: false},
    validations: {type: Object, default: null},
  },
};
</script>

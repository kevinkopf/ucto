<template>
  <div class="d-flex flex-wrap">
    <div class="mr-auto">
      <label :for="id">
        {{ label }}
      </label>
    </div>

    <slot name="after-tooltip" />

    <div class="d-inline-flex flex-wrap justify-content-end align-items-center mb-2">
      <p
        v-if="instructions"
        class="text-left text-secondary mb-0 ml-3"
      >
        <small>{{ instructions }}</small>
      </p>

      <a
        v-if="more"
        :href="more"
        class="ml-3"
      >
        {{ $t('more') }}
      </a>

      <slot/>

    </div>
  </div>
</template>

<script>
export default {
  props: {
    id: { type: String, default: null },
    label: { type: String, default: '' },
    labelExtra: { type: String, default: '' },
    labelExtraShow: { type: Boolean, default: true },
    instructions: { type: String, default: '' },
    instructionsExtra: { type: String, default: '' },
    more: { type: String, default: '' },
  },
};
</script>

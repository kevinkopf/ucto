<template>
    <b-button
            v-b-toggle="'collapse-' + indexkey"
            class="float-right"
            variant="primary"
            @click="toggled = !toggled"
    >
        <i
                class="fas fa-angle-double-right fa-spin-partial"
                :class="{ 'fa-spin-partial-90': toggled }"
        ></i>
    </b-button>
</template>
<script>
    export default {
        props: {
            indexkey: { type: Number, required: true },
        },
        data() {
            return {
                toggled: false,
            };
        },
    }
</script>

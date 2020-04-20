<template>
    <div>
        <input-text
            :id="id + '_text'"
            :value="model"
            @change="calculateNormalizedAmount"
        >
        </input-text>

        <input type="hidden" :id="id" :name="name" :value="this.normalizedAmount">
    </div>
</template>
<script>
    import InputText from "./InputText";

    export default {
        components: {
            InputText
        },
        props: {
            id: { type: String, required: true },
            name: { type: String, default: '' },
            value: { type: String, default: '' },
        },
        data() {
            return {
                amount: "",
                normalizedAmount: 0,
            };
        },
        computed: {
            model: {
                get() {
                    if(this.value) {
                        this.amount = (this.value / 100).toString();
                        this.calculateNormalizedAmount(this.amount);
                    }

                    return this.amount;
                },
                set(val){
                    this.$emit('input', val.toString());
                }
            },
        },
        methods: {
            calculateNormalizedAmount(amount) {
                const tempAmount = Number(amount);

                if(typeof tempAmount === 'number') {
                    this.normalizedAmount = parseInt(tempAmount * 100);
                } else {
                    this.normalizedAmount = 0;
                }
            },
        },
    }
</script>

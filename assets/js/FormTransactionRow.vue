<template>
    <span>
        <template v-for="(child, index) in this.model">
                <hr>
            <div class="row">
                <div class="col-9">
                    <input-text
                            v-model="child.description"
                            :id="id + '_' + index + '_description'"
                            :name="name + '[' + index + '][description]'"
                    ></input-text>
                </div>
                <div class="col-3">
                    <input-money
                            :value="child.amount.toString()"
                            :id="id + '_' + index + '_amount'"
                            :name="name + '[' + index + '][amount]'"
                    ></input-money>
                </div>
                <div class="col-6">
                    <input-account
                            :value="child.debtorsAccount ? child.debtorsAccount : {}"
                            :id="id + '_' + index + '_debtorsAccount'"
                            :name="name + '[' + index + '][debtorsAccount]'"
                            :url="url"
                    ></input-account>
                </div>
                <div class="col-6">
                    <input-account
                            :value="child.creditorsAccount ? child.creditorsAccount : {}"
                            :id="id + '_' + index + '_creditorsAccount'"
                            :name="name + '[' + index + '][creditorsAccount]'"
                            :url="url"
                    ></input-account>
                </div>
            </div>
        </template>
        <div class="row">
            <div class="col-12">
                <button
                        type="button"
                        class="btn btn-primary btn-sm mt-3"
                        @click="addEmptyRow"
                >Přidat</button>
            </div>
        </div>
    </span>
</template>
<script>
    import InputAccount from "./InputAccount";
    import InputMoney from "./InputMoney";
    import InputText from "./InputText";

    export default {
        components: {
            InputAccount,
            InputMoney,
            InputText,
        },
        props: {
            id: { type: String, required: true },
            name: { type: String, default: '' },
            value: { type: Array, required: true },
            url: {type:String, required: true},
        },
        data() {
            return {
                rows: [],
            };
        },
        computed: {
            model: {
                get() {
                    if(this.value.length > 0)
                    {
                        this.rows = this.value;
                    }

                    return this.rows;
                },
                set(val){
                    this.$emit('input', val)
                }
            },
        },
        beforeMount() {
            if(!this.initialValue)
            {
                this.addEmptyRow();
            }
        },
        methods: {
            addEmptyRow() {
                this.rows.push({
                    description: "",
                    amount: "",
                    debtorsAccount: "",
                    creditorsAccount: "",
                });
            },
        }
    }
</script>

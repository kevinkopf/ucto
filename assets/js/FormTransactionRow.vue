<template>
    <span>
        <template v-for="(child, index) in this.rows">
                <hr>
            <div class="row">
                <div class="col-9">
                    <input-text
                            v-model="child.description"
                            v-bind="child.description"
                            :id="id + '_' + index + '_description'"
                            :name="name + '[' + index + '][description]'"
                    ></input-text>
                </div>
                <div class="col-3">
                    <input-money
                            v-model="child.amount"
                            v-bind="child.amount"
                            :id="id + '_' + index + '_amount'"
                            :name="name + '[' + index + '][amount]'"
                    ></input-money>
                </div>
                <div class="col-6">
                    <input-account
                            v-model="child.debtorsAccount"
                            v-bind="child.debtorsAccount"
                            :id="id + '_' + index + '_debtorsAccount'"
                            :name="name + '[' + index + '][debtorsAccount]'"
                            :url="url"
                    ></input-account>
                </div>
                <div class="col-6">
                    <input-account
                            v-model="child.creditorsAccount"
                            v-bind="child.creditorsAccount"
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
            url: {type:String, required: true},
        },
        data() {
            return {
                rows: [],
            };
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

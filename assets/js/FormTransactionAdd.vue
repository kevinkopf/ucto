<script>
    import axios from 'axios';
    import { required } from 'vuelidate/lib/validators';
    import FormTransactionRow from "./FormTransactionRow";
    import InputContact from "./InputContact";
    import InputDate from "./InputDate";
    import InputText from "./InputText";

    export default {
        components: {
            FormTransactionRow,
            InputContact,
            InputDate,
            InputText,
        },
        props: {
            id: { type: Object, required: true },
            taxableSupplyDate: { type: Object, required: true },
            contact: { type: Object, required: true },
            description: { type: Object, required: true },
            rows: { type: Object, required: true },
        },
        data() {
            return {
                payload: {
                    id: this.id.initialValue,
                    taxableSupplyDate: this.taxableSupplyDate.initialValue,
                    contact: this.contact.initialValue,
                    description: this.description.initialValue,
                    rows: this.rows.initialValue,
                }
            };
        },
        methods: {
            searchContacts(url, options) {
                axios({
                    method: 'get',
                    url: url,
                }).then(function (response) {
                    options = response.data;
                }).catch(function (error) {
                    options = [];
                });
            },
            submitModal() {
                this.$refs.transactionForm.submit();
            },
            resetModal() {

            },
        },
        validations: {
            payload: {
                taxableSupplyDate: { required },
                contact: { required },
                description: { required },
            },
        },
    }
</script>

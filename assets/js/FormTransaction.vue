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
            documentNumber: { type: Object, required: true },
            contact: { type: Object, required: true },
            description: { type: Object, required: true },
            rows: { type: Object, required: true },
            transactionDetailUrl: { type: String, required: true },
        },
        data() {
            return {
                payload: {
                    id: this.id.initialValue,
                    taxableSupplyDate: this.taxableSupplyDate.initialValue,
                    documentNumber: this.documentNumber.initialValue,
                    contact: this.contact.initialValue ? this.contact.initialValue : {},
                    description: this.description.initialValue,
                    rows: this.rows.initialValue ? this.rows.initialValue : [],
                },
            };
        },
        mounted() {
            window.EventBus.$on('transactionEdit', (id) => {
                this.populateDetails(id);
                this.payload.id = id;
            });
            window.EventBus.$on('transactionClone', (id) => {
                this.populateDetails(id);
            });
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
            populateDetails(id) {
                axios({
                    method: 'get',
                    url: this.transactionDetailUrl + id,
                }).then((response) => {
                    this.payload.taxableSupplyDate = new Date(response.data.taxableSupplyDate);
                    this.payload.documentNumber = response.data.documentNumber;
                    this.payload.contact = response.data.contact;
                    this.payload.description = response.data.description;
                    this.payload.rows = response.data.rows;
                }).catch((error) => {
                    console.log(error);
                });
            },
            submitModal() {
                this.$refs.transactionForm.submit();
            },
            resetModal() {
                this.payload = {
                    id: "",
                    taxableSupplyDate: "",
                    documentNumber: "",
                    contact: {},
                    description: "",
                    rows: [],
                }
            },
        },
        validations: {
            payload: {
                taxableSupplyDate: { required },
                documentNumber: { required },
                contact: { required },
                description: { required },
            },
        },
    }
</script>

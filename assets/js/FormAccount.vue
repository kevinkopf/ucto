<script>
    import { required } from 'vuelidate/lib/validators';
    import InputText from "./InputText";

    export default {
        components: {
            InputText,
        },
        props: {
            id: { type: Object, required: true },
            name: { type: Object, required: true },
            numeral: { type: Object, required: true },
            account: { type: Object, required: true },
        },
        data() {
            return {
                payload: {
                    id: this.id.initialValue,
                    name: this.name.initialValue,
                    numeral: this.numeral.initialValue,
                    account: this.account.initialValue,
                },
                accountName: "",
                accountNumeral: "",
            };
        },
        mounted() {
            window.EventBus.$on('accountAnalyticalAdd', (account) => {
                this.accountName = account.name;
                this.accountNumeral = account.numeral;
                this.payload.account = account.id;
            });
        },
        methods: {
            submitModal() {
                this.$refs.contactForm.submit();
            },
            resetModal() {
                this.payload = {
                    id: "",
                    numeral: "",
                    name: "",
                    account: "",
                }
            },
        },
        validations: {
            payload: {
                numeral: { required },
                name: { required },
            },
        },
    }
</script>

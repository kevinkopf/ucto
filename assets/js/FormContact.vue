<script>
    import axios from 'axios';
    import { required } from 'vuelidate/lib/validators';
    import InputCheckbox from "./InputCheckbox";
    import InputText from "./InputText";

    export default {
        components: {
            InputCheckbox,
            InputText,
        },
        props: {
            id: { type: Object, required: true },
            name: { type: Object, required: true },
            address: { type: Object, required: true },
            phone: { type: Object, required: true },
            email: { type: Object, required: true },
            registrationNumber: { type: Object, required: true },
            isVatPayer: { type: Object, required: true },
            vatNumberPrefix: { type: Object, required: true },
            vatNumber: { type: Object, required: true },
            contactDetailUrl: { type: String, required: true },
        },
        data() {
            return {
                payload: {
                    id: this.id.initialValue,
                    name: this.name.initialValue,
                    address: this.address.initialValue,
                    phone: this.phone.initialValue,
                    email: this.email.initialValue,
                    registrationNumber: this.registrationNumber.initialValue,
                    isVatPayer: this.isVatPayer.initialValue,
                    vatNumberPrefix: this.vatNumberPrefix.initialValue,
                    vatNumber: this.vatNumber.initialValue,
                },
            };
        },
        mounted() {
            window.EventBus.$on('contactEdit', (id) => {
                this.populateDetails(id);
                this.payload.id = id;
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
                    url: this.contactDetailUrl + id,
                }).then((response) => {
                    this.payload.name = response.data.name;
                    this.payload.address = response.data.address;
                    this.payload.phone = response.data.phone;
                    this.payload.email = response.data.email;
                    this.payload.registrationNumber = response.data.registrationNumber;
                    this.payload.isVatPayer = response.data.vatPayer;
                    this.payload.vatNumberPrefix = response.data.vatNumberPrefix;
                    this.payload.vatNumber = response.data.vatNumber;
                }).catch((error) => {
                    console.log(error);
                });
            },
            submitModal() {
                this.$refs.contactForm.submit();
            },
            resetModal() {
                this.payload = {
                    id: "",
                    name: "",
                    address: "",
                    phone: "",
                    email: "",
                    registrationNumber: "",
                    isVatPayer: false,
                    vatNumberPrefix: "",
                    vatNumber: "",
                }
            },
        },
        validations: {
            payload: {
                name: { required },
                address: { required },
                registrationNumber: { required },
            },
        },
    }
</script>

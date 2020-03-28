<script>
    import axios from 'axios';
    import {en, cs} from 'vuejs-datepicker/dist/locale';
    import Datepicker from 'vuejs-datepicker';
    import InputContact from "./InputContact";
    import InputDate from "./InputDate";
    import InputText from "./InputText";

    export default {
        components: {
            Datepicker,
            InputContact,
            InputDate,
            InputText,
        },
        props: {

        },
        data() {
            return {
                en: en,
                cs: cs,
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
        }
    }
</script>

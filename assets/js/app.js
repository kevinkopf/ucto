import { BButton, BModal, VBModal, BCollapse, VBToggle } from 'bootstrap-vue';
import Vue from 'vue';
import Vuelidate from 'vuelidate';
import axios from 'axios';
import moment from 'moment';

Vue.component('b-button', BButton);
Vue.component('b-modal', BModal);
Vue.directive('b-modal', VBModal);
Vue.component('b-collapse', BCollapse);
Vue.directive('b-toggle', VBToggle);
// Vue.directive('b-tooltip', VBTooltip);

Vue.filter('capitalize', value => upperFirst(value));
Vue.filter('formatPrice', (money, cur) => money.toRoundedUnit(2).toLocaleString(window.locale, {
    style: 'currency',
    currency: cur,
}));
Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('YYYY-MM-DD');
    }
});

Vue.filter('separateThousands', number => Number(number).toLocaleString(window.locale));

Vue.use(Vuelidate);

window.EventBus = new Vue();

const app = new Vue({
    el: '#app',
    components: {
        'ButtonArrow': () => import('./ButtonArrow'),
        'FormAccount': () => import('./FormAccount'),
        'FormContact': () => import('./FormContact'),
        'FormTransaction': () => import('./FormTransaction'),
        'LinkIcon': () => import('./LinkIcon'),
        'ModalAccountRemove': () => import('./ModalAccountRemove'),
        'PageAccounts': () => import('./PageAccounts'),
        'PageContacts': () => import('./PageContacts'),
        'PageTransactions': () => import('./PageTransactions'),
    },
    created(){
        this.$root.$on('bv::modal::show', () => {
            this.isVisibleModal = true;
        });

        this.$root.$on('bv::modal::hide', () => {
            this.isVisibleModal = false;
        });
    },
    data(){
        return {
            isVisibleModal: false,
        }
    },
});

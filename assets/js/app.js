import { BButton, BModal, VBModal } from 'bootstrap-vue';
import Vue from 'vue';
import Vuelidate from 'vuelidate';
import axios from 'axios';

Vue.component('b-button', BButton);
Vue.component('b-modal', BModal);
Vue.directive('b-modal', VBModal);
// Vue.directive('b-tooltip', VBTooltip);

Vue.filter('capitalize', value => upperFirst(value));
Vue.filter('formatPrice', (money, cur) => money.toRoundedUnit(2).toLocaleString(window.locale, {
    style: 'currency',
    currency: cur,
}));

Vue.filter('separateThousands', number => Number(number).toLocaleString(window.locale));

Vue.use(Vuelidate);

window.EventBus = new Vue();

const app = new Vue({
    el: '#app',
    components: {
        'FormTransactionAdd': () => import('./FormTransactionAdd'),
        'LinkIcon': () => import('./LinkIcon'),
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

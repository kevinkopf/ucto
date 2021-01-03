import "admin-lte/dist/js/adminlte.min";
import {BButton, BCollapse, BModal, VBModal, VBToggle} from 'bootstrap-vue';
import Vue from 'vue';
import Vuelidate from 'vuelidate';
import moment from 'moment';

Vue.component('b-button', BButton);
Vue.component('b-modal', BModal);
Vue.directive('b-modal', VBModal);
Vue.component('b-collapse', BCollapse);
Vue.directive('b-toggle', VBToggle);
// Vue.directive('b-tooltip', VBTooltip);

Vue.filter('capitalize', value => upperFirst(value));
Vue.filter('formatPrice', function (amount) {
  try {
    const decimalCount = 2;
    const decimal = ",";
    const thousands = ".";
    const negativeSign = amount < 0 ? "-" : "";
    let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
    let j = (i.length > 3) ? i.length % 3 : 0;
    return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
  } catch (e) {
    console.log(e)
  }
});
Vue.filter('formatDate', function (value) {
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
    'ComponentStatementsTrialBalanceList': () => import('./ComponentStatementsTrialBalanceList'),
    'FormAccount': () => import('./FormAccount'),
    'FormContact': () => import('./FormContact'),
    'FormTransaction': () => import('./FormTransaction'),
    'FormTrialBalanceCompiler': () => import('./FormTrialBalanceCompiler'),
    'LinkIcon': () => import('./LinkIcon'),
    'ModalAccountRemove': () => import('./ModalAccountRemove'),
    'PageAccounts': () => import('./PageAccounts'),
    'PageContacts': () => import('./PageContacts'),
    'PageTransactions': () => import('./PageTransactions'),
  },
  created() {
    this.$root.$on('bv::modal::show', () => {
      this.isVisibleModal = true;
    });

    this.$root.$on('bv::modal::hide', () => {
      this.isVisibleModal = false;
    });
  },
  data() {
    return {
      isVisibleModal: false,
    }
  },
});

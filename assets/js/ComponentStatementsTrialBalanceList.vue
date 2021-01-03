<template>
  <b-modal
      size="lg"
      id="trial-balance-statements-list"
      title="Jiné obratové předvahy"
      :no-close-on-backdrop="true"
      :hide-footer="true"
      @close="resetModal"
      @show="getList"
  >
    <div class="modal-body">
      <ul>
        <li v-for="statement in statements" :key="statement.id">
          <a :href="url.detail + statement.id">
            Obratová Předvaha ke dni {{ statement.date }} (Sestaveno {{ statement.compiledDate }})
          </a>
          <button
              type="button"
              class="px-2 py-1 mx-1 cursor-pointer btn btn-outline-danger btn-sm"
              @click="removeStatement(statement.id)"
          >
            <i class="cursor-pointer fas fa-trash-alt"></i>
          </button>
        </li>
      </ul>
    </div>
  </b-modal>
</template>
<script>
import axios from 'axios';

export default {
  props: {
    listUrl: {type: String, required: true},
    detailUrl: {type: String, required: true},
    removeUrl: {type: String, required: true},
  },
  data() {
    return {
      url: {
        detail: this.detailUrl + '/',
        remove: this.removeUrl + '/',
      },
      statements: [],
    };
  },
  methods: {
    getList() {
      axios({
        method: 'get',
        url: this.listUrl
      }).then((response) => {
        this.statements = response.data;
      }).catch((error) => {
        this.resetModal();
        console.log(error);
      });
    },
    resetModal() {
      this.statements = [];
    },
    removeStatement(id) {
      axios({
        method: 'get',
        url: this.url.remove + id
      }).then((response) => {
        this.getList();
      }).catch((error) => {
        this.resetModal();
        console.log(error);
      });
    },
  },
}
</script>

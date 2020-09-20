<template>
  <ul class="pagination pagination-sm m-0 float-right">
    <li v-if="currentPage > 1" class="page-item">
      <button
          class="page-link"
          @click="emit(currentPage-1)">
        «
      </button>
    </li>
    <li
        v-if="lowerLimit > 1"
        class="page-item">
      <button
          type="button"
          class='page-link'
          @click="emit(1)">
        1
      </button>
    </li>
    <li
        v-if="lowerLimit > 2"
        class="page-item">
      <button
          type="button"
          class='page-link'>
        ...
      </button>
    </li>
    <li
        v-for="pageNumber in generatePaginationNumbers()"
        class="page-item">
      <button
          type="button"
          :class="(pageNumber === currentPage) ? 'page-link active' : 'page-link'"
          @click="emit(pageNumber)">
        {{ pageNumber }}
      </button>
    </li>
    <li
        v-if="upperLimit < totalPages-1"
        class="page-item">
      <button
          type="button"
          class='page-link'>
        ...
      </button>
    </li>
    <li
        v-if="upperLimit < totalPages"
        class="page-item">
      <button
          type="button"
          class='page-link'
          @click="emit(totalPages)">
        {{ totalPages }}
      </button>
    </li>
    <li v-if="currentPage < totalPages" class="page-item">
      <button
          type="button"
          class="page-link"
          @click="emit(currentPage+1)">
        »
      </button>
    </li>
  </ul>
</template>
<script>
export default {
  props: {
    currentPage: {total: Number, required: true},
    totalPages: {total: Number, required: true},
    lowerLimit: {total: Number, required: true},
    upperLimit: {total: Number, required: true},
  },
  methods: {
    emit(value) {
      this.$emit('update:currentPage', value);
    },
    generatePaginationNumbers() {
      let pages = [];

      for (let i = this.lowerLimit; i <= this.upperLimit; i++) {
        if (i > 0 && i < this.totalPages) {
          pages = pages.concat(i);
        }
      }

      return pages;
    },
  },
};
</script>

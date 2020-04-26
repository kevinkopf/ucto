<template>
    <div>
        <div class="row">
            <div class="col-1 p-2 border text-left font-weight-bold">
                Datum
            </div>
            <div class="col-5p5 p-2 border text-left font-weight-bold">
                Popis
            </div>
            <div class="col-2 p-2 border text-left font-weight-bold">
                Doklad
            </div>
            <div class="col-2 p-2 border text-center font-weight-bold">
                Kontakt
            </div>
            <div class="col-1p5 p-2 border text-center font-weight-bold">
                Akce
            </div>
        </div>

        <template v-for="(transaction, index) in transactions.data">
        <b-modal
            :id="'transaction-remove-'+transaction.id"
            title="Odstranit transakci?"
            :no-close-on-backdrop="true"
            cancel-title="Ponechat"
            ok-title="Odstranit"
            @ok="submitRemove(transactionRemoveUrl + '?id=' + transaction.id)"
        >
            <p class="my-4">Záznam bude nenávratně odstraněn.</p>
        </b-modal>
        <div class="row">
            <div class="col-1 p-1 border text-left">
                {{ transaction.taxableSupplyDate | formatDate }}
            </div>
            <div class="col-5p5 p-1 border text-left">
                <button-arrow
                        :indexkey="index"
                >
                </button-arrow>
                {{ transaction.description }}
            </div>
            <div class="col-2 p-1 border text-left">
                {{ transaction.documentNumber }}
            </div>
            <div class="col-2 p-1 border text-center">
                {{ transaction.contact.name }}
            </div>
            <div class="col-1p5 p-1 border text-center">
                <link-icon inline-template>
                    <button
                            type="button"
                            class="pt-1 pr-2 pb-1 pl-2 cursor-pointer btn btn-outline-primary"
                            @click="emitEvent('transactionEdit', transaction.id)"
                            v-b-modal.transaction-form
                    >
                        <i class="fas fa-edit"></i>
                    </button>
                </link-icon>
                <link-icon inline-template>
                    <button
                            type="button"
                            class="pt-1 pr-2 pb-1 pl-2 cursor-pointer btn btn-outline-primary"
                            @click="emitEvent('transactionClone', transaction.id)"
                            v-b-modal.transaction-form
                    >
                        <i class="cursor-pointer far fa-copy"></i>
                    </button>
                </link-icon>
                <link-icon inline-template>
                    <button
                            type="button"
                            class="pt-1 pr-2 pb-1 pl-2 cursor-pointer btn btn-outline-danger"
                            @click="emitEvent('transactionRemove', transaction.id)"
                            v-b-modal="'transaction-remove-' + transaction.id"
                    >
                        <i class="cursor-pointer fas fa-trash-alt"></i>
                    </button>
                </link-icon>
            </div>
        </div>
        <b-collapse :id="'collapse-'+index" class="row">
            <div class="col-12 border p-3">
                <div class="container">
                    <div class="row">
                        <div class="col-8 p-1 border font-weight-bold">
                            Popis
                        </div>
                        <div class="col-2 p-1 border text-center font-weight-bold">
                            Částka
                        </div>
                        <div class="col-1 p-1 border text-center font-weight-bold">
                            MD
                        </div>
                        <div class="col-1 p-1 border text-center font-weight-bold">
                            D
                        </div>
                    </div>

                    <div
                            v-for="trow in transaction.rows"
                            class="row"
                    >
                        <div class="col-8 p-1 border">
                            {{ trow.description }}
                        </div>
                        <div class="col-2 p-1 border text-center">
                            {{ (trow.amount / 100) }}
                        </div>
                        <div class="col-1 p-1 border text-center">
                            {{ trow.debtorsAccount.numeral }}
                        </div>
                        <div class="col-1 p-1 border text-center">
                            {{ trow.creditorsAccount.numeral }}
                        </div>
                    </div>

                </div>
            </div>
        </b-collapse>

        </template>

        <nav aria-label="Transactions pagination" class="mx-auto mt-5 mb-10 d-flex justify-content-center">
            <ul class="pagination pagination-lg">
                <li
                    class="page-item"
                    :class="{disabled: transactions.pages.current === 1}"
                    @click="transactions.pages.current > 1 ? fetchTransactions(transactions.pages.current - 1) : null"
                >
                    <span class="page-link" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </span>
                </li>

                <template v-for="(pageNumber, index) in generatePaginationPages()">
                <li
                    class="page-item disabled"
                    v-if="pageNumber-1 !== (generatePaginationPages())[index-1] && pageNumber > 1"
                >
                    <span class="page-link">
                        ...
                    </span>
                </li>
                <li
                    class="page-item"
                    :class="{active: pageNumber === transactions.pages.current}"
                    @click="fetchTransactions(pageNumber)"
                >
                    <span class="page-link">
                        {{ pageNumber }}
                    </span>
                </li>
                </template>

                <li
                    class="page-item"
                    :class="{disabled: transactions.pages.current === transactions.pages.total}"
                    @click="transactions.pages.current < transactions.pages.total ? fetchTransactions(transactions.pages.current + 1) : null"
                >
                    <span class="page-link" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </span>
                </li>
            </ul>
        </nav>
    </div>
</template>
<script>
    import axios from 'axios';
    import ButtonArrow from "./ButtonArrow";
    import LinkIcon from "./LinkIcon";

    export default {
        components: {
            ButtonArrow,
            LinkIcon,
        },
        props: {
            transactionsListUrl: {type: String, required: true},
            transactionRemoveUrl: {type: String, required: true},
        },
        data() {
            return {
                transactions: {
                    data: [],
                    pages: {
                        current: 0,
                        total: 1,
                    },
                },
            };
        },
        mounted() {
            this.fetchTransactions();
        },
        methods: {
            fetchTransactions(page = 1) {
                page = page < 1 ? 1 : page;

                axios({
                    method: 'get',
                    url: this.transactionsListUrl + "?page="+page,
                }).then((response) => {
                    this.transactions = response.data;
                }).catch((error) => {
                    this.transactions = {
                        data: [],
                        pages: {
                            current: 0,
                            total: 1,
                        },
                    };
                });
            },
            generatePaginationPages() {
                // Rewrite this method to be smarter
                let pages = [1, 2];

                if(this.transactions.pages.current >= 2 && this.transactions.pages.current < this.transactions.pages.total) {
                    pages = pages.concat([
                        // this.transactions.pages.current - 2,
                        this.transactions.pages.current - 1,
                        this.transactions.pages.current,
                        this.transactions.pages.current + 1,
                        // this.transactions.pages.current + 2,
                    ]);
                }

                pages = pages.concat([
                    this.transactions.pages.total - 1,
                    this.transactions.pages.total,
                ]);

                return pages.filter((value, index, self) => {
                    return self.indexOf(value) === index;
                });
            },
            submitRemove(url) {
                window.location.replace(url);
            }
        },
    }
</script>

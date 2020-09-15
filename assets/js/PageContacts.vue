<template>
    <div>
        <div class="row">
            <div class="col-1 p-2 border text-left font-weight-bold">

            </div>
            <div class="col-2 p-2 border-top border-right border-bottom text-left font-weight-bold">
                IČ / DIČ
            </div>
            <div class="col-8 p-2 border-top border-right border-bottom text-left font-weight-bold">
                Název
            </div>
            <div class="col-1 p-2 border-top border-right border-bottom text-center font-weight-bold">
                Akce
            </div>
        </div>

        <template v-for="(contact, index) in contacts.data">
        <b-modal
            :id="'contact-remove-'+contact.id"
            title="Odstranit kontakt?"
            :no-close-on-backdrop="true"
            cancel-title="Ponechat"
            ok-title="Odstranit"
            @ok="submitRemove(contactRemoveUrl + '?id=' + contact.id)"
        >
            <p class="my-4">Záznam bude nenávratně odstraněn.</p>
        </b-modal>
        <div class="row">
            <div class="col-1 p-2 border-left border-bottom text-center">
                <button-arrow
                        :indexkey="index">
                </button-arrow>
            </div>
            <div class="col-2 p-2 border-left border-bottom text-left">
                {{ contact.registrationNumber }}<br>
                <span v-if="contact.vatPayer">
                    {{ contact.vatNumberPrefix }}{{ contact.vatNumber }}
                    <i class="fas fa-check text-success"></i>
                </span>
            </div>
            <div class="col-8 p-2 border-left border-bottom text-left">
                {{ contact.name }}
            </div>
            <div class="col-1 p-2 border-left border-right border-bottom text-center">
                <link-icon
                    :args="contact.id"
                    inline-template
                >
                    <button
                            type="button"
                            class="pt-1 pr-2 pb-1 pl-2 cursor-pointer btn btn-outline-primary"
                            @click="emitEvent('contactEdit')"
                            v-b-modal.contact-form
                    >
                        <i class="fas fa-edit"></i>
                    </button>
                </link-icon>
                <link-icon
                    :args="contact.id"
                    inline-template
                >
                    <button
                            type="button"
                            class="pt-1 pr-2 pb-1 pl-2 cursor-pointer btn btn-outline-danger"
                            @click="emitEvent('contactRemove')"
                            v-b-modal="'contact-remove-' + args"
                            :disabled="contact.transactions.length > 0"
                        >
                        <i class="cursor-pointer fas fa-trash-alt"></i>
                    </button>
                </link-icon>
            </div>
        </div>
        <b-collapse
            :id="'collapse-'+index"
            class="row"
        >
            <div class="col-1 border-left border-bottom"></div>
            <div class="col-2 p-2 border-bottom border-right text-right font-weight-bold">
                Plátce DPH
            </div>
            <div class="col-9 p-2 border-bottom border-right">
                <template v-if="contact.vatPayer">
                <i class="fas fa-check text-success"></i>
                </template>
                <template v-if="!contact.vatPayer">
                <i class="far fa-times-circle text-danger"></i>
                </template>
            </div>
            <div class="col-1 border-left border-bottom"></div>
            <div class="col-2 p-2 border-bottom border-right text-right font-weight-bold">
                Sídlo
            </div>
            <div class="col-9 p-2 border-bottom border-right">
                {{ contact.address }}
            </div>
            <div class="col-1 border-left border-bottom"></div>
            <div class="col-2 p-2 border-bottom border-right text-right font-weight-bold">
                Telefon
            </div>
            <div class="col-9 p-2 border-bottom border-right">
                {{ contact.phone }}
            </div>
            <div class="col-1 border-left border-bottom"></div>
            <div class="col-2 p-2 border-bottom border-right text-right font-weight-bold">
                Email
            </div>
            <div class="col-9 p-2 border-bottom border-right">
                {{ contact.email }}
            </div>
        </b-collapse>
        </template>

        <nav aria-label="Contacts pagination" class="mx-auto mt-5 mb-10 d-flex justify-content-center">
            <ul class="pagination pagination-lg">
                <li
                        class="page-item"
                        :class="{disabled: contacts.pages.current === 1}"
                        @click="contacts.pages.current > 1 ? fetchTransactions(contacts.pages.current - 1) : null"
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
                            :class="{active: pageNumber === contacts.pages.current}"
                            @click="fetchTransactions(pageNumber)"
                    >
                    <span class="page-link">
                        {{ pageNumber }}
                    </span>
                    </li>
                </template>

                <li
                        class="page-item"
                        :class="{disabled: contacts.pages.current === contacts.pages.total}"
                        @click="contacts.pages.current < contacts.pages.total ? fetchTransactions(contacts.pages.current + 1) : null"
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
    import axios from "axios";
    import ButtonArrow from "./ButtonArrow";
    import LinkIcon from "./LinkIcon";

    export default {
        components: {
            ButtonArrow,
            LinkIcon,
        },
        props: {
            contactsListUrl: { type: String, required: true },
            contactRemoveUrl: { type: String, required: true },
        },
        data() {
            return {
                contacts: {
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
                    url: this.contactsListUrl + "?page="+page,
                }).then((response) => {
                    this.contacts = response.data;
                }).catch((error) => {
                    this.contacts = {
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

                if(this.contacts.pages.current >= 2 && this.contacts.pages.current < this.contacts.pages.total) {
                    pages = pages.concat([
                        // this.contacts.pages.current - 2,
                        this.contacts.pages.current - 1,
                        this.contacts.pages.current,
                        this.contacts.pages.current + 1,
                        // this.contacts.pages.current + 2,
                    ]);
                }

                pages = pages.concat([
                    this.contacts.pages.total - 1,
                    this.contacts.pages.total,
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

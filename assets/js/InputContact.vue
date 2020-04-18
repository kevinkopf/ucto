<template>
    <div>
        <label class="typo__label" for="ajax">Kontakt</label>
        <multiselect
                v-model="selectedOption"
                id="ajax"
                label="name"
                track-by="id"
                placeholder="Type to search"
                open-direction="bottom"
                :options="preselectedOptions"
                :multiple="false"
                :searchable="true"
                :loading="isLoading"
                :internal-search="false"
                :close-on-select="true"
                :options-limit="300"
                :limit="3"
                :limit-text="limitText"
                :max-height="600"
                :show-no-results="false"
                :hide-selected="true"
                :preserveSearch="true"
                @search-change="defaultSearch"
        >

            <span slot="noResult">
                Oops! No elements found. Consider changing the search query.
            </span>
        </multiselect>

        <input type="hidden" :id="id" :name="name" :value="selectedOption.id">
    </div>
</template>
<script>
    import axios from 'axios';
    import Multiselect from 'vue-multiselect';

    export default {
        components: {
            Multiselect,
        },
        props: {
            id: { type: String, required: true },
            name: { type: String, default: '' },
            url: {type:String, required: true},
        },
        data () {
            return {
                selectedOption: [],
                isLoading: false,
                preselectedOptions: [],
            }
        },
        methods: {
            limitText (count) {
                return `and ${count} other countries`
            },
            clearAll () {
                this.selectedOption = []
            },
            defaultSearch(query) {
                this.isLoading = true;

                axios({
                    method: 'get',
                    url: this.url + query,
                }).then(response => {
                    this.preselectedOptions = response.data;
                    this.isLoading = false;
                }).catch(error => {
                    this.preselectedOptions = [];
                    this.isLoading = false
                });
            },
        }
    }
</script>

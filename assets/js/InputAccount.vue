<template>
    <div>
        <label class="typo__label" for="ajax">{{ label }}</label>
        <multiselect
                v-model="selectedOption"
                id="ajax"
                label="name"
                track-by="code"
                placeholder="Type to search"
                open-direction="bottom"
                :options="preselectedOptions"
                :multiple="false"
                :searchable="true"
                :loading="isLoading"
                :internal-search="false"
                :clear-on-select="false"
                :close-on-select="true"
                :options-limit="300"
                :limit="3"
                :limit-text="limitText"
                :max-height="600"
                :show-no-results="false"
                :hide-selected="true"
                @search-change="defaultSearchChangeFunction"
        >
            <template
                    slot="option"
                    slot-scope="props"
            >
                <div class="option__desc">
                    <span class="option__title">
                        {{ props.option.numeral }} -- {{ props.option.name }}
                    </span>
                </div>
            </template>

            <span
                    slot="singleLabel"
            >
                {{ selectedOption.numeral }} -- {{ selectedOption.name }}
            </span>

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
            label: { type: String, default: '' },
            url: {type:String, required: true},
        },
        data () {
            return {
                selectedOption: "",
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
            defaultSearchChangeFunction(query) {
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

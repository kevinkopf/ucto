<template>
    <div>
        <label class="typo__label" for="ajax">Kontakt</label>
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
                    slot="tag"
                    slot-scope="{ option, remove }"
            >
                <span class="custom__tag">
                    <span>
                        {{ option.name }}
                    </span>
                    <span class="custom__remove" @click="remove(option)">❌</span>
                </span>
            </template>

            <template
                    slot="clear"
                    slot-scope="props"
            >
                <div
                        class="multiselect__clear"
                        v-if="selectedOption.length"
                        @mousedown.prevent.stop="clearAll(props.search)"
                >
                </div>
            </template>

            <span slot="noResult">
                Oops! No elements found. Consider changing the search query.
            </span>
        </multiselect>
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

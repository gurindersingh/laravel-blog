<template>
    <div class="bgc-white bd p-3 tag-wrap">
        <div class="tag-box">
            <p v-for="(tag, i) in selectedTags" class="tag">
                <span class="tag-label" v-text="tag.label"></span>
                <span class="delete cur-p" @click="removeTag(tag, i)">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                         height="16"
                         viewBox="0 0 24 24">
                    <path fill="currentColor"
                          d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/>
                </svg>
                </span>
            </p>
            <input type="text"
                   v-model="tagInput"
                   @keydown.up="focusSuggestion('up')"
                   @keydown.down="focusSuggestion('down')"
                   @keydown.esc="reset()"
                   @keydown.enter="addTag()"
                   placeholder="Add Tag...">
        </div>
        <div class="tag-suggections" v-if="suggestions.length > 0 && tagInput.length > 0">
            <ul class="list-unstyled bgc-white bd shadow p-2">
                <li class="d-b p-1"
                    v-for="(tag, i) in suggestions"
                    @click="addTag(tag)"
                    :class="{ active: focusedIndex === i }"
                    v-text="tag.label"></li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {

        name: "vue-tag-input",

        props: {
            tagRestUrl : {
                type: String,
                required: true
            },
            dataSelectedTags: {
                type: Array,
                default: []
            }
        },

        data() {
            return {
                selectedTags: [],
                taglist: [],
                suggestions: [],
                tagInput: null,
                focusedIndex: -1
            }
        },

        mounted() {
            this.selectedTags = this.dataSelectedTags;
            this.fetchTagslist();
        },

        watch: {
            'tagInput': 'fetchSuggestions',
        },

        methods: {
            fetchTagslist() {
                window.axios.get(this.tagRestUrl)
                    .then(res => this.taglist = res.data)
            },

            fetchSuggestions() {

                if (this.tagInput.length >= 2) {
                    this.suggestions = this.taglist.filter(t => t.label.toLowerCase().includes(this.tagInput.toLowerCase()));
                } else {
                    this.suggestions = [];
                }

            },

            addTag(tag = null) {

                // Add Clicked Tag from suggestion
                if (tag) {
                    this.addTagToSelectedList(tag);
                }
                // If Suggested tag not clicked, add tag to database if required, taglist & selectedTags
                else {

                    // Tag is already in database
                    if (this.focusedIndex > -1) {
                        tag = this.suggestions[this.focusedIndex];
                        this.addTagToSelectedList(tag);
                    }

                    // Tag not in focus
                    else if (this.focusedIndex < 0) {
                        tag = this.tagInput;

                        // Check if tag in taglist
                        if (this.taglist.filter(t => t.label === tag.label).length > 0) {
                            tag = this.taglist.findIndex(t => t.label === tag.label);
                            this.addTagToSelectedList(tag);
                        }
                        // add tag to database
                        else {
                            this.addNewTagToDatabase(this.tagInput).then(res => {
                                this.taglist.push(res.data);
                                this.addTagToSelectedList(res.data);
                            });

                        }

                    }
                }

                this.reset();
            },

            addTagToSelectedList(tag) {
                if (this.selectedTags.filter(t => t.label === tag.label).length === 0) {
                    this.selectedTags.push(tag);
                    this.$emit('tag-selected', tag);
                }
            },

            addNewTagToDatabase(label) {

                return new Promise((resolve, reject) => {
                    window.axios.post(this.tagRestUrl, {label: label})
                        .then(res => resolve(res))
                        .catch(err => reject(err))
                });

            },

            reset() {
                this.tagInput = '';
                this.suggestions = [];
                this.focusedIndex = -1;
            },

            focusSuggestion(dir = 'down') {

                if (dir === 'down') {
                    this.focusedIndex = this.focusedIndex === (this.suggestions.length - 1) ? 0 : ++this.focusedIndex
                } else {
                    this.focusedIndex = this.focusedIndex === -1 ? (this.suggestions.length - 1) : --this.focusedIndex
                }

            },

            removeTag(tag, index) {
                this.selectedTags.splice(index, 1);
                this.$emit('tag-removed', tag);
            }
        }
    }
</script>
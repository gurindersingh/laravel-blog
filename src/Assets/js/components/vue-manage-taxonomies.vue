<template>
    <vue-tabs>
        <vue-tab title="Categories" :active="true">
            <div class="">
                <vue-lists :lists="sortedCategories" taxonomy-type="category"></vue-lists>
            </div>
        </vue-tab>
        <vue-tab title="Tags">
            <div class="">
                <ul class="list-unstyled d-f fxw-w mb-0">
                    <li v-for="(tag, i) in sortedTags"
                        :key="i"
                        @click="edit(tag, 'tag')"
                        class="py-1 px-3 mr-3 mb-3 bd hv:bgc-gray-light cur-p">
                        {{ tag.label }}
                    </li>
                </ul>
            </div>
        </vue-tab>
        <vue-tab title="+ ADD">
            <vue-taxonomy-form :rest-url="taxonomyRestUrl"
                               :editable="editable"
                               @taxonomy-updated="taxonomyUpdated"
                               :flat-categories="flattenCategories"
            ></vue-taxonomy-form>
        </vue-tab>
    </vue-tabs>
</template>

<script>
    import VueTabs from './tabs/vue-tabs';
    import VueTab from './tabs/vue-tab';
    import VueTaxonomyForm from './vue-taxonomy-form';
    import VueLists from './lists/vue-lists';
    import {orderBy, sortBy} from 'lodash';

    Array.prototype.flatResult = function (inputArray, ischild) {

        ischild = ischild || false;

        var i = 0, len = inputArray.length;

        for (i = 0; i < len; i++) {
            this.push(inputArray[i]);

            if (inputArray[i].children && typeof inputArray[i].children === typeof []) {
                this.flatResult(inputArray[i].children, true);
            }
        }

        if (ischild === false) {
            return this;
        }
    };

    export default {
        name: "vue-manage-taxonomies",

        props: ['taxonomyRestUrl', 'dataCategories', 'dataTags'],

        components: {
            VueTabs,
            VueTab,
            VueTaxonomyForm,
            VueLists
        },

        data() {
            return {
                categories: this.dataCategories,
                flatCategories: [],
                categoriesCount: null,
                tagsCount: null,
                tags: this.dataTags,
                editable: null
            }
        },

        computed: {

            sortedCategories() {
                return orderBy(this.categories, 'label');
            },

            sortedTags() {
                let tags = orderBy(this.tags, 'label');

                this.tagsCount = tags.length;

                return tags;
            },

            flattenCategories() {
                let array = sortBy([].flatResult(this.sortedCategories), t => t.label.toLowerCase());

                this.categoriesCount = array.length;

                return array;
            }

        },

        mounted() {
            Event.listen('edit-taxonomy', data => {
                this.editable = data;
                this.$emit('select-tab', 2);
            })
        },

        methods: {

            taxonomyAdded(data) {

                if (data.taxonomy_type === 'tag') {
                    this.tags.push(data.taxonomy);
                    this.$emit('select-tab', 1);
                }

                if (data.taxonomy_type === 'category') {
                    this.categories.push(data.taxonomy);
                    this.$emit('select-tab', 0);
                }

            },

            taxonomyUpdated(data) {

                if (data.taxonomy_type === 'tag') {
                    this.tags = data.all_taxonomies;
                    this.$emit('select-tab', 1);
                }

                if (data.taxonomy_type === 'category') {
                    this.categories = data.all_taxonomies;
                    this.$emit('select-tab', 0);
                }

                this.editable = null
            },

            edit(listItem, taxonomyType) {
                Event.fire('edit-taxonomy', {listItem, taxonomyType})
            }

        }
    }
</script>
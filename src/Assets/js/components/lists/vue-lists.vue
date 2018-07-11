<template>
    <ul class="tree mt-3">
        <slot :lists="lists">
            <li v-for="(listItem, i) in lists" :key="i" class="">

                <a href="#" v-if="!selectable" @click.prevent="edit(listItem, taxonomyType)"
                   class="h6">{{ listItem.label }}</a>

                <div class="" v-if="selectable">
                    <label :for="name + '-' + listItem.id" class="toggle mr-3">
                        <input :type="inputType"
                               :name="name"
                               :value="listItem.id"
                               :id="name + '-' + listItem.id"
                               :checked="selected === listItem.id"
                               @change="select(listItem)">
                        <span v-text="listItem.label"></span>
                    </label>
                </div>

                <vue-lists :lists="listItem.children"
                           v-if="listItem.children.length > 0"
                           :taxonomy-type="taxonomyType"
                           name="category_id"
                           :input-type="inputType"
                           :selectable="selectable"></vue-lists>
            </li>
        </slot>
    </ul>
</template>

<script>
    export default {
        name: "vue-lists",

        props: ['lists', 'taxonomyType', 'selectable', 'inputType', 'name', 'selected'],

        data() {
            return {
                selection: null
            }
        },

        mounted() {

            this.$watch('selection', id => {
                Event.fire('category-selected', { id: id })
            })

        },

        methods: {

            edit(listItem, taxonomyType) {
                Event.fire('edit-taxonomy', {listItem, taxonomyType})
            },

            select(item) {
                this.selection = item.id;
            }

        }
    }
</script>
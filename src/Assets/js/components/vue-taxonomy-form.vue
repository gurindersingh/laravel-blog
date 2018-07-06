<template>

    <div class="row">
        <div class="col-lg-12">
            <form action="">

                <div class="form-row">
                    <div class="col-lg-12">
                        <hr>
                        <h3 class="fsz-sm tt-u ls-12 mb-3">Taxonomy To Create</h3>

                        <label for="category" class="toggle label mr-3">
                            <input type="radio" v-model="taxonomy.taxonomy_type" name="taxonomy" value="category"
                                   id="category">
                            <span>Category</span>
                        </label>
                        <label for="tag" class="toggle label mr-3">
                            <input type="radio" v-model="taxonomy.taxonomy_type" name="taxonomy" value="tag" id="tag">
                            <span>Tag</span>
                        </label>
                    </div>
                </div>

                <div class="" v-if="taxonomy.taxonomy_type">
                    <hr>
                    <div class="form-group">
                        <label for="name" class="label">Taxonomy Name</label>
                        <input type="text" v-model="taxonomy.label" id="name" name="name" placeholder="Name..."
                               class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="description" class="label">Description</label>
                        <textarea name="description" v-model="taxonomy.description" id="description" rows="5"
                                  placeholder="Description..." class="form-control"></textarea>
                    </div>

                    <div class="form-group" v-if="taxonomy.taxonomy_type === 'category'">
                        <label for="parent_id" class="label">Parent</label>
                        <select v-model="taxonomy.parent_id" name="parent_id" id="parent_id" class="form-control">
                            <option value="null">Select Parent Category....</option>
                            <option :value="category.id" v-for="(category, i) in flatCategories">{{ category.label}}
                            </option>
                        </select>
                    </div>

                    <div class="d-f jc-sb">
                        <button type="submit" class="app-btn grad-blue btn bd-n fc:shadow-none hv:shadow"
                                @click.prevent="updating ? update() : save()">Save
                        </button>

                        <button type="submit" class="app-btn grad-error btn bd-n fc:shadow-none hv:shadow"
                                v-if="updating"
                                @click.prevent="destroy()">Delete
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: "vue-taxonomy-form",

        props: ['restUrl', 'flatCategories', 'editable'],

        data() {
            return {
                updating: false,
                taxonomy: new window.Form({
                    taxonomy_type: null,
                    id: null,
                    label: null,
                    description: null,
                    parent_id: null
                })
            }
        },

        mounted() {
            this.updating = !!this.editable;

            if (this.updating) {
                this.taxonomy.taxonomy_type = this.editable.taxonomyType;
                this.taxonomy.id = this.editable.listItem.id;
                this.taxonomy.label = this.editable.listItem.label;
                this.taxonomy.description = this.editable.listItem.description;
                this.taxonomy.parent_id = this.editable.listItem.parent_id;
            }

        },

        beforeDestroy() {
            this.updating = false;
            this.taxonomy.taxonomy_type = null;
            this.taxonomy.id = null;
            this.taxonomy.label = null;
            this.taxonomy.description = null;
            this.taxonomy.parent_id = null;
        },

        methods: {

            save() {
                this.taxonomy.post(`${this.restUrl}`)
                    .then(res => this.$emit('taxonomy-updated', res))
                    .catch(err => console.log(err))
            },

            update() {
                this.taxonomy.put(`${this.restUrl}/${this.editable.listItem.id}`)
                    .then(res => this.$emit('taxonomy-updated', res))
                    .catch(err => console.log(err))
            },

            destroy() {
                window.axios
                    .delete(
                        `${this.restUrl}/${this.editable.listItem.id}`,
                        {
                            data: {
                                taxonomy_type: this.editable.taxonomyType,
                                id: this.editable.listItem.id
                            }
                        }
                    )
                    .then(res => this.$emit('taxonomy-updated', res.data))
                    .catch(err => console.log(err))
            }

        }
    }
</script>
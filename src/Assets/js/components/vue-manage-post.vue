<!--suppress ALL -->
<template>
    <div class="container" style="min-height: 500px;" v-if="scriptsLoaded">
        <div class="processing p-fx t-0 l-0 b-0 w-100 z-fixed d-f jc-c ai-c bg-opq" v-if="processing">
            <div class="">
                <div class="dot-bricks"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="d-f jc-sb ai-c mt-5">
                    <h1 class="h5 tt-u text-uppercase"
                        v-text="isPost ? 'Create ' + dataPostType : 'Create ' + dataPostType"></h1>
                    <div class="d-f jc-sb">
                        <button type="button"
                                v-text="post.id ? 'Update' : 'Save'"
                                @click="post.id ? update() : save()"
                                class="btn app-btn bd-n fsz-sm grad-success fc:otl-n ac:otl-n fc:shadow-none">
                        </button>
                        <button type="button"
                                v-if="post.id"
                                v-text="'Delete'"
                                @click="destroy()"
                                class="btn app-btn bd-n fsz-sm grad-error fc:otl-n ac:otl-n fc:shadow-none ml-3">
                        </button>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row mb-3">

            <div class="col-lg-12">

                <div class="bd bdc-red p-3 mb-4 bdr-10" v-if="post.errors.any()">
                    <ul class="list-unstyled mb-0">
                        <li class="alert c-red" v-for="(error, i) in post.errors.all()" :key="i">
                            {{ error[0] }}
                        </li>
                    </ul>
                </div>

            </div>

            <div class="col-lg-8">
                <div class="row">

                    <div class="col-lg-12 mb-4" v-if="post.slug">
                        <label for="slug" class="label">slug</label>
                        <div class="input-group input-group-sm">
                            <input type="text"
                                   v-model="post.slug"
                                   :disabled="!editSlug"
                                   id="slug"
                                   class="form-control" placeholder="Recipient's username"
                                   aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-default bd fc:shadow-none fc:bgc-primary fc:c-white"
                                        @click="editSlug = !editSlug"
                                        type="button">
                                    <svg height="18" width="18"
                                         fill="currentColor"
                                         class="d-f as-c"
                                         xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    >
                                        <use xlink:href="#icon-edit"></use>
                                    </svg>
                                </button>
                                <button class="btn btn-default bd fc:shadow-none fc:bgc-green fc:c-white"
                                        @click="update()"
                                        v-if="editSlug"
                                        type="button">
                                    <svg height="18" width="18"
                                         fill="currentColor"
                                         class="d-f as-c"
                                         xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    >
                                        <use xlink:href="#icon-check"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 mb-4">
                        <label for="title" class="label">Title</label>
                        <input id="title" type="text" class="form-control" name="title" v-model="post.title"
                               placeholder="Title...">
                    </div>

                    <div class="col-lg-12 mb-4">
                        <label for="excerpt" class="label">excerpt / Meta Description</label>
                        <textarea name="excerpt" class="form-control" id="excerpt" rows="5"
                                  v-model="post.excerpt"
                                  placeholder="Excerpt..."></textarea>
                    </div>

                    <div class="col-lg-12 p-rel mb-4" v-if="post.id">
                        <label class="label sticky">Content</label>
                        <vue-wysiwyg
                                placeholder="Write something great..."
                                :file-upload-url="fileUploadUrlSanitized"
                                :file-delete-url="fileUploadUrlSanitized"
                                :scripts-to-load="mediumScriptUrl"
                                :data-toc="post.table_of_content"
                                @toc-updated="tocUpdated"
                                @file-uploaded="update()"
                                v-model="post.body"></vue-wysiwyg>
                        <input type="hidden" name="content" v-model="post.content" class="d-none">
                    </div>

                    <div class="col-lg-12 mb-4" v-if="!post.id">
                        <label class="label">Content</label>
                        <div class="bgc-white bd p-4 form-control">
                            <p class="mb-0">To add content, <strong><u>save</u></strong> your post/page </p>
                        </div>
                    </div>

                    <div class="col-lg-12 mb-4">
                        <div class="form-group">
                            <label for="robots">Robots</label>
                            <select name="robots" id="robots" class="form-control" v-model="post.meta.robots">
                                <option value="all">All</option>
                                <option value="index, follow">Index & Follow</option>
                                <option value="index, nofollow">Index & No-Follow</option>
                                <option value="noindex, follow">No-Index & Follow</option>
                                <option value="noindex, nofollow">No-Index & No-Follow</option>
                            </select>
                            <small class="fsz-xs fs-i c-gray ls-11 mt-1 mb-0">
                                Robots tag help search engine bots to index website. For more information visit
                                <a href="https://developers.google.com/search/reference/robots_meta_tag"
                                   target="_blank">Google Page</a>
                            </small>
                        </div>
                    </div>

                    <div class="col-lg-12 mb-4">
                        <label for="keywords" class="label">Keywords</label>
                        <textarea name="keywords" class="form-control" id="keywords" rows="3"
                                  v-model="post.meta.keywords"
                                  placeholder="Keywords..."></textarea>
                    </div>

                </div>
            </div>

            <div class="col-lg-4">

                <div class="row">

                    <div class="col-lg-12 mb-4">
                        <h3 class="label">Status</h3>
                        <div class="bgc-white p-3 bd">
                            <label class="toggle label mb-0">
                                <input type="checkbox"
                                       :checked="post.published_at"
                                       @change="togglePublish()">
                                <span v-text="post.published_at ? 'Published' : 'Un-Published'"></span>
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-12 mb-4" v-if="post.id">
                        <h3 class="label">Actions</h3>
                        <div class="bgc-white p-3 bd">
                            <a :href="'/' + post.slug" class="tt-u">View</a>
                        </div>
                    </div>

                    <div class="col-lg-12 mb-4" v-if="isPost">
                        <h3 class="label">Select Category</h3>
                        <div class="bgc-white p-3 bd" style="max-height: 500px; overflow-y: auto">
                            <vue-lists :lists="sortedCategories"
                                       taxonomy-type="category"
                                       name="category_id"
                                       input-type="radio"
                                       :selected="post.category_id"
                                       :selectable="true"></vue-lists>
                        </div>
                    </div>

                    <div class="col-lg-12 mb-4" v-if="isPost">
                        <h3 class="label">Add Tags</h3>
                        <vue-tag-input
                                :tag-rest-url="tagRestUrl"
                                @tag-selected="tagSelected"
                                @tag-removed="tagRemoved"
                                :data-selected-tags="postData ? postData.tags : []"
                        ></vue-tag-input>
                    </div>

                    <div class="col-lg-12 mtb-4">
                        <h3 class="label"
                            v-text="isPost ? dataPostType + ' Featured Image' : dataPostType + ' Featured Image'"></h3>
                        <vue-image-selector name="featured_image"
                                            :src="featuredImageUrl"
                                            class="bd"
                                            @image-selected="featuredImageSelected"
                                            image-selection-text="Select Post Image"
                                            image-selected-text="Post Image"
                        ></vue-image-selector>
                    </div>

                </div>

            </div>

        </div>
    </div>
</template>

<script>
    import VueWysiwyg from './vue-medium-wysiwyg';
    import VueLists from './lists/vue-lists';
    import VueTagInput from './vue-tag-input';
    import VueImageSelector from './vue-image-selector';
    import dayjs from 'dayjs';
    import {orderBy} from 'lodash';
    import Utils from "./tools/Utils";

    export default {
        name: "vue-manage-post",

        props: [
            'mediumScriptUrl',
            'dataPostType',
            'categories',
            'tagRestUrl',
            'postData',
            'postRestUrl',
            'postEditUrl',
            'fileUploadUrl'
        ],

        components: {
            VueWysiwyg,
            VueLists,
            VueTagInput,
            VueImageSelector
        },

        data() {
            return {
                editSlug: false,
                scriptsLoaded: true,
                processing: false,
                post: new window.Form({
                    id: null,
                    title: null,
                    slug: null,
                    excerpt: null,
                    body: null,
                    table_of_content: null,
                    category_id: null,
                    post_type: this.dataPostType === 'page' ? 'page' : 'post',
                    path: null,
                    published_at: null,
                    created_at: null,
                    updated_at: null,
                    tags: [],
                    featured_image: null,
                    featured_image_raw: null,
                    featured_image_id: null,
                    meta: {
                        robots: 'all',
                        keywords: ''
                    }
                })
            }
        },

        mounted() {

            Event.listen('category-selected', data => this.post.category_id = data.id);

            if (this.postData) {
                this.updateData(this.postData)
            }

        },

        computed: {
            sortedCategories() {
                return orderBy(this.categories, 'label');
            },

            featuredImageUrl() {
                if (this.post.featured_image_id !== null) {
                    if (Utils.objHasKey(this.post, 'featured_image.variations.thumbnail.path')) {
                        let path = this.post.featured_image.variations.thumbnail.path;
                        let prefix = window.App.cloud_url_prefix;
                        return `${prefix}/${path}`;
                    }
                }
                return null;
            },

            fileUploadUrlSanitized() {
                return this.fileUploadUrl.replace('%post%', this.post.id);
            },

            isPost() {
                return !(this.dataPostType === 'page');
            }
        },

        methods: {

            tagSelected(tagToAdd) {
                if (this.post.tags.filter(tag => tag.slug === tagToAdd.slug).length <= 0) {
                    this.post.tags.push(tagToAdd)
                }
            },

            tagRemoved(tagToRemove) {
                let index = this.post.tags.findIndex(tag => tag.slug === tagToRemove.slug);

                if (index >= 0) {
                    this.post.tags.splice(index, 1)
                }
            },

            tocUpdated(data) {
                this.post.table_of_content = data.toc;
            },

            featuredImageSelected(data) {
                this.post.featured_image_raw = data.image;
            },

            togglePublish() {
                this.post.published_at = this.post.published_at ? null : dayjs().format('YYYY-MM-DD HH:mm:ss');
            },

            save() {
                this.processing = true;

                this.post.post(`${this.postRestUrl}`)
                    .then(res => {
                        Object.assign(this.post, res);

                        let url = this.postEditUrl.replace('%post%', this.post.id);

                        window.location.href = url;
                    })
                    .catch(err => {
                        console.log(err);
                        this.processing = false;
                    });
            },

            update() {
                this.processing = true;

                this.$nextTick(() => {
                    this.post.put(`${this.postRestUrl}/${this.post.id}`)
                        .then(res => {
                            this.updateData(res);
                            this.processing = false;
                        })
                        .catch(err => {
                            console.log(err);
                            this.processing = false;
                        });
                });

            },

            destroy() {
                this.$confirm("Are You Sure You Want To Delete")
                    .then(res => {
                        this.processing = true;

                        window.axios
                            .delete(
                                `${this.postRestUrl}/${this.post.id}`,
                                {
                                    data: {
                                        id: this.post.id
                                    }
                                }
                            )
                            .then(res => window.location.href = this.postRestUrl)
                            .catch(err => console.log(err))
                    })
                    .catch(err => console.log(err));
            },

            updateData(post) {
                Object.assign(this.post, post, {category_id: post.category ? post.category.id : null})
                this.editSlug = false;
            },

            tocUpdated(data) {
                this.post.table_of_content = data.toc;
            }
        }

    }
</script>

<style>
    .label.sticky {
        position: absolute;
        top: 0;
    }
</style>
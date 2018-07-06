<template>
    <div style="max-width: 100%" class="bgc-white p-3">

        <div class="w-100 d-f ai-c jc-c">
            <div class="d-block p-rel w-100">
                <img :src="imageUrl" v-show="imageUrl" class="mb-3" style="max-width: 100%; width: 100%">
                <div class="d-f jc-c">
                    <button class="btn btn-sm d-f ai-c p-3 cur-p mt-1 fc:shadow-none bd hv:shadow"
                            type="button"
                            v-show="! imageUrl"
                            @click.prevent="openSelector()">
                        <svg viewBox="0 0 24 24" width="20" height="20">
                            <path d="M19 2h-14c-1.7 0-3 1.3-3 3v14c0 1.7 1.3 3 3 3h14c1.7 0 3-1.3 3-3v-14c0-1.7-1.3-3-3-3zM4 5c0-0.6 0.4-1 1-1h14c0.6 0 1 0.4 1 1v7.6l-3.3-3.3c-0.4-0.4-1-0.4-1.4 0l-10.6 10.6c-0.4-0.1-0.7-0.5-0.7-0.9v-14zM19 20h-11.6l8.6-8.6 4 4v3.6c0 0.6-0.4 1-1 1z"></path>
                            <path d="M8.5 11c1.4 0 2.5-1.1 2.5-2.5s-1.1-2.5-2.5-2.5-2.5 1.1-2.5 2.5 1.1 2.5 2.5 2.5zM8.5 8c0.3 0 0.5 0.2 0.5 0.5s-0.2 0.5-0.5 0.5-0.5-0.2-0.5-0.5 0.2-0.5 0.5-0.5z"></path>
                        </svg>
                    </button>
                    <button class="btn btn-sm d-f ai-c p-3 cur-p mt-1 fc:shadow-none bd hv:shadow"
                            type="button"
                            v-show="imageUrl"
                            @click.prevent="openSelector()">
                        <svg width="20" height="20" viewBox="0 0 24 24">
                            <path d="M21.7 7.3l-5-5c-0.4-0.4-1-0.4-1.4 0l-13 13c-0.2 0.2-0.3 0.4-0.3 0.7v5c0 0.6 0.4 1 1 1h5c0.3 0 0.5-0.1 0.7-0.3l13-13c0.4-0.4 0.4-1 0-1.4zM7.6 20h-3.6v-3.6l12-12 3.6 3.6-12 12z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <input type="file"
                   :name=name
                   style="display: none"
                   ref=fileField
                   accept="image/jpeg,image/png,image/jpg"
                   @change="preview($event.target.files[0])">
        </div>

    </div>
</template>

<script>
    export default {
        name: "vue-image-selector",

        props: ['src', 'name', 'imageSelectionText', 'imageSelectedText'],

        data() {
            return {
                image: this.src,
                imageObject: null,
                dataUri: null,
                deleted: false,
                errors: [],
                maxWidth: null,
                maxHeight: null,
                maxSize: null,
            }
        },

        mounted() {

            this.$watch('src', (val, oldVal) => {
                if(val) this.image = null;
            });

        },

        computed: {

            imageUrl() {
                if (this.image) return this.image;
                if (this.src) return this.src;
                return null;
            }

        },

        methods: {

            openSelector() {
                this.deleted = false;
                this.$refs.fileField.click()
            },

            preview(file) {
                const reader = new FileReader();
                this.errors = [];
                this.imageObject = null;

                reader.onload = event => {

                    this.imageObject = new Image();

                    this.imageObject.onload = () => {

                        if (this.validateImage(this.imageObject, file.size)) {
                            this.image = event.target.result;
                            this.$emit('image-selected', {image: this.image})
                        } else {
                            flash(this.errorsHtml(), 'error', 5000);
                            this.errors = [];
                            this.imageObject = null;
                        }

                    };

                    this.imageObject.src = event.target.result;

                };
                reader.readAsDataURL(file)
            },

            validateImage(image, size) {
                let maxSize = this.maxSize ? this.maxSize * 1024 * 1024 : 3 * 1024 * 1024;

                if (size <= 0 || size > maxSize) {
                    this.errors.push("Maximum Upload size is " + this.maxSize + "MB")
                }

                if (this.maxWidth && this.imageObject.width > this.maxWidth) {
                    this.errors.push("Maximum width of the file should be " + this.maxWidth + "px")
                }

                if (this.maxHeight && this.imageObject.height > this.maxHeight) {
                    this.errors.push("Maximum width of the file should be " + this.maxWidth + "px")
                }

                return this.errors.length <= 0;
            },

            errorsHtml() {
                let html = `<ul class="list-unstyled m-0">`;
                this.errors.forEach(error => html += `<li>${error}</li>`);
                html += `</ul>`;
                return html;
            },

            removeImage() {
                this.file = null;
                this.$refs.fileField.value = '';
                this.deleted = true;
            }

        }
    }
</script>
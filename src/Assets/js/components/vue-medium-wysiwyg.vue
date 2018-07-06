<template>

    <div class="p-rel" :class="{ 'fullscreen-wysiwyg' : fullscreen }">

        <div class="top-buttons d-f p-rel jc-fe">
            <button type="button"
                    @click.prevent="showToc = !showToc"
                    v-if="this.enableToc"
                    class="btn btn-light btn-sm"
                    style="top: -25px; right: 40px">
                <svg width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.984 12.984v-1.969h14.016v1.969h-14.016zM6.984 18.984v-1.969h14.016v1.969h-14.016zM6.984 5.016h14.016v1.969h-14.016v-1.969zM2.016 11.016v-1.031h3v0.938l-1.828 2.063h1.828v1.031h-3v-0.938l1.781-2.063h-1.781zM3 8.016v-3h-0.984v-1.031h1.969v4.031h-0.984zM2.016 17.016v-1.031h3v4.031h-3v-1.031h1.969v-0.469h-0.984v-1.031h0.984v-0.469h-1.969z"></path>
                </svg>
            </button>

            <!-- Table of content -->
            <div class="p-ab bgc-blue-light bd p-2 d-f fxd-c shadow bdr-5"
                 v-if="showToc && this.enableToc"
                 style="top: 0; right: 40px; width: 400px; min-height: 400px;z-index: 1050;">

                <h3 class="h6 bdB text-uppercase pb-1">Table of content</h3>

                <div class="toc-wrap" v-html="toc ? toc : '<p>Nothing to show</p>'"></div>

                <div class="d-f jc-fe" style="margin-top: auto">

                    <button type="button"
                            @click="refreshToc(true)"
                            class="btn btn-light btn-sm d-f ai-c jc-c">
                        <svg width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 5h-4v-1c0-1.7-1.3-3-3-3h-4c-1.7 0-3 1.3-3 3v1h-4c-0.6 0-1 0.4-1 1s0.4 1 1 1h1v13c0 1.7 1.3 3 3 3h10c1.7 0 3-1.3 3-3v-13h1c0.6 0 1-0.4 1-1s-0.4-1-1-1zM9 4c0-0.6 0.4-1 1-1h4c0.6 0 1 0.4 1 1v1h-6v-1zM18 20c0 0.6-0.4 1-1 1h-10c-0.6 0-1-0.4-1-1v-13h12v13z"></path>
                            <path d="M10 10c-0.6 0-1 0.4-1 1v6c0 0.6 0.4 1 1 1s1-0.4 1-1v-6c0-0.6-0.4-1-1-1z"></path>
                            <path d="M14 10c-0.6 0-1 0.4-1 1v6c0 0.6 0.4 1 1 1s1-0.4 1-1v-6c0-0.6-0.4-1-1-1z"></path>
                        </svg>
                    </button>

                    <button type="button"
                            @click="refreshToc()"
                            class="btn btn-light btn-sm d-f ai-c jc-c">
                        <svg width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.8 14.1c-0.5-0.2-1.1 0.1-1.3 0.6-0.4 1.1-1 2.2-1.9 3-1.4 1.5-3.5 2.3-5.6 2.3 0 0 0 0 0 0-2.1 0-4.1-0.8-5.7-2.4l-2.8-2.6h3.5c0.6 0 1-0.4 1-1s-0.4-1-1-1h-6c0 0 0 0 0 0-0.1 0-0.2 0-0.2 0.1 0 0-0.1 0-0.1 0s-0.1 0-0.1 0.1c-0.1 0-0.2 0.1-0.2 0.2 0 0 0 0 0 0s0 0.1-0.1 0.1c0 0.1-0.1 0.1-0.1 0.2s0 0.1 0 0.2c0 0 0 0.1 0 0.1v6c0 0.6 0.4 1 1 1s1-0.4 1-1v-3.7l2.9 2.8c1.7 1.9 4.2 2.9 6.9 2.9 0 0 0 0 0 0 2.7 0 5.2-1 7.1-2.9 1-1 1.9-2.3 2.4-3.7 0.1-0.6-0.2-1.2-0.7-1.3z"></path>
                            <path d="M24 10.1c0 0 0-0.1 0-0.1v-6c0-0.6-0.4-1-1-1s-1 0.4-1 1v3.7l-2.9-2.8c-1-1-2.3-1.9-3.7-2.4-2.6-0.8-5.3-0.7-7.7 0.5-2.4 1.1-4.2 3.1-5.1 5.7-0.2 0.5 0.1 1.1 0.6 1.2 0.5 0.2 1.1-0.1 1.3-0.6 0.7-2 2.2-3.6 4.1-4.6 1.9-0.9 4.1-1 6.1-0.3 1.1 0.4 2.2 1 3 1.9l2.8 2.7h-3.5c-0.6 0-1 0.4-1 1s0.4 1 1 1h6c0.1 0 0.3 0 0.4-0.1 0 0 0 0 0 0 0.1-0.1 0.2-0.1 0.3-0.2 0 0 0 0 0 0s0-0.1 0.1-0.1c0-0.1 0.1-0.1 0.1-0.2 0.1-0.1 0.1-0.2 0.1-0.3z"></path>
                        </svg>
                    </button>

                    <button type="button"
                            @click="showToc = ! showToc"
                            class="btn btn-light btn-sm d-f ai-c jc-c">
                        <svg width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.4 12l5.3-5.3c0.4-0.4 0.4-1 0-1.4s-1-0.4-1.4 0l-5.3 5.3-5.3-5.3c-0.4-0.4-1-0.4-1.4 0s-0.4 1 0 1.4l5.3 5.3-5.3 5.3c-0.4 0.4-0.4 1 0 1.4 0.2 0.2 0.4 0.3 0.7 0.3s0.5-0.1 0.7-0.3l5.3-5.3 5.3 5.3c0.2 0.2 0.5 0.3 0.7 0.3s0.5-0.1 0.7-0.3c0.4-0.4 0.4-1 0-1.4l-5.3-5.3z"></path>
                        </svg>
                    </button>

                </div>

            </div>

            <button type="button"
                    @click.prevent="toggleFullScreen()"
                    class="btn btn-light btn-sm"
                    style="top: -25px">
                <svg width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21.9 2.6c-0.1-0.2-0.3-0.4-0.5-0.5-0.1-0.1-0.3-0.1-0.4-0.1h-6c-0.6 0-1 0.4-1 1s0.4 1 1 1h3.6l-5.3 5.3c-0.4 0.4-0.4 1 0 1.4 0.2 0.2 0.5 0.3 0.7 0.3s0.5-0.1 0.7-0.3l5.3-5.3v3.6c0 0.6 0.4 1 1 1s1-0.4 1-1v-6c0-0.1 0-0.3-0.1-0.4z"></path>
                    <path d="M9.3 13.3l-5.3 5.3v-3.6c0-0.6-0.4-1-1-1s-1 0.4-1 1v6c0 0.1 0 0.3 0.1 0.4 0.1 0.2 0.3 0.4 0.5 0.5 0.1 0.1 0.3 0.1 0.4 0.1h6c0.6 0 1-0.4 1-1s-0.4-1-1-1h-3.6l5.3-5.3c0.4-0.4 0.4-1 0-1.4s-1-0.4-1.4 0z"></path>
                </svg>
            </button>
        </div>

        <textarea :name="name"
                  :id="id"
                  class="bd p-3"
                  ref="editorWrap"
                  v-if="show"
                  :data-placeholder="placeholder ? placeholder : 'Type your text...'"
        ></textarea>

        <modal name="get-code-for-wysiwyg"
               :adaptive="true"
               :scrollable="true"
               @before-open="codeModelOpening"
               height="auto">
            <div class="p-4">
                <h2 class="fsz-sm tt-u ls-12">Add Code</h2>

                <div class="form-group">

                    <label for="code-language">Language</label>

                    <select name="code-language"
                            ref="codeLanguage"
                            id="code-language"
                            class="form-control">
                        <option v-for="(value, key) in supportedLanguages" :key="key" :value="key" v-text="value">HTML
                        </option>
                    </select>

                </div>

                <div class="form-group mb-0">

                    <label for="code-textarea">Code</label>

                    <textarea name="name"
                              ref="codeTextarea"
                              id="code-textarea"
                              rows="10"
                              placeholder="Enter code..."
                              class="form-control"></textarea>

                </div>

                <div class="d-f jc-sb mt-4">
                    <button type="button"
                            id="code-close"
                            ref="codeClose"
                            @click="closeCodeModel()"
                            class="btn btn-sm btn-secondary fsz-sm ls-12 tt-u">Close
                    </button>
                    <button type="button"
                            id="code-insert"
                            ref="codeInsert"
                            @click="insertCode()"
                            class="btn btn-sm btn-primary fsz-sm ls-12 tt-u">Insert
                    </button>
                </div>

            </div>
        </modal>

    </div>

</template>

<script>
    import Utils from "./tools/Utils";
    import Code from './tools/insert-code'
    import TocLevel from './tools/insert-table-of-content'
    import MakeToc from './tools/make-table-of-content'
    import {props, DefaultButtons, SupportedLanguages, FileUploadOptions} from './wysiwyg-props';
    import NormalizeWhitespace from './tools/normalize-whitespace';

    import VModal from 'vue-js-modal'

    Vue.use(VModal);

    export default {
        name: "vue-medium-wysiwyg",

        props: props,

        data() {
            return {
                id: Utils.uniqueId(),
                show: false,
                editor: null,
                editorId: null,
                initialValue: this.value,
                mediaButton: null,
                fullscreen: false,
                language: 'php',
                code: null,
                core: null,
                showToc: false,
                toc: null
            }
        },

        created() {

            this.addSCripts(this.scriptsToLoad)
                .then(res => {
                    this.toc = this.dataToc;
                    this.registerPlugin();
                    this.init()
                });

        },

        computed: {
            supportedLanguages() {
                return Utils.sortObjectByKeys(SupportedLanguages);
            }

        },

        methods: {

            addSCripts(scriptSrc) {
                return new Promise((resolve, reject) => {
                    try {
                        let script = document.createElement('script');
                        script.setAttribute('src', scriptSrc);
                        script.async = true;
                        script.onload = () => resolve();
                        document.head.appendChild(script);
                    } catch (err) {
                        reject(err);
                    }
                });
            },

            init: function () {

                this.show = true;

                this.$nextTick(() => {

                    this.startEditor();

                    this.setContent();

                    this.subscribe();

                    // this.$watch('fullscreen', val => {
                    //     val ? document.body.classList.add('fullscreen-active') : document.body.classList.remove('fullscreen-active')
                    // });

                    document.addEventListener("keydown", e => {
                        if (this.fullscreen && e.keyCode === 27) {
                            this.fullscreen = false
                        }
                    });

                })

            },

            startEditor() {

                this.editor = new MediumEditor(this.$refs.editorWrap, {
                    toolbar: {
                        buttons: DefaultButtons
                    }
                });

                $(this.editor.elements[0]).css('min-height', this.editorHeight);

                this.editorId = this.$refs.editorWrap.getAttribute('medium-editor-textarea-id');

                let fileUploadConfigs = FileUploadOptions(this);

                Object.assign(fileUploadConfigs, {
                    headers: {'X-CSRF-TOKEN': window.App.csrfToken}
                });

                let addons = {
                    images: {
                        label: this.insertLabels.image,
                        fileUploadOptions: fileUploadConfigs,
                        deleteMethod: 'DELETE',
                        deleteScript: this.fileDeleteUrl,
                        fileDeleteOptions: {
                            headers: {'X-CSRF-TOKEN': window.App.csrfToken}
                        },
                        uploadCompleted: ($el, data) => this.$emit('file-uploaded', data),
                        uploadFailed: (uploadErrors, data) => this.$emit('file-upload-error', {
                            erros: uploadErrors,
                            data: data
                        }),
                        actions: { // (object) Actions for an optional second toolbar
                            remove: { // (object) Remove action configuration
                                clicked: $el => {
                                    let $place = this.core.$el.find($el);
                                    let $event = $.Event('keydown');
                                    $event.which = 8;
                                    $(document).trigger($event);
                                    $place.remove();
                                    this.$emit('file-deleted')
                                }
                            }
                        },
                    },
                    embeds: {
                        label: this.insertLabels.video
                    }
                };

                if (this.enableCode) {
                    addons = Object.assign(addons, {code: {}});
                }

                if (this.enableToc) {
                    addons = Object.assign(addons, {
                        tocLevelTwo: {'level': 'two'},
                        tocLevelThree: {'level': 'three'},
                        tocLevelFour: {'level': 'four'},
                        tocLevelFive: {'level': 'five'},
                        tocLevelSix: {'level': 'six'}
                    });
                }

                $(`#${this.id}`).mediumInsert({
                    editor: this.editor,
                    addons: addons
                });

                document.querySelector('button.medium-insert-buttons-show').innerHTML = this.insertLabels.plus;

            },

            setContent() {
                if (this.value) {
                    this.editor.setContent(this.value);
                }
            },

            subscribe() {

                this.editor.subscribe('editableInput', (event, editable) => {

                    let allContents = this.editor.serialize();

                    if (allContents[this.editorId]) {

                        let value = allContents[this.editorId].value;

                        this.$emit('input', value)

                    }

                });

            },

            registerPlugin() {
                let self = this;

                window.$.fn['mediumInsertCode'] = function (options) {
                    return this.each(function () {
                        if (!$.data(this, 'plugin_mediumInsertCode')) {
                            $.data(this, 'plugin_mediumInsertCode',
                                new Code(this, options, self)
                            );
                            self.core = $(this).data('plugin_mediumInsert');
                        }
                    });
                };

                let tocLevels = ['Two', 'Three', 'Four', 'Five', 'Six'];

                if (this.enableToc) {
                    tocLevels.forEach(tocLevel => {
                        window.$.fn[`mediumInsertTocLevel${tocLevel}`] = function (options) {
                            return this.each(function () {
                                if (!$.data(this, `plugin_mediumInsertTocLevel${tocLevel}`)) {
                                    $.data(this, `plugin_mediumInsertTocLevel${tocLevel}`,
                                        new TocLevel(this, options, self)
                                    );
                                    self.core = $(this).data('plugin_mediumInsert');
                                }
                            });
                        };
                    })
                }

            },

            insertCode() {

                this.$modal.hide('get-code-for-wysiwyg');

                let html = this.$refs.codeTextarea.value;

                let lang = this.$refs.codeLanguage.value;

                if (html) {

                    let ns = new NormalizeWhitespace();

                    html = Utils.htmlEntities(ns.normalize(html));

                    lang = lang ? lang : 'html';

                    //let pre = $('<pre />').addClass('prettyprint').addClass(`lang-${lang}`);
                    let pre = $('<pre />');

                    let codeBlock = $('<code />').attr('v-pre', '').attr('data-language', lang);

                    codeBlock = $(codeBlock).html(html);

                    html = $(pre).html(codeBlock);

                    let $place = this.core.$el.find('.medium-insert-active');

                    let $emptyTag = $('<p><br></p>');

                    $place.before(html);

                    $place.after($emptyTag);

                    $place.remove();

                    this.core.triggerInput();

                    this.core.moveCaret($emptyTag);
                }

                this.core.hideButtons();
            },

            codeModelOpening() {
                this.core.hideButtons();
            },

            closeCodeModel() {
                this.core.hideButtons();
                this.$modal.hide('get-code-for-wysiwyg')
            },

            refreshToc(remove = false) {

                if (this.hideToc) return null;

                if (remove) {

                    this.toc = null

                } else {

                    let tocMaker = new MakeToc;

                    let content = this.editor.getContent();

                    let html = (new DOMParser()).parseFromString(content, "text/html");

                    let hTags = 'h2,h3,h4,h5,h6';

                    let headers = html.querySelectorAll(hTags);

                    headers.forEach(h => h.id = Utils.slugify(h.textContent));

                    this.editor.setContent(html.body.innerHTML);

                    let toc = tocMaker.for(headers);

                    this.toc = toc ? toc.outerHTML : null

                }

                let data = {
                    toc: this.toc
                };

                this.showToc = false;

                // Update Table of Content - TOC
                this.$nextTick(() => this.$emit('toc-updated', data))

            },

            toggleFullScreen() {
                this.fullscreen = document.body.classList.toggle('fullscreen-on');
            }
        }
    }
</script>

<style>

    .medium-editor-element {
        background: white;
    }

    .medium-editor-insert-plugin.medium-editor-placeholder:after {
        margin-top: 16px;
        padding-left: 16px;
        padding-right: 16px;
    }

    .medium-insert-buttons {
        left: 24px !important;
        /*top: 64px !important;*/
        margin-top: -5px;
    }

    .medium-insert-buttons-show {
        border-color: black !important;
        color: black !important;
        -webkit-transition: all 0.15s linear;
        transition: all 0.15s linear;
    }

    .medium-insert-buttons-show.medium-insert-buttons-rotate {
        border-color: #006aff !important;
        background: #006aff !important;
        color: white !important;
    }

    .medium-insert-action {
        display: flex !important;
        align-items: center;
        justify-content: center;
        border-color: black !important;
        color: black !important;
        -webkit-transition: all 0.15s linear;
        transition: all 0.15s linear;
    }

    .medium-insert-action:hover {
        border-color: #006aff !important;
        background: #006aff !important;
        color: white !important;
    }

    .medium-insert-buttons-rotate .medium-insert-buttons-addons {
        display: flex !important;
    }

    .medium-insert-buttons-addons {
        left: 40px !important;
    }

    body.fullscreen-on {
        overflow: hidden;
    }

    .fullscreen-wysiwyg {
        position: fixed !important;
        background: white;
        z-index: 2000;
        top: 0;
        left: 0;
        padding: 0 40px 0 40px;
        width: 100%;
        height: 100% !important;
        overflow: auto;
        -webkit-transition: all 0.15s linear;
        transition: all 0.15s linear;

    }

    .fullscreen-wysiwyg .top-buttons {
        position: absolute !important;
        right: 0;
        top: 0;
    }

    .fullscreen-wysiwyg .medium-editor-element {
        position: relative;
        max-width: 100%;
        min-height: 100% !important;
        margin: 0 auto;
        border-color: transparent;
    }

    .fullscreen-wysiwyg .top-buttons {
        position: fixed !important;
        right: 10px !important;
        top: 0 !important;
        z-index: 2100;
        /*top: 28px !important;*/
    }

    pre.prettyprint {
        background: #fffcda;
        padding: 10px;
        border: 1px solid #d6d6d6;
    }
</style>
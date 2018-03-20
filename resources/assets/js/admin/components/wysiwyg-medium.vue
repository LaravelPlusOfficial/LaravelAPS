<template>

    <div class="p-rel" :class="{ 'fullscreen-able' : fullscreen }">

        <button type="button"
                @click.prevent="showToc = !showToc"
                class="bg-n bd-n p-0 m-0 lh-1 fsz-sm p-ab r-0 fill-gray fill-primary-hv otl-n-fc cur-p"
                style="top: -25px; right: 40px">
            <vue-svg name="icon-list-numbered" square="20"></vue-svg>
        </button>

        <!-- Table of content -->
        <scale-transition origin="top right">
            <div class="p-ab r-0 bgc-gray-light z-popover bdr-10 p-3 d-f fxd-c jc-fs"
                 v-show="showToc"
                 style="top: 0; right: 40px; width: 400px; min-height: 400px;">

                <h3 class="label bdB pt-2 pb-3">Table of content</h3>

                <div class="toc-wrap" v-html="toc ? toc : '<p>Nothing to show</p>'">

                </div>

                <div class="d-f jc-fe ai-c ac-c bdT pt-3" style="margin-top: auto">

                    <button type="button"
                            @click="refreshToc(true)"
                            class="bg-n bd-n p-0 m-0 lh-1 fsz-sm fill-gray fill-primary-hv fullscreen-btn otl-n-fc cur-p">
                        <vue-svg name="icon-delete" square="20"></vue-svg>
                    </button>

                    <button type="button"
                            @click="refreshToc()"
                            class="bg-n bd-n p-0 m-0 lh-1 fsz-sm fill-gray fill-primary-hv fullscreen-btn otl-n-fc cur-p mr-3 ml-3
">
                        <vue-svg name="icon-refresh" square="20"></vue-svg>
                    </button>

                    <button type="button"
                            @click="showToc = ! showToc"
                            class="bg-n bd-n p-0 m-0 lh-1 fsz-sm fill-gray fill-primary-hv fullscreen-btn otl-n-fc cur-p">
                        <vue-svg name="icon-x" square="20"></vue-svg>
                    </button>

                </div>

            </div>
        </scale-transition>

        <button type="button"
                @click.prevent="fullscreen = !fullscreen"
                class="bg-n bd-n p-0 m-0 lh-1 fsz-sm p-ab r-0 fill-gray fill-primary-hv fullscreen-btn otl-n-fc cur-p"
                style="top: -25px">
            <vue-svg name="icon-maximize" square="20"></vue-svg>
        </button>

        <textarea :name="name"
                  :id="id"
                  ref="editorWrap"
                  v-if="show"
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
                        <option v-for="(value, key) in supportedLanguages" :value="key" v-text="value">HTML</option>
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
    import Utils from "../../common/Utils";
    import Code from './medium-insert-code-extension'
    import TocLevel from './medium-insert-table-of-content-extension'
    import {ScaleTransition} from 'vue2-transitions'
    import MakeToc from './make-toc'

    export default {
        name: "wysiwyg-medium",

        props: ["name", "value", "placeholder", 'dataToc'],

        components: {
            ScaleTransition
        },

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

        mounted() {

            Event.listen('mediumEditorLoaded', () => {
                this.toc = this.dataToc;
                this.registerPlugin()
                this.init()
            })

        },

        computed: {

            supportedLanguages() {
                let languages = {
                    html: 'HTML',
                    java: 'Java',
                    py: 'Python',
                    bsh: 'Bash',
                    sh: 'Shell',
                    sql: 'SQL',
                    xml: 'XML',
                    css: 'CSS',
                    js: 'Javascript',
                    coffee: 'CoffeeScript',
                    go: 'GO',
                    json: 'JSON',
                    vb: 'Visual Basic',
                    php: 'PHP',
                    perl: 'Perl',
                    rb: 'Ruby',
                }

                return Utils.sortObjectByKeys(languages);


            }

        },

        methods: {

            init: function () {

                this.show = true

                this.$nextTick(() => {


                    this.startEditor();

                    this.setContent();

                    this.subscribe();

                    this.$watch('fullscreen', val => {
                        val ? document.body.classList.add('fullscreen-active') : document.body.classList.remove('fullscreen-active')
                    })

                    document.addEventListener("keydown", (e) => {
                        if (this.fullscreen && e.keyCode === 27) {
                            this.fullscreen = false
                        }
                    });

                })


            },

            startEditor() {

                this.editor = new MediumEditor(this.$refs.editorWrap, {
                    toolbar: {
                        buttons: [
                            'bold',
                            'italic',
                            'underline',
                            'anchor',
                            'h2',
                            'h3',
                            'quote',
                            'strikethrough',
                            'removeFormat',
                            'html',
                            'pre'
                        ]
                    }
                })

                this.editorId = this.$refs.editorWrap.getAttribute('medium-editor-textarea-id');

                $(`#${this.id}`).mediumInsert({
                    editor: this.editor,
                    addons: {
                        images: {
                            label: '<span class="fa fa-camera"></span>',
                            fileUploadOptions: { // https://github.com/blueimp/jQuery-File-Upload/wiki/Options
                                url: '/dashboard/media', // (string) A relative path to an upload script
                                paramName: 'file',
                                singleFileUploads: true,
                                headers: {
                                    'X-CSRF-TOKEN': App.csrfToken
                                },
                                acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i // (regexp) Regexp of accepted file types
                            },
                        },
                        code: {},
                        tocLevelTwo: {
                            'level': 'two'
                        },
                        tocLevelThree: {
                            'level': 'three'
                        },
                        tocLevelFour: {
                            'level': 'four'
                        },
                        tocLevelFive: {
                            'level': 'five'
                        },
                        tocLevelSix: {
                            'level': 'six'
                        }
                    }
                });

            },

            setContent() {
                if (this.value) {
                    this.editor.setContent(this.value)
                }
            },

            subscribe() {

                this.editor.subscribe('editableInput', (event, editable) => {

                    let allContents = this.editor.serialize();

                    if (allContents[this.editorId]) {

                        let value = allContents[this.editorId].value

                        this.$emit('input', value)

                    }

                });

            },

            registerPlugin() {
                let self = this

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

            },

            insertCode() {

                this.$modal.hide('get-code-for-wysiwyg')

                let html = this.$refs.codeTextarea.value

                let lang = this.$refs.codeLanguage.value

                if (html) {

                    html = this.$normalizeWhitespace().normalize(html);

                    //html = this.htmlEntities(html)
                    html = Utils.htmlEntities(html)

                    console.log(html)

                    lang = lang ? lang : 'html';

                    let pre = $('<pre />').addClass('prettyprint').addClass(`lang-${lang}`)

                    html = $(pre).html(html)

                    let $place = this.core.$el.find('.medium-insert-active')

                    let $emptyTag = $('<p><br></p>')

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

                if (remove) {

                    this.toc = null

                } else {

                    let tocMaker = new MakeToc

                    let content = this.editor.getContent();

                    let html = (new DOMParser()).parseFromString(content, "text/html");

                    let hTags = 'h2,h3,h4,h5,h6';
                    
                    let headers = html.querySelectorAll(hTags);

                    headers.forEach(h => h.id = Utils.slugify(h.textContent));

                    this.editor.setContent(html.body.innerHTML)

                    let toc = tocMaker.for(headers)

                    this.toc = toc ? toc.outerHTML : null

                }

                let data = {
                    toc: this.toc
                }

                this.showToc = false

                this.$nextTick(() => this.$emit('toc-updated', data))

            },
        }
    }
</script>
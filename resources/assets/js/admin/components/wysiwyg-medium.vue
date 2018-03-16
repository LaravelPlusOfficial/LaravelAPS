<template>

    <div class="p-rel" :class="{ 'fullscreen-able' : fullscreen }">

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

    export default {
        name: "wysiwyg-medium",

        props: ["name", "value", "placeholder"],

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
                core: null
            }
        },

        mounted() {

            Event.listen('mediumEditorLoaded', () => {
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
                    }
                });

                //this.core = plugin_mediumInsert

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

                            $.data(
                                this,
                                'plugin_mediumInsertCode',
                                new Code(
                                    this,
                                    options,
                                    self
                                )
                            );

                            self.core = $(this).data('plugin_mediumInsert');

                        }
                    });

                };
            },

            codeModelOpening() {

            },

            insertCode() {

                this.$modal.hide('get-code-for-wysiwyg')

                let html = this.$refs.codeTextarea.value

                let lang = this.$refs.codeLanguage.value

                if (html) {

                    html = this.$normalizeWhitespace().normalize(html);

                    html = this.htmlEntities(html)

                    console.log(html)

                    lang = lang ? lang : 'html';

                    let pre = $('<pre />').addClass('prettyprint').addClass(`lang-${lang}`)

                    //html = $('<pre />').attr('data-language', lang ? lang : 'html').html(html)

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


                // let code = this.$refs.codeTextarea.value
                //
                // console.log(code)
                //
                // this.$refs.codeTextarea.value = null
                //

                // // let code = '<pre><code>' + this.code + '</code></pre>'
                //
                // this.editor.pasteHTML(`${code}`)
            },

            htmlEntities(str) {
                return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }
        }
    }
</script>
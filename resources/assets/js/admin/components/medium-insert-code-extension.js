class Code {

    constructor(el, options, vue, codeLanguage, codeTextarea, codeClose, codeInsert) {

        this.vue = vue

        this.defaults = {
            label: '<span class="fa fa-code"></span>'
        }

        this.el = el;

        this.$el = $(el);

        this.templates = window.MediumInsert.Templates;

        this.core = this.$el.data('plugin_mediumInsert');

        this.options = $.extend(true, {}, this.defaults, options);

        this._defaults = this.defaults;

        this._name = 'mediumInsert';

        this.init();

    }

    init() {
        this.events();
    }

    events() {

    }

    getCore() {
        return this.core
    }

    add() {
        this.vue.$modal.show('get-code-for-wysiwyg');

        // this.vue.$nextTick(() => {
        //     console.log(this.vue.$el.querySelector('#code-insert'))
        // })

        // this.codeLanguage = $(this.vue).find('select#code-language')
        // this.codeTextarea = $(this.vue).find('textarea#code-textarea')
        // this.codeClose = $(this.vue).find('button#code-close')
        // this.codeInsert = $(this.vue).find('button#code-insert')

        //this.vue.$refs.codeInsert.addEventListener('click', this.insertCodeToPage())

        //$(this.vue.$refs.codeInsert).click(() => this.insertCodeToPage())

        // console.log(this.core)
        // // this.hideButtons(this.$el);

        // let $place = this.$el.find('.medium-insert-active');
        //
        // // Make sure that the content of the paragraph is empty and <br> is wrapped in <p></p> to avoid Firefox problems
        // $place.html('<p><br></p>');
        //
        // // Replace paragraph with div to prevent #124 issue with pasting in Chrome,
        // // because medium editor wraps inserted content into paragraph and paragraphs can't be nested
        // if ($place.is('p')) {
        //     $place.replaceWith('<div class="medium-insert-active">' + $place.html() + '</div>');
        //     $place = this.$el.find('.medium-insert-active');
        //     this.core.moveCaret($place);
        // }
    }

    insertCodeToPage() {
        console.log('sdvjs')
    }

    hideButtons($el) {
        $el = $el || this.$el;

        $el.find('.medium-insert-buttons').hide();
        $el.find('.medium-insert-buttons-addons').hide();
        $el.find('.medium-insert-buttons-show').removeClass('medium-insert-buttons-rotate');
    }

}

export default Code;
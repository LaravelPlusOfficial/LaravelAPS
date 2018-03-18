class Code {

    constructor(el, options, vue) {

        this.vue = vue

        this.defaults = {
            label: '<span class="fa fa-terminal"></span>'
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
    }

    insertCodeToPage() {

    }

    hideButtons($el) {
        $el = $el || this.$el;

        $el.find('.medium-insert-buttons').hide();
        $el.find('.medium-insert-buttons-addons').hide();
        $el.find('.medium-insert-buttons-show').removeClass('medium-insert-buttons-rotate');
    }

}

export default Code;
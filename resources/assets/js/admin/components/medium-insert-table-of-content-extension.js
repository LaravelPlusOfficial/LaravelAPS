import Utils from "../../common/Utils";

class TocLevel {

    constructor(el, options, vue) {

        this.level = options.level ? options.level : 'one'

        this.vue = vue

        this.tags = {
            one: 'L1',
            two: 'L2',
            three: 'L3',
            four: 'L4',
            five: 'L5',
            six: 'L6'
        }

        this.defaults = {
            label: this.tags[this.level]
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

    getLevelOneTag() {
        let id = Utils.uniqueId();

        return $('<h2 />')
            .attr('data-lp-toc', 'true')
            .attr('data-lp-toc-level', '1')
            .attr('id', id)
            .text('Level One')
    }

    getLevelTwoTag() {
        let id = Utils.uniqueId();

        return $('<h3 />')
            .attr('data-lp-toc', 'true')
            .attr('data-lp-toc-level', '2')
            .attr('id', id)
            .text('Level Two')
    }

    getNode() {
        let tagLevel = this.tags[this.level].replace('L', '');

        let id = Utils.uniqueId();

        return $(`<h${tagLevel} />`)
            .attr('data-lp-toc', 'true')
            .attr('data-lp-toc-level', tagLevel)
            .attr('id', id)
            .text(`Heading level ${this.level}`)
    }

    add() {
        let $place = this.core.$el.find('.medium-insert-active')

        let $emptyTag = $('<p><br></p>')

        $place.before(this.getNode());

        $place.after($emptyTag);

        $place.remove();

        this.core.triggerInput();

        this.core.moveCaret($emptyTag);

        this.hideButtons();
    }

    hideButtons($el) {
        $el = $el || this.$el;

        $el.find('.medium-insert-buttons').hide();
        $el.find('.medium-insert-buttons-addons').hide();
        $el.find('.medium-insert-buttons-show').removeClass('medium-insert-buttons-rotate');
    }

}

export default TocLevel;
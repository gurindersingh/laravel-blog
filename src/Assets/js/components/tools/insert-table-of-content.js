import Utils from "./Utils";

class TocLevel {

    constructor(el, options, vue) {

        this.level = options.level ? options.level : 'one';

        this.vue = vue;

        this.tags = {
            one: 'h1',
            two: 'h2',
            three: 'h3',
            four: 'h4',
            five: 'h5',
            six: 'h6'
        };

        this.defaults = {
            label: vue.insertLabels[this.tags[this.level]]
        };

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

    getNode() {
        let tagLevel = this.tags[this.level].replace('L', '');

        let id = Utils.uniqueId();

        return $(`<${tagLevel} />`)
            .attr('data-lp-toc', 'true')
            .attr('data-lp-toc-level', tagLevel)
            .attr('id', id)
            .text(`Heading level ${this.level}`)
    }

    add() {
        let $place = this.core.$el.find('.medium-insert-active');

        let $emptyTag = $('<p><br></p>');

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
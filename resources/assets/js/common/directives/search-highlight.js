let SearchHighlight = {

    bind: function (el, binding, vnode) {

        let instance = new Mark(el);

        instance.mark(el.getAttribute('data-keyword'), {
            separateWordSearch: false
        })

    }

}

export default SearchHighlight
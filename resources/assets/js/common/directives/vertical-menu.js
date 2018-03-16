let VerticalMenu = {

    bind(el, binding, vnode) {

        $(el).metisMenu({
            activeClass: 'is-active',
            collapseInClass: 'show',
            subMenu: '.submenu' // bootstrap 4
        });

    }

}

export default VerticalMenu
let Hammer = typeof require === 'function' ? require('hammerjs') : window.Hammer

let Touch = {

    bind: function (el, binding, vnode) {
        
        let mc = new Hammer(el);
        
        mc.on(Object.keys(binding.modifiers).join(' '), function (ev) {

            if (typeof vnode.context[binding.expression] === 'function') {
                vnode.context[binding.expression]()
            }

        });

    }

}

export default Touch
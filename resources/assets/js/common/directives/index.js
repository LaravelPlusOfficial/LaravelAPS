import SearchHighlight from './search-highlight'
import Touch from './touch'
import VerticalMenu from './vertical-menu'

export default function install(Vue) {
    Vue.directive('search-highlight', SearchHighlight)
    Vue.directive('touch', Touch)
    Vue.directive('vertical-menu', VerticalMenu)
}
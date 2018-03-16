export default class Utils {

    /*
    Encode url
     */
    static urlEncode(str) {
        str = str.split('/').join('%2F');
        str = str.split('#').join('%23');
        str = str.split('&').join('%26');
        str = str.split('=').join('%3D');
        str = str.split('?').join('%3F');
        return str;
    }

    /*
    * Get count of depth nested objects by key
    */
    static countObjectDepth(Obj, $key = 'children') {

        let count = Obj[$key].length

        for (let i = 0; i < Obj[$key].length; i++) {

            count += Utils.countObjectDepth(Obj[$key][i], $key);
        }

        return count;
    }

    /*
    Get value of field from url
    www.example.com?foo=bar
    use -> getQueryString('foo', url) -> bar / null

    @param string url
    @param string foo
    @return string | null
     */
    static getQueryString(field, url) {
        let href = url ? url : window.location.href;

        let reg = new RegExp('[?&]' + field + '=([^&#]*)', 'i');

        let string = reg.exec(href);

        return string ? string[1] : null;
    }

    /*
    addProps(myObj, 'sub1.sub2.propA', 1);
    addProps(myObj, ['sub1', 'sub2', 'propA'], 1);
    https://stackoverflow.com/questions/17643965/automatically-create-object-if-undefined#answer-34205057
     */
    static addProps(obj, arr, val) {
        if (typeof arr === 'string') {
            arr = arr.split(".")
        }

        obj[arr[0]] = obj[arr[0]] || {};

        let tmpObj = obj[arr[0]];

        if (arr.length > 1) {
            arr.shift();
            Utils.addProps(tmpObj, arr, val);
        } else {
            obj[arr[0]] = val;
        }

        return obj;

    }

    /**
     * Get the value from an object by the given key.
     *
     * example: get('a.b', {a: {b: c}}, 'default value')
     */
    static get(key, object, def = null) {
        return key.toString().split('.').reduce((t, i) => t[i] || def, object);
    }

    static objIsEmpty(obj) {
        return Object.getOwnPropertyNames(obj).length === 0;
    }

    static highlight(text, keywords) {
        return text.replace(new RegExp(keywords, 'gi'), '<span class="highlighted">$&</span>');
    }

    static uniqueId() {
        return '_' + Math.random().toString(36).substr(2, 9);
    }

    /*
    sort objects by keys
    { c: 'vc', a: 'va' } -> { a: 'va', c: 'vc' }
     */
    static sortObjectByKeys(obj) {
        return Object.keys(obj).sort().reduce((r, k) => (r[k] = obj[k], r), {})
    }
}


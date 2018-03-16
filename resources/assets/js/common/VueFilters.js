import moment from 'moment';

Vue.filter('capitalize', function (value) {
    if (!value) return ''

    value = value.toString()

    return value.charAt(0).toUpperCase() + value.slice(1)
})


Vue.filter('append', function (text, suffix = '...') {
    return text + suffix;
});

Vue.filter('timeAgo', function (text) {
    return moment(text).fromNow();
});

Vue.filter('truncate', function (text, length, clamp) {
    clamp = clamp || '...';
    length = length || 30;

    if (text.length <= length) return text;

    var tcText = text.slice(0, length - clamp.length);
    var last = tcText.length - 1;


    while (last > 0 && tcText[last] !== ' ' && tcText[last] !== clamp[0]) last -= 1;

    // Fix for case when text dont have any `space`
    last = last || length - clamp.length;

    tcText =  tcText.slice(0, last);

    return tcText + clamp;
});

Vue.filter('substr', function (text, length, clamp) {
    clamp = clamp || '...';
    length = length || 30;

    return text.substr(0, length) + clamp;
});

Vue.filter('strCount', function (text, limit, clamp) {
    let char = text ? text.length : ''.length

    limit = limit ? limit : 70;

    let remaining = (limit - char) < 0 ? 0 : (limit - char);

    return `Maximum of ${limit} characters ( ${remaining} Available)`
});


Vue.filter('prettyBytes', function (bytes, decimals, kib = false) {
    if (bytes === 0) return '0 Bytes'
    if (isNaN(parseFloat(bytes)) && !isFinite(bytes)) return 'Not an number'
    const k = kib ? 1024 : 1000
    const dm = decimals || 2
    const sizes = kib ? ['Bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'] : ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
    const i = Math.floor(Math.log(bytes) / Math.log(k))

    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i]
})

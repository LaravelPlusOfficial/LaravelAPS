<?php

if (!function_exists('setting')) {

    /**
     * @param null $key
     * @param null $default
     * @param null $value
     * @param bool $trueNull
     * @return string | \App\Models\Setting | \App\Services\Settings\Contract\SettingContract
     */
    function setting($key = null, $default = null, $value = null, $trueNull = false)
    {
        $setting = resolve(\App\Services\Settings\Contract\SettingContract::class);

        return $trueNull ? $setting->setNull($key) : $setting->get($key, $default, $value);
    }

}

if (!function_exists('generateMenuItemClass')) {

    /**
     * @param        $item
     * @param string $class
     * @return string
     */
    function generateMenuItemClass($item, $class = '')
    {
        $class .= $item->hasChildren() ? ' has-submenu  ' : '';

        $class .= $item->attr('class') ? ' ' . $item->attr('class') : '';

        $class .= $item->isActive ? ' is-active' : '';

        return $class;
    }

}

if (!function_exists('featuredImage')) {

    /**
     * Get Featured image of the post,
     * Fallback to default
     *
     * @param      $post
     * @param bool $thumb
     * @return string
     * @throws Exception
     */
    function featuredImage($post, $thumb = false)
    {
        if ($post->featuredImage) {

            if ($thumb && optional($post->featuredImage)->variations['thumbnail']['path']) {
                return $post->featuredImage->variations['large']['path'];
            }

            if (optional($post->featuredImage)->variations['large']['path']) {
                return $post->featuredImage->variations['large']['path'];
            }

        }

        return url(mix($thumb ? '/site/defaults/post-image-thumbnail.png' : '/site/defaults/post-image-large.png'));

    }

}

if (!function_exists('getGoogleAnalytics')) {

    /**
     *
     * Get Google analytics code
     *
     * @param $id
     * @return string
     */
    function getGoogleAnalytics($id = null)
    {
        $id = $id ? $id : setting('google_analytics');

        if (!$id) return '';

        $script = "<script async src='https://www.googletagmanager.com/gtag/js?id={$id}'></script>\n";
        $script .= "<script>\n";
        $script .= "\twindow.dataLayer = window.dataLayer || [];\n";
        $script .= "\tfunction gtag() { dataLayer.push(arguments); }\n";
        $script .= "\tgtag('js', new Date()); gtag('config', '{$id}');\n";
        $script .= "</script>";

        return $script;

    }
}

if (!function_exists('getGoogleAdsense')) {

    /**
     *
     * Get Google adsense verification code
     *
     * @param      $id
     * @return string
     */
    function getGoogleAdsense($id = null)
    {
        $id = $id ? $id : setting('google_adsense');

        if (!$id) return '';

        $script = "<script async src='//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js'></script>\n";
        $script .= "<script>\n";
        $script .= "\t(adsbygoogle = window.adsbygoogle || []).push({\n";
        $script .= "\t\tgoogle_ad_client: '{$id}',\n";
        $script .= "\t\tenable_page_level_ads: true\n";
        $script .= "\t});\n";
        $script .= "</script>";

        return $script;

    }
}

if (!function_exists('removeQueryFromUrl')) {

    /**
     *
     * Remove Query string from url
     *
     * @param string $url
     * @param string $concat
     * @return string
     */
    function removeQueryFromUrl($url, $concat = '')
    {
        $url = trim(str_replace(' ', '', strtolower($url)));

        $url = parse_url(filter_var($url, FILTER_SANITIZE_URL));

        return "{$url['scheme']}://{$url['host']}{$url['path']}{$concat}";
    }
}

if (!function_exists('humanFileSize')) {

    /**
     *
     * Convert Bytes in human readable file size
     *
     * @param     $size
     * @param int $precision
     * @return string
     */
    function humanFileSize($size, $precision = 2)
    {
        $units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $step = 1024;
        $i = 0;
        while (($size / $step) > 0.9) {
            $size = $size / $step;
            $i++;
        }

        return round($size, $precision) . $units[$i];
    }
}

if (!function_exists('perPage')) {

    /**
     * Get Per page count
     *
     * @param int $default
     * @return string
     */
    function perPage(int $default = null)
    {
        if (request()->perPage) {
            return (int)request()->perPage;
        }

        return (int)$default ? $default : setting('post_default_pagination_count', 12);

    }
}
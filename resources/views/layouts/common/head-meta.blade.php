@php
	$appName = config('app.name');
	$defaultTitle = setting('seo_default_title', $appName);
	$defaultDescription = setting('seo_default_description', 'Welcome to ' . $appName);
	$ogImage = removeQueryFromUrl(url(setting('seo_default_site_image', mix('/site/defaults/site-share.png'))));
	$twitterImage = $ogImage;
	$themeColor = setting('site_theme_color', '#E83E8C');
	$robots = setting('seo_robots_meta', 'index, follow')
@endphp

<link rel="dns-prefetch" href="//fonts.googleapis.com"/>
<link rel="dns-prefetch" href="//fonts.gstatic.com"/>
<link rel="dns-prefetch" href="//ajax.googleapis.com"/>
<link rel="dns-prefetch" href="//www.google-analytics.com"/>

{!! getGoogleAnalytics() !!}

<!--- Title --->
<title itemprop='name'>@yield('seo_title', $defaultTitle)</title>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="MobileOptimized" content="width"/>
<meta name="HandheldFriendly" content="true"/>
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta itemprop="name" content="{{ $appName }}">
<meta name="description" content="@yield('seo_description', $defaultDescription)"/>

@hasSection('article_author')
	<meta name="keywords" content="@yield('keywords')"/>
@endif


<!--- Icons --->
<link rel="apple-touch-icon" sizes="180x180" href="{{ url(mix('/favicons/apple-touch-icon.png')) }}">
<link rel="icon" type="image/png" href="{{ url(mix('/favicons/favicon-32x32.png')) }}" sizes="32x32">
<link rel="icon" type="image/png" href="{{ url(mix('/favicons/favicon-16x16.png')) }}" sizes="16x16">
<link rel="manifest" href="{{ url(mix('/favicons/manifest.json')) }}">
<link rel="mask-icon" href="{{ url(mix('/favicons/safari-pinned-tab.svg')) }}" color="{{ $themeColor }}">
<link rel="shortcut icon" href="{{ url(mix('favicons/favicon-48x48.png')) }}">
<meta name="msapplication-config" content="{{ url(mix('/favicons/browserconfig.xml')) }}">
<meta name="referrer" content="always">

<!--- Open Graph --->
<meta property="og:locale" content="{{ App::getLocale() }}"/>
<meta property="og:type" content="@yield('og_type', 'website')"/>
<meta property="og:title" content="@yield('seo_title', $defaultTitle)"/>
<meta property="og:description" content="@yield('seo_description', $defaultDescription)"/>
<meta property="og:url" content="@yield('seo_url', url('/'))"/>
<meta property="og:site_name" content="{{ $appName }}"/>
<meta property="og:image" content="@yield('seo_image', $ogImage)"/>
@hasSection('updated_at')
	<meta property="og:updated_time" content="@yield('updated_at')"/>
@endif

<!--- Facebook --->
<meta property="fb:app_id" content="{{ config('services.facebook.app_id') }}"/>
<meta property="article:publisher" content="{{ setting('facebook_publisher') }}"/>
<meta property="fb:pages" content="{{ setting('facebook_page_id') }}">
<meta property="fb:admins" content="{{ setting('facebook_admin') }}"/>
@hasSection('article_author')
	<meta property="article:author" content="@yield('article_author')"/>
@endif
@hasSection('author_name')
	<meta property="author" content="@yield('author_name')">
@endif
@hasSection('published_at')
	<meta property="article:published_time" content="@yield('published_at')"/>
@endif
@hasSection('updated_at')
	<meta property="article:modified_time" content="@yield('updated_at')"/>
@endif
@hasSection('article_section')
	<meta property="article:section" content="@yield('article_section')"/>
@endif

{{--https://support.google.com/webmasters/answer/1663744?hl=en&ref_topic=4617741--}}
<!--- Google --->
<link rel="publisher" href="{{ setting('google_plus_url') }}">
@hasSection('link_next')
	<link rel="next" href="@yield('link_next')">
@endif
@hasSection('link_prev')
	<link rel="prev" href="@yield('link_prev')">
@endif
@hasSection('canonical')
	<meta name="original-source" content="@yield('canonical')"/>
	<link rel="canonical" href="@yield('canonical')" itemprop="url">
@endif
@hasSection('article_author_google_plus')
	<link rel="author" href="@yield('article_author_google_plus')">
@endif

<!--- Twitter --->
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:site" content="@yield('twitter_site_username', setting('twitter_site_username'))"/>
@hasSection('twitter_creator')
	<meta name="twitter:creator" content="@yield('twitter_creator')"/>
@endif
<meta name="twitter:title" content="@yield('seo_title', $defaultTitle)"/>
<meta name="twitter:description" content="@yield('seo_description', $defaultDescription)"/>
<meta name="twitter:image" content="@yield('seo_image', $twitterImage)">

<meta name="robots" content="@yield('robots', $robots)"/>

<!--- Misc --->
<meta name="theme-color" content="@yield('theme_color', $themeColor)">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
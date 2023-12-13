<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    @include('eventmie::layouts.meta')

    @include('eventmie::layouts.favicon')

    @include('eventmie::layouts.include_css')

    @yield('stylesheet')
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-65TK3LVKRX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-65TK3LVKRX');
    </script>
</head>

<body class="home" {!! is_rtl() ? 'dir="rtl"' : '' !!}>

    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
        your browser</a> to improve your experience.</p>
    <![endif]-->

    {{-- Ziggy directive --}}
    @routes

    {{-- Main wrapper --}}
    <div id="eventmie_app">

        @include('eventmie::layouts.header')

        @php
            $no_breadcrumb = ['eventmie.welcome', 'eventmie.events_show', 'eventmie.login', 'eventmie.register', 'eventmie.register_show', 'eventmie.password.request', 'eventmie.password.reset', 'eventmie.o_dashboard', 'eventmie.myevents_index', 'eventmie.myevents_index', 'eventmie.myevents_form', 'eventmie.obookings_index', 'eventmie.event_earning_index', 'eventmie.tags_form', 'eventmie.myvenues.index', 'eventmie.venues.show', 'eventmie.ticket_scan', 'myglists_index', 'sub_organizer.index', 'myglists_index', 'manage_reviews.index', 'pos.index', 'eventmie.ticket_scan', 'scanner.index', 'pos.o_dashboard', 'scanner.o_dashboard', 'organiser_show'];
        @endphp
        @if (!in_array(Route::currentRouteName(), $no_breadcrumb))
            @include('eventmie::layouts.breadcrumb')
        @endif

        <section class="db-wrapper">

            {{-- page content --}}
            @yield('content')

            {{-- set progress bar --}}
            <vue-progress-bar></vue-progress-bar>
        </section>

        @include('eventmie::layouts.footer')

    </div>
    <!--Main wrapper end-->

    @include('eventmie::layouts.include_js')

    {{-- Page specific javascript --}}
    @yield('javascript')
    <script type="text/javascript" id="zsiqchat">var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode: "siq175cea106717d3dda3022cac187cb6bd6284ff376097443c09adeb107ba5581a", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="https://salesiq.zohopublic.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);</script>
</body>

</html>

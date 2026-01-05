<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>EGK API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost/api";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.6.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.6.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-cities" class="tocify-header">
                <li class="tocify-item level-1" data-unique="cities">
                    <a href="#cities">Cities</a>
                </li>
                                    <ul id="tocify-subheader-cities" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="cities-GETapi-cities">
                                <a href="#cities-GETapi-cities">Get All Cities</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cities-GETapi-cities--id-">
                                <a href="#cities-GETapi-cities--id-">Get Single City</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-faqs" class="tocify-header">
                <li class="tocify-item level-1" data-unique="faqs">
                    <a href="#faqs">FAQs</a>
                </li>
                                    <ul id="tocify-subheader-faqs" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="faqs-GETapi-faqs">
                                <a href="#faqs-GETapi-faqs">Get All FAQs</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="faqs-GETapi-faqs--id-">
                                <a href="#faqs-GETapi-faqs--id-">Get Single FAQ</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-package-types" class="tocify-header">
                <li class="tocify-item level-1" data-unique="package-types">
                    <a href="#package-types">Package Types</a>
                </li>
                                    <ul id="tocify-subheader-package-types" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="package-types-GETapi-package-types">
                                <a href="#package-types-GETapi-package-types">Get All Package Types</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="package-types-GETapi-package-types--id-">
                                <a href="#package-types-GETapi-package-types--id-">Get Single Package Type</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="package-types-GETapi-package-types-slug--slug-">
                                <a href="#package-types-GETapi-package-types-slug--slug-">Get Package Type by Slug</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-pages" class="tocify-header">
                <li class="tocify-item level-1" data-unique="pages">
                    <a href="#pages">Pages</a>
                </li>
                                    <ul id="tocify-subheader-pages" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="pages-GETapi-pages">
                                <a href="#pages-GETapi-pages">Get All Pages</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="pages-GETapi-pages-slug--slug-">
                                <a href="#pages-GETapi-pages-slug--slug-">Get Page by Slug</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-recent-updates" class="tocify-header">
                <li class="tocify-item level-1" data-unique="recent-updates">
                    <a href="#recent-updates">Recent Updates</a>
                </li>
                                    <ul id="tocify-subheader-recent-updates" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="recent-updates-GETapi-sender-recent-updates">
                                <a href="#recent-updates-GETapi-sender-recent-updates">Get Recent Updates</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-sender-addresses" class="tocify-header">
                <li class="tocify-item level-1" data-unique="sender-addresses">
                    <a href="#sender-addresses">Sender Addresses</a>
                </li>
                                    <ul id="tocify-subheader-sender-addresses" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="sender-addresses-GETapi-sender-addresses">
                                <a href="#sender-addresses-GETapi-sender-addresses">Get All Addresses</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-addresses-POSTapi-sender-addresses">
                                <a href="#sender-addresses-POSTapi-sender-addresses">Create Address</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-addresses-GETapi-sender-addresses--id-">
                                <a href="#sender-addresses-GETapi-sender-addresses--id-">Get Single Address</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-addresses-PUTapi-sender-addresses--id-">
                                <a href="#sender-addresses-PUTapi-sender-addresses--id-">Update Address</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-addresses-DELETEapi-sender-addresses--id-">
                                <a href="#sender-addresses-DELETEapi-sender-addresses--id-">Delete Address</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-addresses-POSTapi-sender-addresses--id--set-default">
                                <a href="#sender-addresses-POSTapi-sender-addresses--id--set-default">Set Address as Default</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-sender-authentication" class="tocify-header">
                <li class="tocify-item level-1" data-unique="sender-authentication">
                    <a href="#sender-authentication">Sender Authentication</a>
                </li>
                                    <ul id="tocify-subheader-sender-authentication" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="sender-authentication-POSTapi-sender-register">
                                <a href="#sender-authentication-POSTapi-sender-register">Register Sender</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-authentication-POSTapi-sender-verify-code">
                                <a href="#sender-authentication-POSTapi-sender-verify-code">Verify Email Code</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-authentication-POSTapi-sender-login">
                                <a href="#sender-authentication-POSTapi-sender-login">Login Sender</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-authentication-POSTapi-sender-forget-password">
                                <a href="#sender-authentication-POSTapi-sender-forget-password">Forget Password</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-authentication-POSTapi-sender-reset-password">
                                <a href="#sender-authentication-POSTapi-sender-reset-password">Reset Password</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-authentication-GETapi-sender-me">
                                <a href="#sender-authentication-GETapi-sender-me">Get Authenticated Sender</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-authentication-PUTapi-sender-update">
                                <a href="#sender-authentication-PUTapi-sender-update">Update Sender Data</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-authentication-POSTapi-sender-switch-type">
                                <a href="#sender-authentication-POSTapi-sender-switch-type">Switch User Type</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-authentication-POSTapi-sender-logout">
                                <a href="#sender-authentication-POSTapi-sender-logout">Logout</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-authentication-POSTapi-sender-refresh">
                                <a href="#sender-authentication-POSTapi-sender-refresh">Refresh Token</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-sender-packages" class="tocify-header">
                <li class="tocify-item level-1" data-unique="sender-packages">
                    <a href="#sender-packages">Sender Packages</a>
                </li>
                                    <ul id="tocify-subheader-sender-packages" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="sender-packages-GETapi-sender-packages">
                                <a href="#sender-packages-GETapi-sender-packages">Get All Packages</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-packages-POSTapi-sender-packages">
                                <a href="#sender-packages-POSTapi-sender-packages">Create Package</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-packages-GETapi-sender-packages--id-">
                                <a href="#sender-packages-GETapi-sender-packages--id-">Get Single Package</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-packages-PUTapi-sender-packages--id-">
                                <a href="#sender-packages-PUTapi-sender-packages--id-">Update Package</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-packages-DELETEapi-sender-packages--id-">
                                <a href="#sender-packages-DELETEapi-sender-packages--id-">Delete Package</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-packages-POSTapi-sender-packages--id--cancel">
                                <a href="#sender-packages-POSTapi-sender-packages--id--cancel">Cancel Package</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-packages-GETapi-sender-packages-active">
                                <a href="#sender-packages-GETapi-sender-packages-active">Get Active Package</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="sender-packages-GETapi-sender-packages-last">
                                <a href="#sender-packages-GETapi-sender-packages-last">Get Last Package</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-settings" class="tocify-header">
                <li class="tocify-item level-1" data-unique="settings">
                    <a href="#settings">Settings</a>
                </li>
                                    <ul id="tocify-subheader-settings" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="settings-GETapi-settings">
                                <a href="#settings-GETapi-settings">Get All Settings</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="settings-GETapi-settings--key-">
                                <a href="#settings-GETapi-settings--key-">Get Setting by Key</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-statistics" class="tocify-header">
                <li class="tocify-item level-1" data-unique="statistics">
                    <a href="#statistics">Statistics</a>
                </li>
                                    <ul id="tocify-subheader-statistics" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="statistics-GETapi-sender-statistics">
                                <a href="#statistics-GETapi-sender-statistics">Get Statistics</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-traveler-packages" class="tocify-header">
                <li class="tocify-item level-1" data-unique="traveler-packages">
                    <a href="#traveler-packages">Traveler Packages</a>
                </li>
                                    <ul id="tocify-subheader-traveler-packages" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="traveler-packages-GETapi-sender-traveler-packages-with-me">
                                <a href="#traveler-packages-GETapi-sender-traveler-packages-with-me">Get Packages with Me</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="traveler-packages-GETapi-sender-traveler-active-packages-now">
                                <a href="#traveler-packages-GETapi-sender-traveler-active-packages-now">Get Active Packages Now by Order</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-traveler-tickets" class="tocify-header">
                <li class="tocify-item level-1" data-unique="traveler-tickets">
                    <a href="#traveler-tickets">Traveler Tickets</a>
                </li>
                                    <ul id="tocify-subheader-traveler-tickets" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="traveler-tickets-GETapi-sender-tickets">
                                <a href="#traveler-tickets-GETapi-sender-tickets">Get All Tickets</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="traveler-tickets-POSTapi-sender-tickets">
                                <a href="#traveler-tickets-POSTapi-sender-tickets">Create Ticket</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="traveler-tickets-GETapi-sender-tickets--id-">
                                <a href="#traveler-tickets-GETapi-sender-tickets--id-">Get Single Ticket</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="traveler-tickets-PUTapi-sender-tickets--id-">
                                <a href="#traveler-tickets-PUTapi-sender-tickets--id-">Update Ticket</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="traveler-tickets-DELETEapi-sender-tickets--id-">
                                <a href="#traveler-tickets-DELETEapi-sender-tickets--id-">Delete Ticket</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="traveler-tickets-GETapi-sender-traveler-active-trips">
                                <a href="#traveler-tickets-GETapi-sender-traveler-active-trips">Get Active Trips</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: January 5, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>EGK API Documentation</p>
<aside>
    <strong>Base URL</strong>: <code>http://localhost/api</code>
</aside>
<pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="cities">Cities</h1>

    <p>APIs for retrieving cities</p>

                                <h2 id="cities-GETapi-cities">Get All Cities</h2>

<p>
</p>

<p>Get a list of all cities with advanced filtering.</p>

<span id="example-requests-GETapi-cities">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/cities?active=1&amp;code=BEY&amp;codes=BEY%2CTRP%2CSAY&amp;locale=en&amp;search=Beirut&amp;order_by=order&amp;order_direction=asc" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/cities"
);

const params = {
    "active": "1",
    "code": "BEY",
    "codes": "BEY,TRP,SAY",
    "locale": "en",
    "search": "Beirut",
    "order_by": "order",
    "order_direction": "asc",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-cities">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Cities retrieved successfully&quot;,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Beirut&quot;,
            &quot;name_en&quot;: &quot;Beirut&quot;,
            &quot;name_ar&quot;: &quot;بيروت&quot;,
            &quot;code&quot;: &quot;BEY&quot;,
            &quot;order&quot;: 1,
            &quot;is_active&quot;: true
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-cities" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-cities"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-cities"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-cities" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-cities">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-cities" data-method="GET"
      data-path="api/cities"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-cities', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-cities"
                    onclick="tryItOut('GETapi-cities');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-cities"
                    onclick="cancelTryOut('GETapi-cities');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-cities"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/cities</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-cities"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-cities"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="GETapi-cities" style="display: none">
            <input type="radio" name="active"
                   value="1"
                   data-endpoint="GETapi-cities"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-cities" style="display: none">
            <input type="radio" name="active"
                   value="0"
                   data-endpoint="GETapi-cities"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Filter by active status (default: true). Example: <code>true</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="code"                data-endpoint="GETapi-cities"
               value="BEY"
               data-component="query">
    <br>
<p>Filter by city code. Example: <code>BEY</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>codes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="codes"                data-endpoint="GETapi-cities"
               value="BEY,TRP,SAY"
               data-component="query">
    <br>
<p>Comma-separated list of codes to filter. Example: <code>BEY,TRP,SAY</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>locale</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="locale"                data-endpoint="GETapi-cities"
               value="en"
               data-component="query">
    <br>
<p>Language locale (en, ar). Defaults to application locale. Example: <code>en</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-cities"
               value="Beirut"
               data-component="query">
    <br>
<p>Search in name (EN/AR) or code. Example: <code>Beirut</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>order_by</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="order_by"                data-endpoint="GETapi-cities"
               value="order"
               data-component="query">
    <br>
<p>Order by field (default: order). Example: <code>order</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>order_direction</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="order_direction"                data-endpoint="GETapi-cities"
               value="asc"
               data-component="query">
    <br>
<p>Order direction (asc, desc). Example: <code>asc</code></p>
            </div>
                </form>

                    <h2 id="cities-GETapi-cities--id-">Get Single City</h2>

<p>
</p>

<p>Get a single city by ID.</p>

<span id="example-requests-GETapi-cities--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/cities/1?locale=en" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/cities/1"
);

const params = {
    "locale": "en",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-cities--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;City retrieved successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Beirut&quot;,
        &quot;name_en&quot;: &quot;Beirut&quot;,
        &quot;name_ar&quot;: &quot;بيروت&quot;,
        &quot;code&quot;: &quot;BEY&quot;,
        &quot;order&quot;: 1,
        &quot;is_active&quot;: true
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;City not found&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-cities--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-cities--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-cities--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-cities--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-cities--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-cities--id-" data-method="GET"
      data-path="api/cities/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-cities--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-cities--id-"
                    onclick="tryItOut('GETapi-cities--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-cities--id-"
                    onclick="cancelTryOut('GETapi-cities--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-cities--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/cities/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-cities--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-cities--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-cities--id-"
               value="1"
               data-component="url">
    <br>
<p>City ID. Example: <code>1</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>locale</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="locale"                data-endpoint="GETapi-cities--id-"
               value="en"
               data-component="query">
    <br>
<p>Language locale (en, ar). Defaults to application locale. Example: <code>en</code></p>
            </div>
                </form>

                <h1 id="faqs">FAQs</h1>

    <p>APIs for retrieving frequently asked questions</p>

                                <h2 id="faqs-GETapi-faqs">Get All FAQs</h2>

<p>
</p>

<p>Get a list of all FAQs ordered by their display order.</p>

<span id="example-requests-GETapi-faqs">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/faqs?active=1&amp;locale=en&amp;order_by=order&amp;order_direction=asc" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/faqs"
);

const params = {
    "active": "1",
    "locale": "en",
    "order_by": "order",
    "order_direction": "asc",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-faqs">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;FAQs retrieved successfully&quot;,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;question&quot;: &quot;What is your return policy?&quot;,
            &quot;answer&quot;: &quot;We offer a 30-day return policy...&quot;,
            &quot;order&quot;: 1,
            &quot;is_active&quot;: true
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-faqs" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-faqs"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-faqs"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-faqs" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-faqs">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-faqs" data-method="GET"
      data-path="api/faqs"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-faqs', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-faqs"
                    onclick="tryItOut('GETapi-faqs');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-faqs"
                    onclick="cancelTryOut('GETapi-faqs');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-faqs"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/faqs</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-faqs"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-faqs"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="GETapi-faqs" style="display: none">
            <input type="radio" name="active"
                   value="1"
                   data-endpoint="GETapi-faqs"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-faqs" style="display: none">
            <input type="radio" name="active"
                   value="0"
                   data-endpoint="GETapi-faqs"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Filter by active status (default: true). Example: <code>true</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>locale</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="locale"                data-endpoint="GETapi-faqs"
               value="en"
               data-component="query">
    <br>
<p>Language locale (en, ar). Defaults to application locale. Example: <code>en</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>order_by</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="order_by"                data-endpoint="GETapi-faqs"
               value="order"
               data-component="query">
    <br>
<p>Order by field (default: order). Example: <code>order</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>order_direction</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="order_direction"                data-endpoint="GETapi-faqs"
               value="asc"
               data-component="query">
    <br>
<p>Order direction (asc, desc). Example: <code>asc</code></p>
            </div>
                </form>

                    <h2 id="faqs-GETapi-faqs--id-">Get Single FAQ</h2>

<p>
</p>

<p>Get a single FAQ by ID.</p>

<span id="example-requests-GETapi-faqs--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/faqs/1?locale=en" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/faqs/1"
);

const params = {
    "locale": "en",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-faqs--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;FAQ retrieved successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;question&quot;: &quot;What is your return policy?&quot;,
        &quot;answer&quot;: &quot;We offer a 30-day return policy...&quot;,
        &quot;order&quot;: 1,
        &quot;is_active&quot;: true
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;FAQ not found&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-faqs--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-faqs--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-faqs--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-faqs--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-faqs--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-faqs--id-" data-method="GET"
      data-path="api/faqs/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-faqs--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-faqs--id-"
                    onclick="tryItOut('GETapi-faqs--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-faqs--id-"
                    onclick="cancelTryOut('GETapi-faqs--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-faqs--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/faqs/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-faqs--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-faqs--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-faqs--id-"
               value="1"
               data-component="url">
    <br>
<p>FAQ ID. Example: <code>1</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>locale</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="locale"                data-endpoint="GETapi-faqs--id-"
               value="en"
               data-component="query">
    <br>
<p>Language locale (en, ar). Defaults to application locale. Example: <code>en</code></p>
            </div>
                </form>

                <h1 id="package-types">Package Types</h1>

    <p>APIs for retrieving package types</p>

                                <h2 id="package-types-GETapi-package-types">Get All Package Types</h2>

<p>
</p>

<p>Get a list of all package types with advanced filtering.</p>

<span id="example-requests-GETapi-package-types">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/package-types?active=1&amp;slug=standard&amp;slugs=standard%2Cexpress%2Covernight&amp;locale=en&amp;search=standard&amp;order_by=order&amp;order_direction=asc" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/package-types"
);

const params = {
    "active": "1",
    "slug": "standard",
    "slugs": "standard,express,overnight",
    "locale": "en",
    "search": "standard",
    "order_by": "order",
    "order_direction": "asc",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-package-types">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Package types retrieved successfully&quot;,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Standard&quot;,
            &quot;name_en&quot;: &quot;Standard&quot;,
            &quot;name_ar&quot;: &quot;عادي&quot;,
            &quot;slug&quot;: &quot;standard&quot;,
            &quot;description&quot;: &quot;Standard delivery&quot;,
            &quot;color&quot;: &quot;#3B82F6&quot;,
            &quot;order&quot;: 1,
            &quot;is_active&quot;: true
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-package-types" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-package-types"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-package-types"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-package-types" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-package-types">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-package-types" data-method="GET"
      data-path="api/package-types"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-package-types', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-package-types"
                    onclick="tryItOut('GETapi-package-types');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-package-types"
                    onclick="cancelTryOut('GETapi-package-types');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-package-types"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/package-types</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-package-types"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-package-types"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="GETapi-package-types" style="display: none">
            <input type="radio" name="active"
                   value="1"
                   data-endpoint="GETapi-package-types"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-package-types" style="display: none">
            <input type="radio" name="active"
                   value="0"
                   data-endpoint="GETapi-package-types"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Filter by active status (default: true). Example: <code>true</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="GETapi-package-types"
               value="standard"
               data-component="query">
    <br>
<p>Filter by package type slug. Example: <code>standard</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>slugs</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slugs"                data-endpoint="GETapi-package-types"
               value="standard,express,overnight"
               data-component="query">
    <br>
<p>Comma-separated list of slugs to filter. Example: <code>standard,express,overnight</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>locale</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="locale"                data-endpoint="GETapi-package-types"
               value="en"
               data-component="query">
    <br>
<p>Language locale (en, ar). Defaults to application locale. Example: <code>en</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-package-types"
               value="standard"
               data-component="query">
    <br>
<p>Search in name (EN/AR) or slug. Example: <code>standard</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>order_by</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="order_by"                data-endpoint="GETapi-package-types"
               value="order"
               data-component="query">
    <br>
<p>Order by field (default: order). Example: <code>order</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>order_direction</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="order_direction"                data-endpoint="GETapi-package-types"
               value="asc"
               data-component="query">
    <br>
<p>Order direction (asc, desc). Example: <code>asc</code></p>
            </div>
                </form>

                    <h2 id="package-types-GETapi-package-types--id-">Get Single Package Type</h2>

<p>
</p>

<p>Get a single package type by ID.</p>

<span id="example-requests-GETapi-package-types--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/package-types/1?locale=en" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/package-types/1"
);

const params = {
    "locale": "en",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-package-types--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Package type retrieved successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Standard&quot;,
        &quot;name_en&quot;: &quot;Standard&quot;,
        &quot;name_ar&quot;: &quot;عادي&quot;,
        &quot;slug&quot;: &quot;standard&quot;,
        &quot;description&quot;: &quot;Standard delivery&quot;,
        &quot;color&quot;: &quot;#3B82F6&quot;,
        &quot;order&quot;: 1,
        &quot;is_active&quot;: true
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Package type not found&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-package-types--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-package-types--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-package-types--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-package-types--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-package-types--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-package-types--id-" data-method="GET"
      data-path="api/package-types/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-package-types--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-package-types--id-"
                    onclick="tryItOut('GETapi-package-types--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-package-types--id-"
                    onclick="cancelTryOut('GETapi-package-types--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-package-types--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/package-types/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-package-types--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-package-types--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-package-types--id-"
               value="1"
               data-component="url">
    <br>
<p>Package Type ID. Example: <code>1</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>locale</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="locale"                data-endpoint="GETapi-package-types--id-"
               value="en"
               data-component="query">
    <br>
<p>Language locale (en, ar). Defaults to application locale. Example: <code>en</code></p>
            </div>
                </form>

                    <h2 id="package-types-GETapi-package-types-slug--slug-">Get Package Type by Slug</h2>

<p>
</p>

<p>Get a single package type by its slug.</p>

<span id="example-requests-GETapi-package-types-slug--slug-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/package-types/slug/standard?locale=en" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/package-types/slug/standard"
);

const params = {
    "locale": "en",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-package-types-slug--slug-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Package type retrieved successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Standard&quot;,
        &quot;name_en&quot;: &quot;Standard&quot;,
        &quot;name_ar&quot;: &quot;عادي&quot;,
        &quot;slug&quot;: &quot;standard&quot;,
        &quot;description&quot;: &quot;Standard delivery&quot;,
        &quot;color&quot;: &quot;#3B82F6&quot;,
        &quot;order&quot;: 1,
        &quot;is_active&quot;: true
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Package type not found&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-package-types-slug--slug-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-package-types-slug--slug-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-package-types-slug--slug-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-package-types-slug--slug-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-package-types-slug--slug-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-package-types-slug--slug-" data-method="GET"
      data-path="api/package-types/slug/{slug}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-package-types-slug--slug-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-package-types-slug--slug-"
                    onclick="tryItOut('GETapi-package-types-slug--slug-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-package-types-slug--slug-"
                    onclick="cancelTryOut('GETapi-package-types-slug--slug-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-package-types-slug--slug-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/package-types/slug/{slug}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-package-types-slug--slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-package-types-slug--slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="GETapi-package-types-slug--slug-"
               value="standard"
               data-component="url">
    <br>
<p>Package type slug. Example: <code>standard</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>locale</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="locale"                data-endpoint="GETapi-package-types-slug--slug-"
               value="en"
               data-component="query">
    <br>
<p>Language locale (en, ar). Defaults to application locale. Example: <code>en</code></p>
            </div>
                </form>

                <h1 id="pages">Pages</h1>

    <p>APIs for retrieving static pages</p>

                                <h2 id="pages-GETapi-pages">Get All Pages</h2>

<p>
</p>

<p>Get a list of all pages. Can be filtered by slug(s) and searched.</p>

<span id="example-requests-GETapi-pages">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/pages?slug=about-us&amp;slugs=about-us%2Ccontact-us%2Cprivacy-policy&amp;active=1&amp;locale=en&amp;search=company&amp;order_by=id&amp;order_direction=asc" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/pages"
);

const params = {
    "slug": "about-us",
    "slugs": "about-us,contact-us,privacy-policy",
    "active": "1",
    "locale": "en",
    "search": "company",
    "order_by": "id",
    "order_direction": "asc",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-pages">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Pages retrieved successfully&quot;,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;slug&quot;: &quot;about-us&quot;,
            &quot;title&quot;: &quot;About Us&quot;,
            &quot;content&quot;: &quot;Welcome to our company...&quot;,
            &quot;meta_title&quot;: &quot;About Us - Company Name&quot;,
            &quot;meta_description&quot;: &quot;Learn more about our company&quot;,
            &quot;is_active&quot;: true
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-pages" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-pages"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-pages"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-pages" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-pages">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-pages" data-method="GET"
      data-path="api/pages"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-pages', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-pages"
                    onclick="tryItOut('GETapi-pages');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-pages"
                    onclick="cancelTryOut('GETapi-pages');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-pages"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/pages</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-pages"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-pages"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="GETapi-pages"
               value="about-us"
               data-component="query">
    <br>
<p>Filter by page slug. Example: <code>about-us</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>slugs</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slugs"                data-endpoint="GETapi-pages"
               value="about-us,contact-us,privacy-policy"
               data-component="query">
    <br>
<p>Comma-separated list of slugs to filter. Example: <code>about-us,contact-us,privacy-policy</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="GETapi-pages" style="display: none">
            <input type="radio" name="active"
                   value="1"
                   data-endpoint="GETapi-pages"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-pages" style="display: none">
            <input type="radio" name="active"
                   value="0"
                   data-endpoint="GETapi-pages"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Filter by active status (default: true). Example: <code>true</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>locale</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="locale"                data-endpoint="GETapi-pages"
               value="en"
               data-component="query">
    <br>
<p>Language locale (en, ar). Defaults to application locale. Example: <code>en</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-pages"
               value="company"
               data-component="query">
    <br>
<p>Search in title or content. Example: <code>company</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>order_by</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="order_by"                data-endpoint="GETapi-pages"
               value="id"
               data-component="query">
    <br>
<p>Order by field (default: id). Example: <code>id</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>order_direction</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="order_direction"                data-endpoint="GETapi-pages"
               value="asc"
               data-component="query">
    <br>
<p>Order direction (asc, desc). Example: <code>asc</code></p>
            </div>
                </form>

                    <h2 id="pages-GETapi-pages-slug--slug-">Get Page by Slug</h2>

<p>
</p>

<p>Get a single page by its slug.</p>

<span id="example-requests-GETapi-pages-slug--slug-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/pages/slug/about-us?locale=en" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/pages/slug/about-us"
);

const params = {
    "locale": "en",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-pages-slug--slug-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Page retrieved successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;slug&quot;: &quot;about-us&quot;,
        &quot;title&quot;: &quot;About Us&quot;,
        &quot;content&quot;: &quot;Welcome to our company...&quot;,
        &quot;meta_title&quot;: &quot;About Us - Company Name&quot;,
        &quot;meta_description&quot;: &quot;Learn more about our company&quot;,
        &quot;is_active&quot;: true
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Page not found&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-pages-slug--slug-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-pages-slug--slug-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-pages-slug--slug-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-pages-slug--slug-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-pages-slug--slug-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-pages-slug--slug-" data-method="GET"
      data-path="api/pages/slug/{slug}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-pages-slug--slug-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-pages-slug--slug-"
                    onclick="tryItOut('GETapi-pages-slug--slug-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-pages-slug--slug-"
                    onclick="cancelTryOut('GETapi-pages-slug--slug-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-pages-slug--slug-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/pages/slug/{slug}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-pages-slug--slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-pages-slug--slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="GETapi-pages-slug--slug-"
               value="about-us"
               data-component="url">
    <br>
<p>Page slug. Example: <code>about-us</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>locale</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="locale"                data-endpoint="GETapi-pages-slug--slug-"
               value="en"
               data-component="query">
    <br>
<p>Language locale (en, ar). Defaults to application locale. Example: <code>en</code></p>
            </div>
                </form>

                <h1 id="recent-updates">Recent Updates</h1>

    <p>APIs for getting recent activity/notifications</p>

                                <h2 id="recent-updates-GETapi-sender-recent-updates">Get Recent Updates</h2>

<p>
</p>

<p>Get recent activity/notifications (packages and tickets updated recently).
For travelers: Recent updates on their tickets and packages linked to tickets
For senders: Recent updates on their packages</p>

<span id="example-requests-GETapi-sender-recent-updates">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/sender/recent-updates?limit=20&amp;type=packages" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/recent-updates"
);

const params = {
    "limit": "20",
    "type": "packages",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-sender-recent-updates">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Recent updates retrieved successfully&quot;,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;type&quot;: &quot;package&quot;,
            &quot;title&quot;: &quot;Package PKG-ABC123 updated&quot;,
            &quot;description&quot;: &quot;Status changed to in_transit&quot;,
            &quot;updated_at&quot;: &quot;2025-01-15T10:30:00Z&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-sender-recent-updates" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-sender-recent-updates"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-sender-recent-updates"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-sender-recent-updates" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-sender-recent-updates">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-sender-recent-updates" data-method="GET"
      data-path="api/sender/recent-updates"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-sender-recent-updates', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-sender-recent-updates"
                    onclick="tryItOut('GETapi-sender-recent-updates');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-sender-recent-updates"
                    onclick="cancelTryOut('GETapi-sender-recent-updates');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-sender-recent-updates"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/sender/recent-updates</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-sender-recent-updates"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-sender-recent-updates"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>limit</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="limit"                data-endpoint="GETapi-sender-recent-updates"
               value="20"
               data-component="query">
    <br>
<p>Number of items to return (default: 20, max: 100). Example: <code>20</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="GETapi-sender-recent-updates"
               value="packages"
               data-component="query">
    <br>
<p>Filter by type (packages, tickets, all). Default: all. Example: <code>packages</code></p>
            </div>
                </form>

                <h1 id="sender-addresses">Sender Addresses</h1>

    <p>APIs for managing sender addresses</p>

                                <h2 id="sender-addresses-GETapi-sender-addresses">Get All Addresses</h2>

<p>
</p>

<p>Get a list of all addresses for the authenticated sender.</p>

<span id="example-requests-GETapi-sender-addresses">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/sender/addresses?type=home&amp;is_default=1&amp;search=Beirut" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/addresses"
);

const params = {
    "type": "home",
    "is_default": "1",
    "search": "Beirut",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-sender-addresses">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Addresses retrieved successfully&quot;,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;title&quot;: &quot;Home&quot;,
            &quot;type&quot;: &quot;home&quot;,
            &quot;is_default&quot;: true,
            &quot;full_address&quot;: &quot;Hamra Plaza, Bliss Street, 4th Floor&quot;,
            &quot;city&quot;: &quot;Beirut&quot;,
            &quot;area&quot;: &quot;Hamra&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-sender-addresses" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-sender-addresses"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-sender-addresses"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-sender-addresses" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-sender-addresses">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-sender-addresses" data-method="GET"
      data-path="api/sender/addresses"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-sender-addresses', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-sender-addresses"
                    onclick="tryItOut('GETapi-sender-addresses');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-sender-addresses"
                    onclick="cancelTryOut('GETapi-sender-addresses');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-sender-addresses"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/sender/addresses</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-sender-addresses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-sender-addresses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="GETapi-sender-addresses"
               value="home"
               data-component="query">
    <br>
<p>Filter by address type (home, office, warehouse, other). Example: <code>home</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>is_default</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="GETapi-sender-addresses" style="display: none">
            <input type="radio" name="is_default"
                   value="1"
                   data-endpoint="GETapi-sender-addresses"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-sender-addresses" style="display: none">
            <input type="radio" name="is_default"
                   value="0"
                   data-endpoint="GETapi-sender-addresses"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Filter by default status. Example: <code>true</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-sender-addresses"
               value="Beirut"
               data-component="query">
    <br>
<p>Search in title, address, city, or area. Example: <code>Beirut</code></p>
            </div>
                </form>

                    <h2 id="sender-addresses-POSTapi-sender-addresses">Create Address</h2>

<p>
</p>

<p>Create a new address for the authenticated sender.</p>

<span id="example-requests-POSTapi-sender-addresses">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/api/sender/addresses" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"title\": \"Home\",
    \"type\": \"home\",
    \"is_default\": true,
    \"full_address\": \"Hamra Plaza, Bliss Street, 4th Floor\",
    \"mobile_number\": \"+96170234567\",
    \"country\": \"Lebanon\",
    \"city\": \"Beirut\",
    \"area\": \"Hamra\",
    \"landmark\": \"Near AUB Main Gate\",
    \"latitude\": 33.8938,
    \"longitude\": 35.5018
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/addresses"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "title": "Home",
    "type": "home",
    "is_default": true,
    "full_address": "Hamra Plaza, Bliss Street, 4th Floor",
    "mobile_number": "+96170234567",
    "country": "Lebanon",
    "city": "Beirut",
    "area": "Hamra",
    "landmark": "Near AUB Main Gate",
    "latitude": 33.8938,
    "longitude": 35.5018
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-sender-addresses">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Address created successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;title&quot;: &quot;Home&quot;,
        &quot;type&quot;: &quot;home&quot;,
        &quot;is_default&quot;: true
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-sender-addresses" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-sender-addresses"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-sender-addresses"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-sender-addresses" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-sender-addresses">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-sender-addresses" data-method="POST"
      data-path="api/sender/addresses"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-sender-addresses', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-sender-addresses"
                    onclick="tryItOut('POSTapi-sender-addresses');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-sender-addresses"
                    onclick="cancelTryOut('POSTapi-sender-addresses');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-sender-addresses"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/sender/addresses</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-sender-addresses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-sender-addresses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>title</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="title"                data-endpoint="POSTapi-sender-addresses"
               value="Home"
               data-component="body">
    <br>
<p>Address title (e.g., Home, Office, Warehouse). Example: <code>Home</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="POSTapi-sender-addresses"
               value="home"
               data-component="body">
    <br>
<p>Address type (home, office, warehouse, other). Example: <code>home</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_default</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="POSTapi-sender-addresses" style="display: none">
            <input type="radio" name="is_default"
                   value="true"
                   data-endpoint="POSTapi-sender-addresses"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-sender-addresses" style="display: none">
            <input type="radio" name="is_default"
                   value="false"
                   data-endpoint="POSTapi-sender-addresses"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>optional Set as default address. Example: <code>true</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>full_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="full_address"                data-endpoint="POSTapi-sender-addresses"
               value="Hamra Plaza, Bliss Street, 4th Floor"
               data-component="body">
    <br>
<p>Full address (Street, building, floor). Example: <code>Hamra Plaza, Bliss Street, 4th Floor</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mobile_number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="mobile_number"                data-endpoint="POSTapi-sender-addresses"
               value="+96170234567"
               data-component="body">
    <br>
<p>optional Mobile number. Example: <code>+96170234567</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="country"                data-endpoint="POSTapi-sender-addresses"
               value="Lebanon"
               data-component="body">
    <br>
<p>optional Country. Example: <code>Lebanon</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="city"                data-endpoint="POSTapi-sender-addresses"
               value="Beirut"
               data-component="body">
    <br>
<p>City. Example: <code>Beirut</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>area</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="area"                data-endpoint="POSTapi-sender-addresses"
               value="Hamra"
               data-component="body">
    <br>
<p>optional Area/District. Example: <code>Hamra</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>landmark</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="landmark"                data-endpoint="POSTapi-sender-addresses"
               value="Near AUB Main Gate"
               data-component="body">
    <br>
<p>optional Landmark. Example: <code>Near AUB Main Gate</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>latitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="latitude"                data-endpoint="POSTapi-sender-addresses"
               value="33.8938"
               data-component="body">
    <br>
<p>optional Latitude. Example: <code>33.8938</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>longitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="longitude"                data-endpoint="POSTapi-sender-addresses"
               value="35.5018"
               data-component="body">
    <br>
<p>optional Longitude. Example: <code>35.5018</code></p>
        </div>
        </form>

                    <h2 id="sender-addresses-GETapi-sender-addresses--id-">Get Single Address</h2>

<p>
</p>

<p>Get a single address by ID.</p>

<span id="example-requests-GETapi-sender-addresses--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/sender/addresses/consequatur" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/addresses/consequatur"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-sender-addresses--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Address retrieved successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;title&quot;: &quot;Home&quot;,
        &quot;type&quot;: &quot;home&quot;,
        &quot;is_default&quot;: true,
        &quot;full_address&quot;: &quot;Hamra Plaza, Bliss Street, 4th Floor&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Address not found&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-sender-addresses--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-sender-addresses--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-sender-addresses--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-sender-addresses--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-sender-addresses--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-sender-addresses--id-" data-method="GET"
      data-path="api/sender/addresses/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-sender-addresses--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-sender-addresses--id-"
                    onclick="tryItOut('GETapi-sender-addresses--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-sender-addresses--id-"
                    onclick="cancelTryOut('GETapi-sender-addresses--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-sender-addresses--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/sender/addresses/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-sender-addresses--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-sender-addresses--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="GETapi-sender-addresses--id-"
               value="consequatur"
               data-component="url">
    <br>
<p>The ID of the address. Example: <code>consequatur</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="address"                data-endpoint="GETapi-sender-addresses--id-"
               value="1"
               data-component="url">
    <br>
<p>Address ID. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="sender-addresses-PUTapi-sender-addresses--id-">Update Address</h2>

<p>
</p>

<p>Update an existing address.</p>

<span id="example-requests-PUTapi-sender-addresses--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/api/sender/addresses/consequatur" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"title\": \"Home\",
    \"type\": \"home\",
    \"is_default\": true,
    \"full_address\": \"Updated address\",
    \"mobile_number\": \"+96170234567\",
    \"country\": \"Lebanon\",
    \"city\": \"Beirut\",
    \"area\": \"Hamra\",
    \"landmark\": \"Near AUB Main Gate\",
    \"latitude\": 33.8938,
    \"longitude\": 35.5018
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/addresses/consequatur"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "title": "Home",
    "type": "home",
    "is_default": true,
    "full_address": "Updated address",
    "mobile_number": "+96170234567",
    "country": "Lebanon",
    "city": "Beirut",
    "area": "Hamra",
    "landmark": "Near AUB Main Gate",
    "latitude": 33.8938,
    "longitude": 35.5018
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-sender-addresses--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Address updated successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;title&quot;: &quot;Home&quot;,
        &quot;type&quot;: &quot;home&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-sender-addresses--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-sender-addresses--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-sender-addresses--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-sender-addresses--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-sender-addresses--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-sender-addresses--id-" data-method="PUT"
      data-path="api/sender/addresses/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-sender-addresses--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-sender-addresses--id-"
                    onclick="tryItOut('PUTapi-sender-addresses--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-sender-addresses--id-"
                    onclick="cancelTryOut('PUTapi-sender-addresses--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-sender-addresses--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/sender/addresses/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/sender/addresses/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-sender-addresses--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-sender-addresses--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="PUTapi-sender-addresses--id-"
               value="consequatur"
               data-component="url">
    <br>
<p>The ID of the address. Example: <code>consequatur</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="address"                data-endpoint="PUTapi-sender-addresses--id-"
               value="1"
               data-component="url">
    <br>
<p>Address ID. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>title</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="title"                data-endpoint="PUTapi-sender-addresses--id-"
               value="Home"
               data-component="body">
    <br>
<p>optional Address title. Example: <code>Home</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="PUTapi-sender-addresses--id-"
               value="home"
               data-component="body">
    <br>
<p>optional Address type. Example: <code>home</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_default</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PUTapi-sender-addresses--id-" style="display: none">
            <input type="radio" name="is_default"
                   value="true"
                   data-endpoint="PUTapi-sender-addresses--id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PUTapi-sender-addresses--id-" style="display: none">
            <input type="radio" name="is_default"
                   value="false"
                   data-endpoint="PUTapi-sender-addresses--id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>optional Set as default address. Example: <code>true</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>full_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="full_address"                data-endpoint="PUTapi-sender-addresses--id-"
               value="Updated address"
               data-component="body">
    <br>
<p>optional Full address. Example: <code>Updated address</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mobile_number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="mobile_number"                data-endpoint="PUTapi-sender-addresses--id-"
               value="+96170234567"
               data-component="body">
    <br>
<p>optional Mobile number. Example: <code>+96170234567</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="country"                data-endpoint="PUTapi-sender-addresses--id-"
               value="Lebanon"
               data-component="body">
    <br>
<p>optional Country. Example: <code>Lebanon</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="city"                data-endpoint="PUTapi-sender-addresses--id-"
               value="Beirut"
               data-component="body">
    <br>
<p>optional City. Example: <code>Beirut</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>area</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="area"                data-endpoint="PUTapi-sender-addresses--id-"
               value="Hamra"
               data-component="body">
    <br>
<p>optional Area/District. Example: <code>Hamra</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>landmark</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="landmark"                data-endpoint="PUTapi-sender-addresses--id-"
               value="Near AUB Main Gate"
               data-component="body">
    <br>
<p>optional Landmark. Example: <code>Near AUB Main Gate</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>latitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="latitude"                data-endpoint="PUTapi-sender-addresses--id-"
               value="33.8938"
               data-component="body">
    <br>
<p>optional Latitude. Example: <code>33.8938</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>longitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="longitude"                data-endpoint="PUTapi-sender-addresses--id-"
               value="35.5018"
               data-component="body">
    <br>
<p>optional Longitude. Example: <code>35.5018</code></p>
        </div>
        </form>

                    <h2 id="sender-addresses-DELETEapi-sender-addresses--id-">Delete Address</h2>

<p>
</p>

<p>Soft delete an address.</p>

<span id="example-requests-DELETEapi-sender-addresses--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/api/sender/addresses/consequatur" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/addresses/consequatur"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-sender-addresses--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Address deleted successfully&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-sender-addresses--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-sender-addresses--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-sender-addresses--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-sender-addresses--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-sender-addresses--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-sender-addresses--id-" data-method="DELETE"
      data-path="api/sender/addresses/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-sender-addresses--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-sender-addresses--id-"
                    onclick="tryItOut('DELETEapi-sender-addresses--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-sender-addresses--id-"
                    onclick="cancelTryOut('DELETEapi-sender-addresses--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-sender-addresses--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/sender/addresses/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-sender-addresses--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-sender-addresses--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="DELETEapi-sender-addresses--id-"
               value="consequatur"
               data-component="url">
    <br>
<p>The ID of the address. Example: <code>consequatur</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="address"                data-endpoint="DELETEapi-sender-addresses--id-"
               value="1"
               data-component="url">
    <br>
<p>Address ID. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="sender-addresses-POSTapi-sender-addresses--id--set-default">Set Address as Default</h2>

<p>
</p>

<p>Set an address as the default address for the sender.</p>

<span id="example-requests-POSTapi-sender-addresses--id--set-default">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/api/sender/addresses/1/set-default" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/addresses/1/set-default"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-sender-addresses--id--set-default">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Address set as default successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;is_default&quot;: true
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-sender-addresses--id--set-default" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-sender-addresses--id--set-default"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-sender-addresses--id--set-default"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-sender-addresses--id--set-default" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-sender-addresses--id--set-default">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-sender-addresses--id--set-default" data-method="POST"
      data-path="api/sender/addresses/{id}/set-default"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-sender-addresses--id--set-default', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-sender-addresses--id--set-default"
                    onclick="tryItOut('POSTapi-sender-addresses--id--set-default');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-sender-addresses--id--set-default"
                    onclick="cancelTryOut('POSTapi-sender-addresses--id--set-default');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-sender-addresses--id--set-default"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/sender/addresses/{id}/set-default</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-sender-addresses--id--set-default"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-sender-addresses--id--set-default"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="POSTapi-sender-addresses--id--set-default"
               value="1"
               data-component="url">
    <br>
<p>Address ID. Example: <code>1</code></p>
            </div>
                    </form>

                <h1 id="sender-authentication">Sender Authentication</h1>

    <p>APIs for sender authentication and management</p>

                                <h2 id="sender-authentication-POSTapi-sender-register">Register Sender</h2>

<p>
</p>

<p>Register a new sender and send verification code to email.</p>

<span id="example-requests-POSTapi-sender-register">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/api/sender/register" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"full_name\": \"Ahmed Osama\",
    \"email\": \"ahmed@example.com\",
    \"phone\": \"+96170123456\",
    \"password\": \"password123\",
    \"type\": \"sender\",
    \"password_confirmation\": \"password123\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/register"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "full_name": "Ahmed Osama",
    "email": "ahmed@example.com",
    "phone": "+96170123456",
    "password": "password123",
    "type": "sender",
    "password_confirmation": "password123"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-sender-register">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Registration successful. Verification code sent to your email.&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;email&quot;: &quot;ahmed@example.com&quot;,
        &quot;is_verified&quot;: false,
        &quot;type&quot;: &quot;sender&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-sender-register" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-sender-register"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-sender-register"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-sender-register" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-sender-register">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-sender-register" data-method="POST"
      data-path="api/sender/register"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-sender-register', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-sender-register"
                    onclick="tryItOut('POSTapi-sender-register');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-sender-register"
                    onclick="cancelTryOut('POSTapi-sender-register');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-sender-register"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/sender/register</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-sender-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-sender-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>full_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="full_name"                data-endpoint="POSTapi-sender-register"
               value="Ahmed Osama"
               data-component="body">
    <br>
<p>Sender full name. Example: <code>Ahmed Osama</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-sender-register"
               value="ahmed@example.com"
               data-component="body">
    <br>
<p>Sender email. Example: <code>ahmed@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="POSTapi-sender-register"
               value="+96170123456"
               data-component="body">
    <br>
<p>Sender phone. Example: <code>+96170123456</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-sender-register"
               value="password123"
               data-component="body">
    <br>
<p>Password (min 8 characters). Example: <code>password123</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="POSTapi-sender-register"
               value="sender"
               data-component="body">
    <br>
<p>optional User type (sender or traveler). Defaults to 'sender'. Example: <code>sender</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password_confirmation</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password_confirmation"                data-endpoint="POSTapi-sender-register"
               value="password123"
               data-component="body">
    <br>
<p>Password confirmation. Example: <code>password123</code></p>
        </div>
        </form>

                    <h2 id="sender-authentication-POSTapi-sender-verify-code">Verify Email Code</h2>

<p>
</p>

<p>Verify email with code sent during registration.
In development: code &quot;111111&quot; is accepted without database check.</p>

<span id="example-requests-POSTapi-sender-verify-code">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/api/sender/verify-code" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"ahmed@example.com\",
    \"phone\": \"consequatur\",
    \"code\": \"123456\",
    \"type\": \"email_verification\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/verify-code"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "ahmed@example.com",
    "phone": "consequatur",
    "code": "123456",
    "type": "email_verification"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-sender-verify-code">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Email verified successfully&quot;,
    &quot;data&quot;: {
        &quot;token&quot;: &quot;eyJ0eXAiOiJKV1QiLCJhbGc...&quot;,
        &quot;sender&quot;: {
            &quot;id&quot;: 1,
            &quot;full_name&quot;: &quot;Ahmed Osama&quot;,
            &quot;email&quot;: &quot;ahmed@example.com&quot;,
            &quot;is_verified&quot;: true
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-sender-verify-code" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-sender-verify-code"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-sender-verify-code"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-sender-verify-code" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-sender-verify-code">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-sender-verify-code" data-method="POST"
      data-path="api/sender/verify-code"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-sender-verify-code', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-sender-verify-code"
                    onclick="tryItOut('POSTapi-sender-verify-code');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-sender-verify-code"
                    onclick="cancelTryOut('POSTapi-sender-verify-code');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-sender-verify-code"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/sender/verify-code</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-sender-verify-code"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-sender-verify-code"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-sender-verify-code"
               value="ahmed@example.com"
               data-component="body">
    <br>
<p>Email address. Example: <code>ahmed@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="POSTapi-sender-verify-code"
               value="consequatur"
               data-component="body">
    <br>
<p>This field is required when <code>email</code> is not present. Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="code"                data-endpoint="POSTapi-sender-verify-code"
               value="123456"
               data-component="body">
    <br>
<p>6-digit verification code. Example: <code>123456</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="POSTapi-sender-verify-code"
               value="email_verification"
               data-component="body">
    <br>
<p>Code type. Example: <code>email_verification</code></p>
        </div>
        </form>

                    <h2 id="sender-authentication-POSTapi-sender-login">Login Sender</h2>

<p>
</p>

<p>Login with email or phone and password.</p>

<span id="example-requests-POSTapi-sender-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/api/sender/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email_or_phone\": \"ahmed@example.com\",
    \"password\": \"password123\",
    \"device_id\": \"device123\",
    \"fcm_token\": \"fcm_token_here\",
    \"device_type\": \"android\",
    \"device_name\": \"Samsung Galaxy\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email_or_phone": "ahmed@example.com",
    "password": "password123",
    "device_id": "device123",
    "fcm_token": "fcm_token_here",
    "device_type": "android",
    "device_name": "Samsung Galaxy"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-sender-login">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Login successful&quot;,
    &quot;data&quot;: {
        &quot;token&quot;: &quot;eyJ0eXAiOiJKV1QiLCJhbGc...&quot;,
        &quot;sender&quot;: {
            &quot;id&quot;: 1,
            &quot;full_name&quot;: &quot;Ahmed Osama&quot;,
            &quot;email&quot;: &quot;ahmed@example.com&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-sender-login" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-sender-login"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-sender-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-sender-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-sender-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-sender-login" data-method="POST"
      data-path="api/sender/login"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-sender-login', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-sender-login"
                    onclick="tryItOut('POSTapi-sender-login');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-sender-login"
                    onclick="cancelTryOut('POSTapi-sender-login');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-sender-login"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/sender/login</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-sender-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-sender-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email_or_phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email_or_phone"                data-endpoint="POSTapi-sender-login"
               value="ahmed@example.com"
               data-component="body">
    <br>
<p>Email or phone number. Example: <code>ahmed@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-sender-login"
               value="password123"
               data-component="body">
    <br>
<p>Password. Example: <code>password123</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>device_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="device_id"                data-endpoint="POSTapi-sender-login"
               value="device123"
               data-component="body">
    <br>
<p>optional Device ID. Example: <code>device123</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>fcm_token</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="fcm_token"                data-endpoint="POSTapi-sender-login"
               value="fcm_token_here"
               data-component="body">
    <br>
<p>optional FCM token. Example: <code>fcm_token_here</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>device_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="device_type"                data-endpoint="POSTapi-sender-login"
               value="android"
               data-component="body">
    <br>
<p>optional Device type (ios, android, web). Example: <code>android</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>device_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="device_name"                data-endpoint="POSTapi-sender-login"
               value="Samsung Galaxy"
               data-component="body">
    <br>
<p>optional Device name. Example: <code>Samsung Galaxy</code></p>
        </div>
        </form>

                    <h2 id="sender-authentication-POSTapi-sender-forget-password">Forget Password</h2>

<p>
</p>

<p>Send password reset code to email.</p>

<span id="example-requests-POSTapi-sender-forget-password">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/api/sender/forget-password" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"ahmed@example.com\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/forget-password"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "ahmed@example.com"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-sender-forget-password">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Password reset code sent to your email&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-sender-forget-password" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-sender-forget-password"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-sender-forget-password"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-sender-forget-password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-sender-forget-password">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-sender-forget-password" data-method="POST"
      data-path="api/sender/forget-password"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-sender-forget-password', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-sender-forget-password"
                    onclick="tryItOut('POSTapi-sender-forget-password');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-sender-forget-password"
                    onclick="cancelTryOut('POSTapi-sender-forget-password');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-sender-forget-password"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/sender/forget-password</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-sender-forget-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-sender-forget-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-sender-forget-password"
               value="ahmed@example.com"
               data-component="body">
    <br>
<p>Email address. Example: <code>ahmed@example.com</code></p>
        </div>
        </form>

                    <h2 id="sender-authentication-POSTapi-sender-reset-password">Reset Password</h2>

<p>
</p>

<p>Reset password using verification code.</p>

<span id="example-requests-POSTapi-sender-reset-password">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/api/sender/reset-password" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"ahmed@example.com\",
    \"code\": \"123456\",
    \"password\": \"newpassword123\",
    \"password_confirmation\": \"newpassword123\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/reset-password"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "ahmed@example.com",
    "code": "123456",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-sender-reset-password">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Password reset successfully&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-sender-reset-password" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-sender-reset-password"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-sender-reset-password"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-sender-reset-password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-sender-reset-password">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-sender-reset-password" data-method="POST"
      data-path="api/sender/reset-password"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-sender-reset-password', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-sender-reset-password"
                    onclick="tryItOut('POSTapi-sender-reset-password');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-sender-reset-password"
                    onclick="cancelTryOut('POSTapi-sender-reset-password');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-sender-reset-password"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/sender/reset-password</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-sender-reset-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-sender-reset-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-sender-reset-password"
               value="ahmed@example.com"
               data-component="body">
    <br>
<p>Email address. Example: <code>ahmed@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="code"                data-endpoint="POSTapi-sender-reset-password"
               value="123456"
               data-component="body">
    <br>
<p>6-digit verification code. Example: <code>123456</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-sender-reset-password"
               value="newpassword123"
               data-component="body">
    <br>
<p>New password (min 8 characters). Example: <code>newpassword123</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password_confirmation</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password_confirmation"                data-endpoint="POSTapi-sender-reset-password"
               value="newpassword123"
               data-component="body">
    <br>
<p>Password confirmation. Example: <code>newpassword123</code></p>
        </div>
        </form>

                    <h2 id="sender-authentication-GETapi-sender-me">Get Authenticated Sender</h2>

<p>
</p>

<p>Get current authenticated sender data.</p>

<span id="example-requests-GETapi-sender-me">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/sender/me" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/me"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-sender-me">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Sender data retrieved successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;full_name&quot;: &quot;Ahmed Osama&quot;,
        &quot;email&quot;: &quot;ahmed@example.com&quot;,
        &quot;phone&quot;: &quot;+96170123456&quot;,
        &quot;avatar&quot;: &quot;http://example.com/storage/avatars/avatar.jpg&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-sender-me" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-sender-me"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-sender-me"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-sender-me" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-sender-me">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-sender-me" data-method="GET"
      data-path="api/sender/me"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-sender-me', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-sender-me"
                    onclick="tryItOut('GETapi-sender-me');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-sender-me"
                    onclick="cancelTryOut('GETapi-sender-me');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-sender-me"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/sender/me</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-sender-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-sender-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="sender-authentication-PUTapi-sender-update">Update Sender Data</h2>

<p>
</p>

<p>Update authenticated sender information.</p>

<span id="example-requests-PUTapi-sender-update">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/api/sender/update" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"full_name\": \"Ahmed Osama\",
    \"email\": \"ahmed@example.com\",
    \"phone\": \"+96170123456\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/update"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "full_name": "Ahmed Osama",
    "email": "ahmed@example.com",
    "phone": "+96170123456"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-sender-update">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Sender updated successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;full_name&quot;: &quot;Ahmed Osama&quot;,
        &quot;email&quot;: &quot;ahmed@example.com&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-sender-update" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-sender-update"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-sender-update"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-sender-update" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-sender-update">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-sender-update" data-method="PUT"
      data-path="api/sender/update"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-sender-update', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-sender-update"
                    onclick="tryItOut('PUTapi-sender-update');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-sender-update"
                    onclick="cancelTryOut('PUTapi-sender-update');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-sender-update"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/sender/update</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-sender-update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-sender-update"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>full_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="full_name"                data-endpoint="PUTapi-sender-update"
               value="Ahmed Osama"
               data-component="body">
    <br>
<p>optional Full name. Example: <code>Ahmed Osama</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="PUTapi-sender-update"
               value="ahmed@example.com"
               data-component="body">
    <br>
<p>optional Email. Example: <code>ahmed@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="PUTapi-sender-update"
               value="+96170123456"
               data-component="body">
    <br>
<p>optional Phone. Example: <code>+96170123456</code></p>
        </div>
        </form>

                    <h2 id="sender-authentication-POSTapi-sender-switch-type">Switch User Type</h2>

<p>
</p>

<p>Switch between sender and traveler type. This will update the user's type and invalidate the current token.
User will need to login again after switching.</p>

<span id="example-requests-POSTapi-sender-switch-type">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/api/sender/switch-type" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"type\": \"traveler\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/switch-type"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "type": "traveler"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-sender-switch-type">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;User type switched successfully. Please login again.&quot;,
    &quot;data&quot;: {
        &quot;type&quot;: &quot;traveler&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Validation failed&quot;,
    &quot;errors&quot;: {
        &quot;type&quot;: [
            &quot;The type field is required.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-sender-switch-type" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-sender-switch-type"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-sender-switch-type"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-sender-switch-type" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-sender-switch-type">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-sender-switch-type" data-method="POST"
      data-path="api/sender/switch-type"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-sender-switch-type', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-sender-switch-type"
                    onclick="tryItOut('POSTapi-sender-switch-type');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-sender-switch-type"
                    onclick="cancelTryOut('POSTapi-sender-switch-type');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-sender-switch-type"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/sender/switch-type</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-sender-switch-type"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-sender-switch-type"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="POSTapi-sender-switch-type"
               value="traveler"
               data-component="body">
    <br>
<p>The type to switch to (sender or traveler). Example: <code>traveler</code></p>
        </div>
        </form>

                    <h2 id="sender-authentication-POSTapi-sender-logout">Logout</h2>

<p>
</p>

<p>Logout authenticated sender and invalidate token.</p>

<span id="example-requests-POSTapi-sender-logout">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/api/sender/logout" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/logout"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-sender-logout">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Logout successful&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-sender-logout" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-sender-logout"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-sender-logout"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-sender-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-sender-logout">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-sender-logout" data-method="POST"
      data-path="api/sender/logout"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-sender-logout', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-sender-logout"
                    onclick="tryItOut('POSTapi-sender-logout');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-sender-logout"
                    onclick="cancelTryOut('POSTapi-sender-logout');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-sender-logout"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/sender/logout</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-sender-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-sender-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="sender-authentication-POSTapi-sender-refresh">Refresh Token</h2>

<p>
</p>

<p>Refresh JWT token.</p>

<span id="example-requests-POSTapi-sender-refresh">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/api/sender/refresh" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/refresh"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-sender-refresh">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Token refreshed successfully&quot;,
    &quot;data&quot;: {
        &quot;token&quot;: &quot;eyJ0eXAiOiJKV1QiLCJhbGc...&quot;,
        &quot;token_type&quot;: &quot;bearer&quot;,
        &quot;expires_in&quot;: 3600
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-sender-refresh" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-sender-refresh"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-sender-refresh"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-sender-refresh" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-sender-refresh">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-sender-refresh" data-method="POST"
      data-path="api/sender/refresh"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-sender-refresh', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-sender-refresh"
                    onclick="tryItOut('POSTapi-sender-refresh');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-sender-refresh"
                    onclick="cancelTryOut('POSTapi-sender-refresh');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-sender-refresh"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/sender/refresh</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-sender-refresh"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-sender-refresh"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="sender-packages">Sender Packages</h1>

    <p>APIs for managing sender packages</p>

                                <h2 id="sender-packages-GETapi-sender-packages">Get All Packages</h2>

<p>
</p>

<p>Get a list of all packages for the authenticated sender with advanced filtering.</p>

<span id="example-requests-GETapi-sender-packages">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/sender/packages?status=pending_review&amp;statuses[]=pending_review&amp;statuses[]=approved&amp;package_type_id=1&amp;search=PKG-102&amp;pickup_date_from=2025-11-01&amp;pickup_date_to=2025-11-30&amp;delivery_date_from=2025-11-01&amp;delivery_date_to=2025-11-30" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/packages"
);

const params = {
    "status": "pending_review",
    "statuses[0]": "pending_review",
    "statuses[1]": "approved",
    "package_type_id": "1",
    "search": "PKG-102",
    "pickup_date_from": "2025-11-01",
    "pickup_date_to": "2025-11-30",
    "delivery_date_from": "2025-11-01",
    "delivery_date_to": "2025-11-30",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-sender-packages">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Packages retrieved successfully&quot;,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;tracking_number&quot;: &quot;PKG-ABC123XYZ&quot;,
            &quot;status&quot;: &quot;pending_review&quot;,
            &quot;status_label&quot;: &quot;Pending Review&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-sender-packages" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-sender-packages"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-sender-packages"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-sender-packages" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-sender-packages">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-sender-packages" data-method="GET"
      data-path="api/sender/packages"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-sender-packages', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-sender-packages"
                    onclick="tryItOut('GETapi-sender-packages');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-sender-packages"
                    onclick="cancelTryOut('GETapi-sender-packages');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-sender-packages"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/sender/packages</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-sender-packages"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-sender-packages"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="GETapi-sender-packages"
               value="pending_review"
               data-component="query">
    <br>
<p>Filter by status (pending_review, approved, rejected, paid, in_transit, delivered, cancelled). Example: <code>pending_review</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>statuses</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="statuses[0]"                data-endpoint="GETapi-sender-packages"
               data-component="query">
        <input type="text" style="display: none"
               name="statuses[1]"                data-endpoint="GETapi-sender-packages"
               data-component="query">
    <br>
<p>Filter by multiple statuses.</p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>package_type_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="package_type_id"                data-endpoint="GETapi-sender-packages"
               value="1"
               data-component="query">
    <br>
<p>Filter by package type ID. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-sender-packages"
               value="PKG-102"
               data-component="query">
    <br>
<p>Search in tracking number, receiver name, or description. Example: <code>PKG-102</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>pickup_date_from</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="pickup_date_from"                data-endpoint="GETapi-sender-packages"
               value="2025-11-01"
               data-component="query">
    <br>
<p>date Filter packages with pickup date from. Example: <code>2025-11-01</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>pickup_date_to</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="pickup_date_to"                data-endpoint="GETapi-sender-packages"
               value="2025-11-30"
               data-component="query">
    <br>
<p>date Filter packages with pickup date to. Example: <code>2025-11-30</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>delivery_date_from</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="delivery_date_from"                data-endpoint="GETapi-sender-packages"
               value="2025-11-01"
               data-component="query">
    <br>
<p>date Filter packages with delivery date from. Example: <code>2025-11-01</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>delivery_date_to</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="delivery_date_to"                data-endpoint="GETapi-sender-packages"
               value="2025-11-30"
               data-component="query">
    <br>
<p>date Filter packages with delivery date to. Example: <code>2025-11-30</code></p>
            </div>
                </form>

                    <h2 id="sender-packages-POSTapi-sender-packages">Create Package</h2>

<p>
</p>

<p>Create a new package request. The package will be created with &quot;pending_review&quot; status.</p>

<span id="example-requests-POSTapi-sender-packages">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/api/sender/packages" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "pickup_address_id=1"\
    --form "pickup_full_address=Hamra Plaza, Bliss Street, 4th Floor"\
    --form "pickup_country=vmqeopfuudtdsufvyvddq"\
    --form "pickup_city=Beirut"\
    --form "pickup_area=amniihfqcoynlazghdtqt"\
    --form "pickup_landmark=consequatur"\
    --form "pickup_latitude=-90"\
    --form "pickup_longitude=-179"\
    --form "pickup_date=2025-11-03"\
    --form "pickup_time=14:30"\
    --form "delivery_full_address=Zahle Industrial Zone, Bldg 22, 3rd Floor"\
    --form "delivery_country=eopfuudtdsufvyvddqamn"\
    --form "delivery_city=Zahle"\
    --form "delivery_area=iihfqcoynlazghdtqtqxb"\
    --form "delivery_landmark=consequatur"\
    --form "delivery_latitude=-90"\
    --form "delivery_longitude=-179"\
    --form "delivery_date=2025-11-04"\
    --form "delivery_time=15:00"\
    --form "receiver_name=Elie Haddad"\
    --form "receiver_mobile=+96170234567"\
    --form "receiver_notes=consequatur"\
    --form "package_type_id=1"\
    --form "description=Apple AirPods sealed box"\
    --form "weight=0.5"\
    --form "length=13"\
    --form "width=16"\
    --form "height=5"\
    --form "special_instructions=consequatur"\
    --form "compliance_confirmed=1"\
    --form "image=@C:\Users\ahmednour\AppData\Local\Microsoft\WinGet\Packages\Astronomer.Astro_Microsoft.Winget.Source_8wekyb3d8bbwe\phpF26E.tmp" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/packages"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('pickup_address_id', '1');
body.append('pickup_full_address', 'Hamra Plaza, Bliss Street, 4th Floor');
body.append('pickup_country', 'vmqeopfuudtdsufvyvddq');
body.append('pickup_city', 'Beirut');
body.append('pickup_area', 'amniihfqcoynlazghdtqt');
body.append('pickup_landmark', 'consequatur');
body.append('pickup_latitude', '-90');
body.append('pickup_longitude', '-179');
body.append('pickup_date', '2025-11-03');
body.append('pickup_time', '14:30');
body.append('delivery_full_address', 'Zahle Industrial Zone, Bldg 22, 3rd Floor');
body.append('delivery_country', 'eopfuudtdsufvyvddqamn');
body.append('delivery_city', 'Zahle');
body.append('delivery_area', 'iihfqcoynlazghdtqtqxb');
body.append('delivery_landmark', 'consequatur');
body.append('delivery_latitude', '-90');
body.append('delivery_longitude', '-179');
body.append('delivery_date', '2025-11-04');
body.append('delivery_time', '15:00');
body.append('receiver_name', 'Elie Haddad');
body.append('receiver_mobile', '+96170234567');
body.append('receiver_notes', 'consequatur');
body.append('package_type_id', '1');
body.append('description', 'Apple AirPods sealed box');
body.append('weight', '0.5');
body.append('length', '13');
body.append('width', '16');
body.append('height', '5');
body.append('special_instructions', 'consequatur');
body.append('compliance_confirmed', '1');
body.append('image', document.querySelector('input[name="image"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-sender-packages">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Package created successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;tracking_number&quot;: &quot;PKG-ABC123XYZ&quot;,
        &quot;status&quot;: &quot;pending_review&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-sender-packages" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-sender-packages"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-sender-packages"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-sender-packages" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-sender-packages">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-sender-packages" data-method="POST"
      data-path="api/sender/packages"
      data-authed="0"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-sender-packages', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-sender-packages"
                    onclick="tryItOut('POSTapi-sender-packages');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-sender-packages"
                    onclick="cancelTryOut('POSTapi-sender-packages');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-sender-packages"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/sender/packages</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-sender-packages"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-sender-packages"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_address_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="pickup_address_id"                data-endpoint="POSTapi-sender-packages"
               value="1"
               data-component="body">
    <br>
<p>optional ID of saved pickup address. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_full_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="pickup_full_address"                data-endpoint="POSTapi-sender-packages"
               value="Hamra Plaza, Bliss Street, 4th Floor"
               data-component="body">
    <br>
<p>Full pickup address. Example: <code>Hamra Plaza, Bliss Street, 4th Floor</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_country</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="pickup_country"                data-endpoint="POSTapi-sender-packages"
               value="vmqeopfuudtdsufvyvddq"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>vmqeopfuudtdsufvyvddq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="pickup_city"                data-endpoint="POSTapi-sender-packages"
               value="Beirut"
               data-component="body">
    <br>
<p>Pickup city. Example: <code>Beirut</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_area</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="pickup_area"                data-endpoint="POSTapi-sender-packages"
               value="amniihfqcoynlazghdtqt"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>amniihfqcoynlazghdtqt</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_landmark</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="pickup_landmark"                data-endpoint="POSTapi-sender-packages"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_latitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="pickup_latitude"                data-endpoint="POSTapi-sender-packages"
               value="-90"
               data-component="body">
    <br>
<p>Must be between -90 and 90. Example: <code>-90</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_longitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="pickup_longitude"                data-endpoint="POSTapi-sender-packages"
               value="-179"
               data-component="body">
    <br>
<p>Must be between -180 and 180. Example: <code>-179</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_date</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="pickup_date"                data-endpoint="POSTapi-sender-packages"
               value="2025-11-03"
               data-component="body">
    <br>
<p>Pickup date. Example: <code>2025-11-03</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_time</code></b>&nbsp;&nbsp;
<small>time</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="pickup_time"                data-endpoint="POSTapi-sender-packages"
               value="14:30"
               data-component="body">
    <br>
<p>Pickup time. Example: <code>14:30</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_full_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="delivery_full_address"                data-endpoint="POSTapi-sender-packages"
               value="Zahle Industrial Zone, Bldg 22, 3rd Floor"
               data-component="body">
    <br>
<p>Full delivery address. Example: <code>Zahle Industrial Zone, Bldg 22, 3rd Floor</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_country</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="delivery_country"                data-endpoint="POSTapi-sender-packages"
               value="eopfuudtdsufvyvddqamn"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>eopfuudtdsufvyvddqamn</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="delivery_city"                data-endpoint="POSTapi-sender-packages"
               value="Zahle"
               data-component="body">
    <br>
<p>Delivery city. Example: <code>Zahle</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_area</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="delivery_area"                data-endpoint="POSTapi-sender-packages"
               value="iihfqcoynlazghdtqtqxb"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>iihfqcoynlazghdtqtqxb</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_landmark</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="delivery_landmark"                data-endpoint="POSTapi-sender-packages"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_latitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="delivery_latitude"                data-endpoint="POSTapi-sender-packages"
               value="-90"
               data-component="body">
    <br>
<p>Must be between -90 and 90. Example: <code>-90</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_longitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="delivery_longitude"                data-endpoint="POSTapi-sender-packages"
               value="-179"
               data-component="body">
    <br>
<p>Must be between -180 and 180. Example: <code>-179</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_date</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="delivery_date"                data-endpoint="POSTapi-sender-packages"
               value="2025-11-04"
               data-component="body">
    <br>
<p>Delivery date. Example: <code>2025-11-04</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_time</code></b>&nbsp;&nbsp;
<small>time</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="delivery_time"                data-endpoint="POSTapi-sender-packages"
               value="15:00"
               data-component="body">
    <br>
<p>Delivery time. Example: <code>15:00</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>receiver_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="receiver_name"                data-endpoint="POSTapi-sender-packages"
               value="Elie Haddad"
               data-component="body">
    <br>
<p>Receiver full name. Example: <code>Elie Haddad</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>receiver_mobile</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="receiver_mobile"                data-endpoint="POSTapi-sender-packages"
               value="+96170234567"
               data-component="body">
    <br>
<p>Receiver mobile number. Example: <code>+96170234567</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>receiver_notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="receiver_notes"                data-endpoint="POSTapi-sender-packages"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>package_type_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="package_type_id"                data-endpoint="POSTapi-sender-packages"
               value="1"
               data-component="body">
    <br>
<p>Package type ID. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-sender-packages"
               value="Apple AirPods sealed box"
               data-component="body">
    <br>
<p>Package description. Example: <code>Apple AirPods sealed box</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>weight</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="weight"                data-endpoint="POSTapi-sender-packages"
               value="0.5"
               data-component="body">
    <br>
<p>Package weight in kg. Example: <code>0.5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>length</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="length"                data-endpoint="POSTapi-sender-packages"
               value="13"
               data-component="body">
    <br>
<p>Must be at least 0. Must not be greater than 1000. Example: <code>13</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>width</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="width"                data-endpoint="POSTapi-sender-packages"
               value="16"
               data-component="body">
    <br>
<p>Must be at least 0. Must not be greater than 1000. Example: <code>16</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>height</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="height"                data-endpoint="POSTapi-sender-packages"
               value="5"
               data-component="body">
    <br>
<p>Must be at least 0. Must not be greater than 1000. Example: <code>5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>special_instructions</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="special_instructions"                data-endpoint="POSTapi-sender-packages"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>image</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="image"                data-endpoint="POSTapi-sender-packages"
               value=""
               data-component="body">
    <br>
<p>Must be an image. Must not be greater than 5120 kilobytes. Example: <code>C:\Users\ahmednour\AppData\Local\Microsoft\WinGet\Packages\Astronomer.Astro_Microsoft.Winget.Source_8wekyb3d8bbwe\phpF26E.tmp</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>compliance_confirmed</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
 &nbsp;
 &nbsp;
                <label data-endpoint="POSTapi-sender-packages" style="display: none">
            <input type="radio" name="compliance_confirmed"
                   value="true"
                   data-endpoint="POSTapi-sender-packages"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-sender-packages" style="display: none">
            <input type="radio" name="compliance_confirmed"
                   value="false"
                   data-endpoint="POSTapi-sender-packages"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Confirmation of compliance. Example: <code>true</code></p>
        </div>
        </form>

                    <h2 id="sender-packages-GETapi-sender-packages--id-">Get Single Package</h2>

<p>
</p>

<p>Get a single package by ID with full details.</p>

<span id="example-requests-GETapi-sender-packages--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/sender/packages/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/packages/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-sender-packages--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Package retrieved successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;tracking_number&quot;: &quot;PKG-ABC123XYZ&quot;,
        &quot;status&quot;: &quot;pending_review&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Package not found&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-sender-packages--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-sender-packages--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-sender-packages--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-sender-packages--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-sender-packages--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-sender-packages--id-" data-method="GET"
      data-path="api/sender/packages/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-sender-packages--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-sender-packages--id-"
                    onclick="tryItOut('GETapi-sender-packages--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-sender-packages--id-"
                    onclick="cancelTryOut('GETapi-sender-packages--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-sender-packages--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/sender/packages/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-sender-packages--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-sender-packages--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-sender-packages--id-"
               value="1"
               data-component="url">
    <br>
<p>Package ID. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="sender-packages-PUTapi-sender-packages--id-">Update Package</h2>

<p>
</p>

<p>Update an existing package. Only packages in &quot;pending_review&quot; status can be updated.</p>

<span id="example-requests-PUTapi-sender-packages--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/api/sender/packages/1" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "pickup_address_id=1"\
    --form "pickup_full_address=Hamra Plaza, Bliss Street, 4th Floor"\
    --form "pickup_country=vmqeopfuudtdsufvyvddq"\
    --form "pickup_city=Beirut"\
    --form "pickup_area=amniihfqcoynlazghdtqt"\
    --form "pickup_landmark=consequatur"\
    --form "pickup_latitude=-90"\
    --form "pickup_longitude=-179"\
    --form "pickup_date=2025-11-03"\
    --form "pickup_time=14:30"\
    --form "delivery_full_address=Zahle Industrial Zone, Bldg 22, 3rd Floor"\
    --form "delivery_country=eopfuudtdsufvyvddqamn"\
    --form "delivery_city=Zahle"\
    --form "delivery_area=iihfqcoynlazghdtqtqxb"\
    --form "delivery_landmark=consequatur"\
    --form "delivery_latitude=-90"\
    --form "delivery_longitude=-179"\
    --form "delivery_date=2025-11-04"\
    --form "delivery_time=15:00"\
    --form "receiver_name=Elie Haddad"\
    --form "receiver_mobile=+96170234567"\
    --form "receiver_notes=consequatur"\
    --form "package_type_id=1"\
    --form "description=Apple AirPods sealed box"\
    --form "weight=0.5"\
    --form "length=13"\
    --form "width=16"\
    --form "height=5"\
    --form "special_instructions=consequatur"\
    --form "compliance_confirmed="\
    --form "image=@C:\Users\ahmednour\AppData\Local\Microsoft\WinGet\Packages\Astronomer.Astro_Microsoft.Winget.Source_8wekyb3d8bbwe\phpF28E.tmp" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/packages/1"
);

const headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('pickup_address_id', '1');
body.append('pickup_full_address', 'Hamra Plaza, Bliss Street, 4th Floor');
body.append('pickup_country', 'vmqeopfuudtdsufvyvddq');
body.append('pickup_city', 'Beirut');
body.append('pickup_area', 'amniihfqcoynlazghdtqt');
body.append('pickup_landmark', 'consequatur');
body.append('pickup_latitude', '-90');
body.append('pickup_longitude', '-179');
body.append('pickup_date', '2025-11-03');
body.append('pickup_time', '14:30');
body.append('delivery_full_address', 'Zahle Industrial Zone, Bldg 22, 3rd Floor');
body.append('delivery_country', 'eopfuudtdsufvyvddqamn');
body.append('delivery_city', 'Zahle');
body.append('delivery_area', 'iihfqcoynlazghdtqtqxb');
body.append('delivery_landmark', 'consequatur');
body.append('delivery_latitude', '-90');
body.append('delivery_longitude', '-179');
body.append('delivery_date', '2025-11-04');
body.append('delivery_time', '15:00');
body.append('receiver_name', 'Elie Haddad');
body.append('receiver_mobile', '+96170234567');
body.append('receiver_notes', 'consequatur');
body.append('package_type_id', '1');
body.append('description', 'Apple AirPods sealed box');
body.append('weight', '0.5');
body.append('length', '13');
body.append('width', '16');
body.append('height', '5');
body.append('special_instructions', 'consequatur');
body.append('compliance_confirmed', '');
body.append('image', document.querySelector('input[name="image"]').files[0]);

fetch(url, {
    method: "PUT",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-sender-packages--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Package updated successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;tracking_number&quot;: &quot;PKG-ABC123XYZ&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-sender-packages--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-sender-packages--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-sender-packages--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-sender-packages--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-sender-packages--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-sender-packages--id-" data-method="PUT"
      data-path="api/sender/packages/{id}"
      data-authed="0"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-sender-packages--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-sender-packages--id-"
                    onclick="tryItOut('PUTapi-sender-packages--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-sender-packages--id-"
                    onclick="cancelTryOut('PUTapi-sender-packages--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-sender-packages--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/sender/packages/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/sender/packages/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-sender-packages--id-"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-sender-packages--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-sender-packages--id-"
               value="1"
               data-component="url">
    <br>
<p>Package ID. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_address_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="pickup_address_id"                data-endpoint="PUTapi-sender-packages--id-"
               value="1"
               data-component="body">
    <br>
<p>ID of saved pickup address (optional). The <code>id</code> of an existing record in the sender_addresses table. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_full_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="pickup_full_address"                data-endpoint="PUTapi-sender-packages--id-"
               value="Hamra Plaza, Bliss Street, 4th Floor"
               data-component="body">
    <br>
<p>Full pickup address. Example: <code>Hamra Plaza, Bliss Street, 4th Floor</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_country</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="pickup_country"                data-endpoint="PUTapi-sender-packages--id-"
               value="vmqeopfuudtdsufvyvddq"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>vmqeopfuudtdsufvyvddq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="pickup_city"                data-endpoint="PUTapi-sender-packages--id-"
               value="Beirut"
               data-component="body">
    <br>
<p>Pickup city. Must not be greater than 255 characters. Example: <code>Beirut</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_area</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="pickup_area"                data-endpoint="PUTapi-sender-packages--id-"
               value="amniihfqcoynlazghdtqt"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>amniihfqcoynlazghdtqt</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_landmark</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="pickup_landmark"                data-endpoint="PUTapi-sender-packages--id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_latitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="pickup_latitude"                data-endpoint="PUTapi-sender-packages--id-"
               value="-90"
               data-component="body">
    <br>
<p>Must be between -90 and 90. Example: <code>-90</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_longitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="pickup_longitude"                data-endpoint="PUTapi-sender-packages--id-"
               value="-179"
               data-component="body">
    <br>
<p>Must be between -180 and 180. Example: <code>-179</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="pickup_date"                data-endpoint="PUTapi-sender-packages--id-"
               value="2025-11-03"
               data-component="body">
    <br>
<p>Pickup date (YYYY-MM-DD). Must be a valid date. Must be a date after or equal to <code>today</code>. Example: <code>2025-11-03</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>pickup_time</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="pickup_time"                data-endpoint="PUTapi-sender-packages--id-"
               value="14:30"
               data-component="body">
    <br>
<p>Pickup time (HH:MM). Must be a valid date in the format <code>H:i</code>. Example: <code>14:30</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_full_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="delivery_full_address"                data-endpoint="PUTapi-sender-packages--id-"
               value="Zahle Industrial Zone, Bldg 22, 3rd Floor"
               data-component="body">
    <br>
<p>Full delivery address. Example: <code>Zahle Industrial Zone, Bldg 22, 3rd Floor</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_country</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="delivery_country"                data-endpoint="PUTapi-sender-packages--id-"
               value="eopfuudtdsufvyvddqamn"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>eopfuudtdsufvyvddqamn</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="delivery_city"                data-endpoint="PUTapi-sender-packages--id-"
               value="Zahle"
               data-component="body">
    <br>
<p>Delivery city. Must not be greater than 255 characters. Example: <code>Zahle</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_area</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="delivery_area"                data-endpoint="PUTapi-sender-packages--id-"
               value="iihfqcoynlazghdtqtqxb"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>iihfqcoynlazghdtqtqxb</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_landmark</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="delivery_landmark"                data-endpoint="PUTapi-sender-packages--id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_latitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="delivery_latitude"                data-endpoint="PUTapi-sender-packages--id-"
               value="-90"
               data-component="body">
    <br>
<p>Must be between -90 and 90. Example: <code>-90</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_longitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="delivery_longitude"                data-endpoint="PUTapi-sender-packages--id-"
               value="-179"
               data-component="body">
    <br>
<p>Must be between -180 and 180. Example: <code>-179</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="delivery_date"                data-endpoint="PUTapi-sender-packages--id-"
               value="2025-11-04"
               data-component="body">
    <br>
<p>Delivery date (YYYY-MM-DD). Must be a valid date. Must be a date after or equal to <code>pickup_date</code>. Example: <code>2025-11-04</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>delivery_time</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="delivery_time"                data-endpoint="PUTapi-sender-packages--id-"
               value="15:00"
               data-component="body">
    <br>
<p>Delivery time (HH:MM). Must be a valid date in the format <code>H:i</code>. Example: <code>15:00</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>receiver_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="receiver_name"                data-endpoint="PUTapi-sender-packages--id-"
               value="Elie Haddad"
               data-component="body">
    <br>
<p>Receiver full name. Must not be greater than 255 characters. Example: <code>Elie Haddad</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>receiver_mobile</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="receiver_mobile"                data-endpoint="PUTapi-sender-packages--id-"
               value="+96170234567"
               data-component="body">
    <br>
<p>Receiver mobile number. Must not be greater than 255 characters. Example: <code>+96170234567</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>receiver_notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="receiver_notes"                data-endpoint="PUTapi-sender-packages--id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>package_type_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="package_type_id"                data-endpoint="PUTapi-sender-packages--id-"
               value="1"
               data-component="body">
    <br>
<p>Package type ID. The <code>id</code> of an existing record in the package_types table. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="PUTapi-sender-packages--id-"
               value="Apple AirPods sealed box"
               data-component="body">
    <br>
<p>Package description. Example: <code>Apple AirPods sealed box</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>weight</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="weight"                data-endpoint="PUTapi-sender-packages--id-"
               value="0.5"
               data-component="body">
    <br>
<p>Package weight in kg. Must be at least 0.01. Must not be greater than 1000. Example: <code>0.5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>length</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="length"                data-endpoint="PUTapi-sender-packages--id-"
               value="13"
               data-component="body">
    <br>
<p>Must be at least 0. Must not be greater than 1000. Example: <code>13</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>width</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="width"                data-endpoint="PUTapi-sender-packages--id-"
               value="16"
               data-component="body">
    <br>
<p>Must be at least 0. Must not be greater than 1000. Example: <code>16</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>height</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="height"                data-endpoint="PUTapi-sender-packages--id-"
               value="5"
               data-component="body">
    <br>
<p>Must be at least 0. Must not be greater than 1000. Example: <code>5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>special_instructions</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="special_instructions"                data-endpoint="PUTapi-sender-packages--id-"
               value="consequatur"
               data-component="body">
    <br>
<p>Example: <code>consequatur</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>image</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="image"                data-endpoint="PUTapi-sender-packages--id-"
               value=""
               data-component="body">
    <br>
<p>Must be an image. Must not be greater than 5120 kilobytes. Example: <code>C:\Users\ahmednour\AppData\Local\Microsoft\WinGet\Packages\Astronomer.Astro_Microsoft.Winget.Source_8wekyb3d8bbwe\phpF28E.tmp</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>compliance_confirmed</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
 &nbsp;
 &nbsp;
                <label data-endpoint="PUTapi-sender-packages--id-" style="display: none">
            <input type="radio" name="compliance_confirmed"
                   value="true"
                   data-endpoint="PUTapi-sender-packages--id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PUTapi-sender-packages--id-" style="display: none">
            <input type="radio" name="compliance_confirmed"
                   value="false"
                   data-endpoint="PUTapi-sender-packages--id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Confirmation of compliance with packaging guidelines. Must be accepted. Example: <code>false</code></p>
        </div>
        </form>

                    <h2 id="sender-packages-DELETEapi-sender-packages--id-">Delete Package</h2>

<p>
</p>

<p>Soft delete a package. Use cancel endpoint instead for cancelling packages.</p>

<span id="example-requests-DELETEapi-sender-packages--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/api/sender/packages/consequatur" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/packages/consequatur"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-sender-packages--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Package deleted successfully&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-sender-packages--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-sender-packages--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-sender-packages--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-sender-packages--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-sender-packages--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-sender-packages--id-" data-method="DELETE"
      data-path="api/sender/packages/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-sender-packages--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-sender-packages--id-"
                    onclick="tryItOut('DELETEapi-sender-packages--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-sender-packages--id-"
                    onclick="cancelTryOut('DELETEapi-sender-packages--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-sender-packages--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/sender/packages/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-sender-packages--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-sender-packages--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="DELETEapi-sender-packages--id-"
               value="consequatur"
               data-component="url">
    <br>
<p>The ID of the package. Example: <code>consequatur</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>package</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="package"                data-endpoint="DELETEapi-sender-packages--id-"
               value="1"
               data-component="url">
    <br>
<p>Package ID. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="sender-packages-POSTapi-sender-packages--id--cancel">Cancel Package</h2>

<p>
</p>

<p>Cancel a package. Only packages in &quot;pending_review&quot;, &quot;approved&quot;, or &quot;paid&quot; status can be cancelled.</p>

<span id="example-requests-POSTapi-sender-packages--id--cancel">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/api/sender/packages/1/cancel" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/packages/1/cancel"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-sender-packages--id--cancel">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Package cancelled successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;status&quot;: &quot;cancelled&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Package cannot be cancelled in its current status&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-sender-packages--id--cancel" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-sender-packages--id--cancel"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-sender-packages--id--cancel"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-sender-packages--id--cancel" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-sender-packages--id--cancel">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-sender-packages--id--cancel" data-method="POST"
      data-path="api/sender/packages/{id}/cancel"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-sender-packages--id--cancel', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-sender-packages--id--cancel"
                    onclick="tryItOut('POSTapi-sender-packages--id--cancel');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-sender-packages--id--cancel"
                    onclick="cancelTryOut('POSTapi-sender-packages--id--cancel');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-sender-packages--id--cancel"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/sender/packages/{id}/cancel</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-sender-packages--id--cancel"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-sender-packages--id--cancel"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="POSTapi-sender-packages--id--cancel"
               value="1"
               data-component="url">
    <br>
<p>Package ID. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="sender-packages-GETapi-sender-packages-active">Get Active Package</h2>

<p>
</p>

<p>Get the active package for the authenticated sender. Active packages are those that are not delivered or cancelled (pending_review, approved, rejected, paid, in_transit).</p>

<span id="example-requests-GETapi-sender-packages-active">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/sender/packages/active" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/packages/active"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-sender-packages-active">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Active package retrieved successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;tracking_number&quot;: &quot;PKG-ABC123XYZ&quot;,
        &quot;status&quot;: &quot;in_transit&quot;,
        &quot;status_label&quot;: &quot;In Transit&quot;,
        &quot;pickup_city&quot;: &quot;Beirut&quot;,
        &quot;delivery_city&quot;: &quot;Zahle&quot;,
        &quot;receiver_name&quot;: &quot;Elie Haddad&quot;,
        &quot;receiver_mobile&quot;: &quot;+96170234567&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;No active package found&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-sender-packages-active" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-sender-packages-active"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-sender-packages-active"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-sender-packages-active" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-sender-packages-active">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-sender-packages-active" data-method="GET"
      data-path="api/sender/packages/active"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-sender-packages-active', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-sender-packages-active"
                    onclick="tryItOut('GETapi-sender-packages-active');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-sender-packages-active"
                    onclick="cancelTryOut('GETapi-sender-packages-active');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-sender-packages-active"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/sender/packages/active</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-sender-packages-active"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-sender-packages-active"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="sender-packages-GETapi-sender-packages-last">Get Last Package</h2>

<p>
</p>

<p>Get the most recently created package for the authenticated sender.</p>

<span id="example-requests-GETapi-sender-packages-last">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/sender/packages/last" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/packages/last"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-sender-packages-last">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Last package retrieved successfully&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 5,
        &quot;tracking_number&quot;: &quot;PKG-XYZ789ABC&quot;,
        &quot;status&quot;: &quot;delivered&quot;,
        &quot;status_label&quot;: &quot;Delivered&quot;,
        &quot;pickup_city&quot;: &quot;Beirut&quot;,
        &quot;delivery_city&quot;: &quot;Tripoli&quot;,
        &quot;receiver_name&quot;: &quot;John Doe&quot;,
        &quot;receiver_mobile&quot;: &quot;+96170123456&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;No package found&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-sender-packages-last" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-sender-packages-last"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-sender-packages-last"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-sender-packages-last" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-sender-packages-last">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-sender-packages-last" data-method="GET"
      data-path="api/sender/packages/last"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-sender-packages-last', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-sender-packages-last"
                    onclick="tryItOut('GETapi-sender-packages-last');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-sender-packages-last"
                    onclick="cancelTryOut('GETapi-sender-packages-last');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-sender-packages-last"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/sender/packages/last</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-sender-packages-last"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-sender-packages-last"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="settings">Settings</h1>

    <p>APIs for retrieving application settings</p>

                                <h2 id="settings-GETapi-settings">Get All Settings</h2>

<p>
</p>

<p>Get all application settings as key-value pairs with advanced filtering.</p>

<span id="example-requests-GETapi-settings">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/settings?keys=app_name%2Capp_url%2Car_enabled&amp;type=text&amp;types=text%2Curl&amp;search=app" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/settings"
);

const params = {
    "keys": "app_name,app_url,ar_enabled",
    "type": "text",
    "types": "text,url",
    "search": "app",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-settings">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Settings retrieved successfully&quot;,
    &quot;data&quot;: {
        &quot;app_name&quot;: &quot;EGK&quot;,
        &quot;app_url&quot;: &quot;http://localhost:8009&quot;,
        &quot;app_email&quot;: &quot;info@egk.com&quot;,
        &quot;app_phone&quot;: &quot;+1 (555) 123-4567&quot;,
        &quot;api_base_url&quot;: &quot;http://localhost:8009/api&quot;,
        &quot;ar_enabled&quot;: &quot;1&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-settings" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-settings"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-settings"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-settings" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-settings">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-settings" data-method="GET"
      data-path="api/settings"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-settings', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-settings"
                    onclick="tryItOut('GETapi-settings');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-settings"
                    onclick="cancelTryOut('GETapi-settings');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-settings"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/settings</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-settings"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-settings"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>keys</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="keys"                data-endpoint="GETapi-settings"
               value="app_name,app_url,ar_enabled"
               data-component="query">
    <br>
<p>Comma-separated list of setting keys to retrieve. Example: <code>app_name,app_url,ar_enabled</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="GETapi-settings"
               value="text"
               data-component="query">
    <br>
<p>Filter by setting type (text, url, email, number, image, file). Example: <code>text</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>types</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="types"                data-endpoint="GETapi-settings"
               value="text,url"
               data-component="query">
    <br>
<p>Comma-separated list of types to filter. Example: <code>text,url</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-settings"
               value="app"
               data-component="query">
    <br>
<p>Search in key or description. Example: <code>app</code></p>
            </div>
                </form>

                    <h2 id="settings-GETapi-settings--key-">Get Setting by Key</h2>

<p>
</p>

<p>Get a specific setting value by its key.</p>

<span id="example-requests-GETapi-settings--key-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/settings/app_name" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/settings/app_name"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-settings--key-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Setting retrieved successfully&quot;,
    &quot;data&quot;: {
        &quot;key&quot;: &quot;app_name&quot;,
        &quot;value&quot;: &quot;EGK&quot;,
        &quot;type&quot;: &quot;text&quot;,
        &quot;description&quot;: &quot;Application name&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Setting not found&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-settings--key-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-settings--key-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-settings--key-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-settings--key-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-settings--key-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-settings--key-" data-method="GET"
      data-path="api/settings/{key}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-settings--key-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-settings--key-"
                    onclick="tryItOut('GETapi-settings--key-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-settings--key-"
                    onclick="cancelTryOut('GETapi-settings--key-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-settings--key-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/settings/{key}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-settings--key-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-settings--key-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>key</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="key"                data-endpoint="GETapi-settings--key-"
               value="app_name"
               data-component="url">
    <br>
<p>Setting key. Example: <code>app_name</code></p>
            </div>
                    </form>

                <h1 id="statistics">Statistics</h1>

    <p>APIs for getting statistics dashboard data</p>

                                <h2 id="statistics-GETapi-sender-statistics">Get Statistics</h2>

<p>
</p>

<p>Get statistics dashboard data. Returns different statistics based on user type.
For travelers: total/active tickets, total/delivered packages linked to tickets, assigned drivers count
For senders: total/delivered packages</p>

<span id="example-requests-GETapi-sender-statistics">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/sender/statistics" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/statistics"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-sender-statistics">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Statistics retrieved successfully&quot;,
    &quot;data&quot;: {
        &quot;tickets&quot;: {
            &quot;total&quot;: 10,
            &quot;active&quot;: 5
        },
        &quot;packages&quot;: {
            &quot;total&quot;: 25,
            &quot;delivered&quot;: 15
        },
        &quot;drivers&quot;: {
            &quot;assigned&quot;: 3
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-sender-statistics" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-sender-statistics"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-sender-statistics"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-sender-statistics" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-sender-statistics">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-sender-statistics" data-method="GET"
      data-path="api/sender/statistics"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-sender-statistics', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-sender-statistics"
                    onclick="tryItOut('GETapi-sender-statistics');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-sender-statistics"
                    onclick="cancelTryOut('GETapi-sender-statistics');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-sender-statistics"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/sender/statistics</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-sender-statistics"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-sender-statistics"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="traveler-packages">Traveler Packages</h1>

    <p>APIs for managing packages linked to traveler tickets (only accessible to travelers)</p>

                                <h2 id="traveler-packages-GETapi-sender-traveler-packages-with-me">Get Packages with Me</h2>

<p>
</p>

<p>Get packages linked to the authenticated traveler's active tickets.
Only travelers (type='traveler') can access this endpoint.</p>

<span id="example-requests-GETapi-sender-traveler-packages-with-me">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/sender/traveler/packages-with-me?status=in_transit&amp;statuses[]=in_transit&amp;statuses[]=delivered&amp;package_type_id=1&amp;ticket_id=1&amp;search=PKG-102&amp;pickup_date_from=2025-11-01&amp;pickup_date_to=2025-11-30&amp;delivery_date_from=2025-11-01&amp;delivery_date_to=2025-11-30&amp;page=1&amp;per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/traveler/packages-with-me"
);

const params = {
    "status": "in_transit",
    "statuses[0]": "in_transit",
    "statuses[1]": "delivered",
    "package_type_id": "1",
    "ticket_id": "1",
    "search": "PKG-102",
    "pickup_date_from": "2025-11-01",
    "pickup_date_to": "2025-11-30",
    "delivery_date_from": "2025-11-01",
    "delivery_date_to": "2025-11-30",
    "page": "1",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-sender-traveler-packages-with-me">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
  &quot;success&quot;: true,
  &quot;message&quot;: &quot;Packages retrieved successfully&quot;,
  &quot;data&quot;: [...],
  &quot;meta&quot;: {...}
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-sender-traveler-packages-with-me" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-sender-traveler-packages-with-me"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-sender-traveler-packages-with-me"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-sender-traveler-packages-with-me" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-sender-traveler-packages-with-me">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-sender-traveler-packages-with-me" data-method="GET"
      data-path="api/sender/traveler/packages-with-me"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-sender-traveler-packages-with-me', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-sender-traveler-packages-with-me"
                    onclick="tryItOut('GETapi-sender-traveler-packages-with-me');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-sender-traveler-packages-with-me"
                    onclick="cancelTryOut('GETapi-sender-traveler-packages-with-me');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-sender-traveler-packages-with-me"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/sender/traveler/packages-with-me</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-sender-traveler-packages-with-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-sender-traveler-packages-with-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="GETapi-sender-traveler-packages-with-me"
               value="in_transit"
               data-component="query">
    <br>
<p>Filter by status (pending_review, approved, rejected, paid, in_transit, delivered, cancelled). Example: <code>in_transit</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>statuses</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="statuses[0]"                data-endpoint="GETapi-sender-traveler-packages-with-me"
               data-component="query">
        <input type="text" style="display: none"
               name="statuses[1]"                data-endpoint="GETapi-sender-traveler-packages-with-me"
               data-component="query">
    <br>
<p>Filter by multiple statuses.</p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>package_type_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="package_type_id"                data-endpoint="GETapi-sender-traveler-packages-with-me"
               value="1"
               data-component="query">
    <br>
<p>Filter by package type ID. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ticket_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="ticket_id"                data-endpoint="GETapi-sender-traveler-packages-with-me"
               value="1"
               data-component="query">
    <br>
<p>Filter by ticket ID. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-sender-traveler-packages-with-me"
               value="PKG-102"
               data-component="query">
    <br>
<p>Search in tracking number, receiver name, or description. Example: <code>PKG-102</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>pickup_date_from</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="pickup_date_from"                data-endpoint="GETapi-sender-traveler-packages-with-me"
               value="2025-11-01"
               data-component="query">
    <br>
<p>date Filter packages with pickup date from. Example: <code>2025-11-01</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>pickup_date_to</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="pickup_date_to"                data-endpoint="GETapi-sender-traveler-packages-with-me"
               value="2025-11-30"
               data-component="query">
    <br>
<p>date Filter packages with pickup date to. Example: <code>2025-11-30</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>delivery_date_from</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="delivery_date_from"                data-endpoint="GETapi-sender-traveler-packages-with-me"
               value="2025-11-01"
               data-component="query">
    <br>
<p>date Filter packages with delivery date from. Example: <code>2025-11-01</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>delivery_date_to</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="delivery_date_to"                data-endpoint="GETapi-sender-traveler-packages-with-me"
               value="2025-11-30"
               data-component="query">
    <br>
<p>date Filter packages with delivery date to. Example: <code>2025-11-30</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-sender-traveler-packages-with-me"
               value="1"
               data-component="query">
    <br>
<p>Page number for pagination. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-sender-traveler-packages-with-me"
               value="15"
               data-component="query">
    <br>
<p>Items per page (default: 15, max: 100). Example: <code>15</code></p>
            </div>
                </form>

                    <h2 id="traveler-packages-GETapi-sender-traveler-active-packages-now">Get Active Packages Now by Order</h2>

<p>
</p>

<p>Get active packages (status='in_transit') linked to tickets, sorted by creation date (oldest first).
Only travelers (type='traveler') can access this endpoint.</p>

<span id="example-requests-GETapi-sender-traveler-active-packages-now">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/sender/traveler/active-packages-now?ticket_id=1&amp;search=PKG-102&amp;created_from=2025-11-01&amp;created_to=2025-11-30&amp;page=1&amp;per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/traveler/active-packages-now"
);

const params = {
    "ticket_id": "1",
    "search": "PKG-102",
    "created_from": "2025-11-01",
    "created_to": "2025-11-30",
    "page": "1",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-sender-traveler-active-packages-now">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
  &quot;success&quot;: true,
  &quot;message&quot;: &quot;Active packages retrieved successfully&quot;,
  &quot;data&quot;: [...],
  &quot;meta&quot;: {...}
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-sender-traveler-active-packages-now" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-sender-traveler-active-packages-now"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-sender-traveler-active-packages-now"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-sender-traveler-active-packages-now" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-sender-traveler-active-packages-now">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-sender-traveler-active-packages-now" data-method="GET"
      data-path="api/sender/traveler/active-packages-now"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-sender-traveler-active-packages-now', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-sender-traveler-active-packages-now"
                    onclick="tryItOut('GETapi-sender-traveler-active-packages-now');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-sender-traveler-active-packages-now"
                    onclick="cancelTryOut('GETapi-sender-traveler-active-packages-now');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-sender-traveler-active-packages-now"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/sender/traveler/active-packages-now</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-sender-traveler-active-packages-now"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-sender-traveler-active-packages-now"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ticket_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="ticket_id"                data-endpoint="GETapi-sender-traveler-active-packages-now"
               value="1"
               data-component="query">
    <br>
<p>Filter by ticket ID. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-sender-traveler-active-packages-now"
               value="PKG-102"
               data-component="query">
    <br>
<p>Search in tracking number, receiver name, or description. Example: <code>PKG-102</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>created_from</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="created_from"                data-endpoint="GETapi-sender-traveler-active-packages-now"
               value="2025-11-01"
               data-component="query">
    <br>
<p>date Filter packages created from. Example: <code>2025-11-01</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>created_to</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="created_to"                data-endpoint="GETapi-sender-traveler-active-packages-now"
               value="2025-11-30"
               data-component="query">
    <br>
<p>date Filter packages created to. Example: <code>2025-11-30</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-sender-traveler-active-packages-now"
               value="1"
               data-component="query">
    <br>
<p>Page number for pagination. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-sender-traveler-active-packages-now"
               value="15"
               data-component="query">
    <br>
<p>Items per page (default: 15, max: 100). Example: <code>15</code></p>
            </div>
                </form>

                <h1 id="traveler-tickets">Traveler Tickets</h1>

    <p>APIs for managing traveler tickets (only accessible to travelers)</p>

                                <h2 id="traveler-tickets-GETapi-sender-tickets">Get All Tickets</h2>

<p>
</p>

<p>Get a list of all tickets for the authenticated traveler with advanced filtering.
Only travelers (type='traveler') can access this endpoint.</p>

<span id="example-requests-GETapi-sender-tickets">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/sender/tickets?status=active&amp;statuses[]=draft&amp;statuses[]=active&amp;trip_type=one-way&amp;transport_type=Car&amp;from_city=Beirut&amp;to_city=Tripoli&amp;departure_date_from=2025-11-01&amp;departure_date_to=2025-11-30&amp;search=Beirut&amp;page=1&amp;per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/tickets"
);

const params = {
    "status": "active",
    "statuses[0]": "draft",
    "statuses[1]": "active",
    "trip_type": "one-way",
    "transport_type": "Car",
    "from_city": "Beirut",
    "to_city": "Tripoli",
    "departure_date_from": "2025-11-01",
    "departure_date_to": "2025-11-30",
    "search": "Beirut",
    "page": "1",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-sender-tickets">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
  &quot;success&quot;: true,
  &quot;message&quot;: &quot;Tickets retrieved successfully&quot;,
  &quot;data&quot;: [...],
  &quot;meta&quot;: {...}
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-sender-tickets" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-sender-tickets"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-sender-tickets"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-sender-tickets" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-sender-tickets">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-sender-tickets" data-method="GET"
      data-path="api/sender/tickets"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-sender-tickets', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-sender-tickets"
                    onclick="tryItOut('GETapi-sender-tickets');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-sender-tickets"
                    onclick="cancelTryOut('GETapi-sender-tickets');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-sender-tickets"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/sender/tickets</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-sender-tickets"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-sender-tickets"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="GETapi-sender-tickets"
               value="active"
               data-component="query">
    <br>
<p>Filter by status (draft, active, matched, completed, cancelled). Example: <code>active</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>statuses</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="statuses[0]"                data-endpoint="GETapi-sender-tickets"
               data-component="query">
        <input type="text" style="display: none"
               name="statuses[1]"                data-endpoint="GETapi-sender-tickets"
               data-component="query">
    <br>
<p>Filter by multiple statuses.</p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>trip_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="trip_type"                data-endpoint="GETapi-sender-tickets"
               value="one-way"
               data-component="query">
    <br>
<p>Filter by trip type (one-way, round-trip). Example: <code>one-way</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>transport_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="transport_type"                data-endpoint="GETapi-sender-tickets"
               value="Car"
               data-component="query">
    <br>
<p>Filter by transport type. Example: <code>Car</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>from_city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="from_city"                data-endpoint="GETapi-sender-tickets"
               value="Beirut"
               data-component="query">
    <br>
<p>Filter by from city. Example: <code>Beirut</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>to_city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="to_city"                data-endpoint="GETapi-sender-tickets"
               value="Tripoli"
               data-component="query">
    <br>
<p>Filter by to city. Example: <code>Tripoli</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>departure_date_from</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="departure_date_from"                data-endpoint="GETapi-sender-tickets"
               value="2025-11-01"
               data-component="query">
    <br>
<p>date Filter tickets with departure date from. Example: <code>2025-11-01</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>departure_date_to</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="departure_date_to"                data-endpoint="GETapi-sender-tickets"
               value="2025-11-30"
               data-component="query">
    <br>
<p>date Filter tickets with departure date to. Example: <code>2025-11-30</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-sender-tickets"
               value="Beirut"
               data-component="query">
    <br>
<p>Search in cities, transport type, or notes. Example: <code>Beirut</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-sender-tickets"
               value="1"
               data-component="query">
    <br>
<p>Page number for pagination. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-sender-tickets"
               value="15"
               data-component="query">
    <br>
<p>Items per page (default: 15, max: 100). Example: <code>15</code></p>
            </div>
                </form>

                    <h2 id="traveler-tickets-POSTapi-sender-tickets">Create Ticket</h2>

<p>
</p>

<p>Create a new travel ticket. Only travelers (type='traveler') can create tickets.</p>

<span id="example-requests-POSTapi-sender-tickets">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost/api/api/sender/tickets" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"from_city\": \"Beirut\",
    \"to_city\": \"Tripoli\",
    \"full_address\": \"Main Street, Building 5\",
    \"landmark\": \"Near AUB Main Gate\",
    \"latitude\": 33.893791,
    \"longitude\": 35.472163,
    \"trip_type\": \"one-way\",
    \"departure_date\": \"2025-11-26\",
    \"departure_time\": \"11:33\",
    \"transport_type\": \"Car\",
    \"total_weight_limit\": 10,
    \"max_package_count\": 5,
    \"acceptable_package_types\": [
        1,
        2,
        3
    ],
    \"preferred_pickup_area\": \"City Center\",
    \"preferred_delivery_area\": \"Downtown\",
    \"notes_for_senders\": \"No liquids please\",
    \"allow_urgent_packages\": false,
    \"accept_only_verified_senders\": true,
    \"status\": \"active\",
    \"return_date\": \"2025-11-27\",
    \"return_time\": \"14:00\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/tickets"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "from_city": "Beirut",
    "to_city": "Tripoli",
    "full_address": "Main Street, Building 5",
    "landmark": "Near AUB Main Gate",
    "latitude": 33.893791,
    "longitude": 35.472163,
    "trip_type": "one-way",
    "departure_date": "2025-11-26",
    "departure_time": "11:33",
    "transport_type": "Car",
    "total_weight_limit": 10,
    "max_package_count": 5,
    "acceptable_package_types": [
        1,
        2,
        3
    ],
    "preferred_pickup_area": "City Center",
    "preferred_delivery_area": "Downtown",
    "notes_for_senders": "No liquids please",
    "allow_urgent_packages": false,
    "accept_only_verified_senders": true,
    "status": "active",
    "return_date": "2025-11-27",
    "return_time": "14:00"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-sender-tickets">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
  &quot;success&quot;: true,
  &quot;message&quot;: &quot;Ticket created successfully&quot;,
  &quot;data&quot;: {...}
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-sender-tickets" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-sender-tickets"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-sender-tickets"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-sender-tickets" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-sender-tickets">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-sender-tickets" data-method="POST"
      data-path="api/sender/tickets"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-sender-tickets', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-sender-tickets"
                    onclick="tryItOut('POSTapi-sender-tickets');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-sender-tickets"
                    onclick="cancelTryOut('POSTapi-sender-tickets');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-sender-tickets"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/sender/tickets</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-sender-tickets"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-sender-tickets"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>from_city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="from_city"                data-endpoint="POSTapi-sender-tickets"
               value="Beirut"
               data-component="body">
    <br>
<p>From city. Example: <code>Beirut</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>to_city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="to_city"                data-endpoint="POSTapi-sender-tickets"
               value="Tripoli"
               data-component="body">
    <br>
<p>To city. Example: <code>Tripoli</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>full_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="full_address"                data-endpoint="POSTapi-sender-tickets"
               value="Main Street, Building 5"
               data-component="body">
    <br>
<p>Full address. Example: <code>Main Street, Building 5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>landmark</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="landmark"                data-endpoint="POSTapi-sender-tickets"
               value="Near AUB Main Gate"
               data-component="body">
    <br>
<p>optional Landmark. Example: <code>Near AUB Main Gate</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>latitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="latitude"                data-endpoint="POSTapi-sender-tickets"
               value="33.893791"
               data-component="body">
    <br>
<p>optional Latitude coordinate. Example: <code>33.893791</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>longitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="longitude"                data-endpoint="POSTapi-sender-tickets"
               value="35.472163"
               data-component="body">
    <br>
<p>optional Longitude coordinate. Example: <code>35.472163</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>trip_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="trip_type"                data-endpoint="POSTapi-sender-tickets"
               value="one-way"
               data-component="body">
    <br>
<p>Trip type (one-way or round-trip). Example: <code>one-way</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>departure_date</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="departure_date"                data-endpoint="POSTapi-sender-tickets"
               value="2025-11-26"
               data-component="body">
    <br>
<p>Departure date. Example: <code>2025-11-26</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>departure_time</code></b>&nbsp;&nbsp;
<small>time</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="departure_time"                data-endpoint="POSTapi-sender-tickets"
               value="11:33"
               data-component="body">
    <br>
<p>Departure time. Example: <code>11:33</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>transport_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="transport_type"                data-endpoint="POSTapi-sender-tickets"
               value="Car"
               data-component="body">
    <br>
<p>Transport type. Example: <code>Car</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>total_weight_limit</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="total_weight_limit"                data-endpoint="POSTapi-sender-tickets"
               value="10"
               data-component="body">
    <br>
<p>optional Total weight limit in kg. Example: <code>10</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>max_package_count</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="max_package_count"                data-endpoint="POSTapi-sender-tickets"
               value="5"
               data-component="body">
    <br>
<p>optional Maximum package count. Example: <code>5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>acceptable_package_types</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="acceptable_package_types[0]"                data-endpoint="POSTapi-sender-tickets"
               data-component="body">
        <input type="text" style="display: none"
               name="acceptable_package_types[1]"                data-endpoint="POSTapi-sender-tickets"
               data-component="body">
    <br>
<p>optional Array of package type IDs.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>preferred_pickup_area</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="preferred_pickup_area"                data-endpoint="POSTapi-sender-tickets"
               value="City Center"
               data-component="body">
    <br>
<p>optional Preferred pickup area. Example: <code>City Center</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>preferred_delivery_area</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="preferred_delivery_area"                data-endpoint="POSTapi-sender-tickets"
               value="Downtown"
               data-component="body">
    <br>
<p>optional Preferred delivery area. Example: <code>Downtown</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>notes_for_senders</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="notes_for_senders"                data-endpoint="POSTapi-sender-tickets"
               value="No liquids please"
               data-component="body">
    <br>
<p>optional Notes for senders. Example: <code>No liquids please</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>allow_urgent_packages</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="POSTapi-sender-tickets" style="display: none">
            <input type="radio" name="allow_urgent_packages"
                   value="true"
                   data-endpoint="POSTapi-sender-tickets"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-sender-tickets" style="display: none">
            <input type="radio" name="allow_urgent_packages"
                   value="false"
                   data-endpoint="POSTapi-sender-tickets"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>optional Allow urgent packages. Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>accept_only_verified_senders</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="POSTapi-sender-tickets" style="display: none">
            <input type="radio" name="accept_only_verified_senders"
                   value="true"
                   data-endpoint="POSTapi-sender-tickets"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-sender-tickets" style="display: none">
            <input type="radio" name="accept_only_verified_senders"
                   value="false"
                   data-endpoint="POSTapi-sender-tickets"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>optional Accept only verified senders. Example: <code>true</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="POSTapi-sender-tickets"
               value="active"
               data-component="body">
    <br>
<p>optional Status (draft or active). Default: draft. Example: <code>active</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>return_date</code></b>&nbsp;&nbsp;
<small>date</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="return_date"                data-endpoint="POSTapi-sender-tickets"
               value="2025-11-27"
               data-component="body">
    <br>
<p>optional Return date (required for round-trip). Example: <code>2025-11-27</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>return_time</code></b>&nbsp;&nbsp;
<small>time</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="return_time"                data-endpoint="POSTapi-sender-tickets"
               value="14:00"
               data-component="body">
    <br>
<p>optional Return time (required for round-trip). Example: <code>14:00</code></p>
        </div>
        </form>

                    <h2 id="traveler-tickets-GETapi-sender-tickets--id-">Get Single Ticket</h2>

<p>
</p>

<p>Get a single ticket by ID with full details.</p>

<span id="example-requests-GETapi-sender-tickets--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/sender/tickets/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/tickets/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-sender-tickets--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
  &quot;success&quot;: true,
  &quot;message&quot;: &quot;Ticket retrieved successfully&quot;,
  &quot;data&quot;: {...}
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-sender-tickets--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-sender-tickets--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-sender-tickets--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-sender-tickets--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-sender-tickets--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-sender-tickets--id-" data-method="GET"
      data-path="api/sender/tickets/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-sender-tickets--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-sender-tickets--id-"
                    onclick="tryItOut('GETapi-sender-tickets--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-sender-tickets--id-"
                    onclick="cancelTryOut('GETapi-sender-tickets--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-sender-tickets--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/sender/tickets/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-sender-tickets--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-sender-tickets--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-sender-tickets--id-"
               value="1"
               data-component="url">
    <br>
<p>Ticket ID. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="traveler-tickets-PUTapi-sender-tickets--id-">Update Ticket</h2>

<p>
</p>

<p>Update an existing ticket. Only draft or active tickets can be updated.</p>

<span id="example-requests-PUTapi-sender-tickets--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost/api/api/sender/tickets/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"from_city\": \"vmqeopfuudtdsufvyvddq\",
    \"to_city\": \"amniihfqcoynlazghdtqt\",
    \"full_address\": \"qxbajwbpilpmufinllwlo\",
    \"landmark\": \"auydlsmsjuryvojcybzvr\",
    \"latitude\": -90,
    \"longitude\": -179,
    \"trip_type\": \"round-trip\",
    \"departure_date\": \"2107-02-04\",
    \"departure_time\": \"23:18\",
    \"transport_type\": \"mqeopfuudtdsufvyvddqa\",
    \"total_weight_limit\": 45,
    \"max_package_count\": 46,
    \"preferred_pickup_area\": \"iihfqcoynlazghdtqtqxb\",
    \"preferred_delivery_area\": \"ajwbpilpmufinllwloauy\",
    \"notes_for_senders\": \"dlsmsjuryvojcybzvrbyi\",
    \"allow_urgent_packages\": false,
    \"accept_only_verified_senders\": false,
    \"status\": \"draft\",
    \"return_date\": \"2026-01-05T23:18:03\",
    \"return_time\": \"23:18\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/tickets/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "from_city": "vmqeopfuudtdsufvyvddq",
    "to_city": "amniihfqcoynlazghdtqt",
    "full_address": "qxbajwbpilpmufinllwlo",
    "landmark": "auydlsmsjuryvojcybzvr",
    "latitude": -90,
    "longitude": -179,
    "trip_type": "round-trip",
    "departure_date": "2107-02-04",
    "departure_time": "23:18",
    "transport_type": "mqeopfuudtdsufvyvddqa",
    "total_weight_limit": 45,
    "max_package_count": 46,
    "preferred_pickup_area": "iihfqcoynlazghdtqtqxb",
    "preferred_delivery_area": "ajwbpilpmufinllwloauy",
    "notes_for_senders": "dlsmsjuryvojcybzvrbyi",
    "allow_urgent_packages": false,
    "accept_only_verified_senders": false,
    "status": "draft",
    "return_date": "2026-01-05T23:18:03",
    "return_time": "23:18"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-sender-tickets--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
  &quot;success&quot;: true,
  &quot;message&quot;: &quot;Ticket updated successfully&quot;,
  &quot;data&quot;: {...}
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-sender-tickets--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-sender-tickets--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-sender-tickets--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-sender-tickets--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-sender-tickets--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-sender-tickets--id-" data-method="PUT"
      data-path="api/sender/tickets/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-sender-tickets--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-sender-tickets--id-"
                    onclick="tryItOut('PUTapi-sender-tickets--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-sender-tickets--id-"
                    onclick="cancelTryOut('PUTapi-sender-tickets--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-sender-tickets--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/sender/tickets/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/sender/tickets/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-sender-tickets--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-sender-tickets--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-sender-tickets--id-"
               value="1"
               data-component="url">
    <br>
<p>Ticket ID. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>from_city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="from_city"                data-endpoint="PUTapi-sender-tickets--id-"
               value="vmqeopfuudtdsufvyvddq"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>vmqeopfuudtdsufvyvddq</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>to_city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="to_city"                data-endpoint="PUTapi-sender-tickets--id-"
               value="amniihfqcoynlazghdtqt"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>amniihfqcoynlazghdtqt</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>full_address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="full_address"                data-endpoint="PUTapi-sender-tickets--id-"
               value="qxbajwbpilpmufinllwlo"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>qxbajwbpilpmufinllwlo</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>landmark</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="landmark"                data-endpoint="PUTapi-sender-tickets--id-"
               value="auydlsmsjuryvojcybzvr"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>auydlsmsjuryvojcybzvr</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>latitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="latitude"                data-endpoint="PUTapi-sender-tickets--id-"
               value="-90"
               data-component="body">
    <br>
<p>Must be between -90 and 90. Example: <code>-90</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>longitude</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="longitude"                data-endpoint="PUTapi-sender-tickets--id-"
               value="-179"
               data-component="body">
    <br>
<p>Must be between -180 and 180. Example: <code>-179</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>trip_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="trip_type"                data-endpoint="PUTapi-sender-tickets--id-"
               value="round-trip"
               data-component="body">
    <br>
<p>Example: <code>round-trip</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>one-way</code></li> <li><code>round-trip</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>departure_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="departure_date"                data-endpoint="PUTapi-sender-tickets--id-"
               value="2107-02-04"
               data-component="body">
    <br>
<p>Must be a valid date. Must be a date after or equal to <code>today</code>. Example: <code>2107-02-04</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>departure_time</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="departure_time"                data-endpoint="PUTapi-sender-tickets--id-"
               value="23:18"
               data-component="body">
    <br>
<p>Must be a valid date in the format <code>H:i</code>. Example: <code>23:18</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>transport_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="transport_type"                data-endpoint="PUTapi-sender-tickets--id-"
               value="mqeopfuudtdsufvyvddqa"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>mqeopfuudtdsufvyvddqa</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>total_weight_limit</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="total_weight_limit"                data-endpoint="PUTapi-sender-tickets--id-"
               value="45"
               data-component="body">
    <br>
<p>Must be at least 0.01. Example: <code>45</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>max_package_count</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="max_package_count"                data-endpoint="PUTapi-sender-tickets--id-"
               value="46"
               data-component="body">
    <br>
<p>Must be at least 1. Example: <code>46</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>acceptable_package_types</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="acceptable_package_types[0]"                data-endpoint="PUTapi-sender-tickets--id-"
               data-component="body">
        <input type="text" style="display: none"
               name="acceptable_package_types[1]"                data-endpoint="PUTapi-sender-tickets--id-"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the package_types table.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>preferred_pickup_area</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="preferred_pickup_area"                data-endpoint="PUTapi-sender-tickets--id-"
               value="iihfqcoynlazghdtqtqxb"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>iihfqcoynlazghdtqtqxb</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>preferred_delivery_area</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="preferred_delivery_area"                data-endpoint="PUTapi-sender-tickets--id-"
               value="ajwbpilpmufinllwloauy"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>ajwbpilpmufinllwloauy</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>notes_for_senders</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="notes_for_senders"                data-endpoint="PUTapi-sender-tickets--id-"
               value="dlsmsjuryvojcybzvrbyi"
               data-component="body">
    <br>
<p>Must not be greater than 65535 characters. Example: <code>dlsmsjuryvojcybzvrbyi</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>allow_urgent_packages</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PUTapi-sender-tickets--id-" style="display: none">
            <input type="radio" name="allow_urgent_packages"
                   value="true"
                   data-endpoint="PUTapi-sender-tickets--id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PUTapi-sender-tickets--id-" style="display: none">
            <input type="radio" name="allow_urgent_packages"
                   value="false"
                   data-endpoint="PUTapi-sender-tickets--id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>accept_only_verified_senders</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PUTapi-sender-tickets--id-" style="display: none">
            <input type="radio" name="accept_only_verified_senders"
                   value="true"
                   data-endpoint="PUTapi-sender-tickets--id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PUTapi-sender-tickets--id-" style="display: none">
            <input type="radio" name="accept_only_verified_senders"
                   value="false"
                   data-endpoint="PUTapi-sender-tickets--id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="PUTapi-sender-tickets--id-"
               value="draft"
               data-component="body">
    <br>
<p>Example: <code>draft</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>draft</code></li> <li><code>active</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>return_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="return_date"                data-endpoint="PUTapi-sender-tickets--id-"
               value="2026-01-05T23:18:03"
               data-component="body">
    <br>
<p>Must be a valid date. Example: <code>2026-01-05T23:18:03</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>return_time</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="return_time"                data-endpoint="PUTapi-sender-tickets--id-"
               value="23:18"
               data-component="body">
    <br>
<p>Must be a valid date in the format <code>H:i</code>. Example: <code>23:18</code></p>
        </div>
        </form>

                    <h2 id="traveler-tickets-DELETEapi-sender-tickets--id-">Delete Ticket</h2>

<p>
</p>

<p>Soft delete a ticket.</p>

<span id="example-requests-DELETEapi-sender-tickets--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost/api/api/sender/tickets/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/tickets/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-sender-tickets--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Ticket deleted successfully&quot;,
    &quot;data&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-sender-tickets--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-sender-tickets--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-sender-tickets--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-sender-tickets--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-sender-tickets--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-sender-tickets--id-" data-method="DELETE"
      data-path="api/sender/tickets/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-sender-tickets--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-sender-tickets--id-"
                    onclick="tryItOut('DELETEapi-sender-tickets--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-sender-tickets--id-"
                    onclick="cancelTryOut('DELETEapi-sender-tickets--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-sender-tickets--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/sender/tickets/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-sender-tickets--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-sender-tickets--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-sender-tickets--id-"
               value="1"
               data-component="url">
    <br>
<p>Ticket ID. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="traveler-tickets-GETapi-sender-traveler-active-trips">Get Active Trips</h2>

<p>
</p>

<p>Get all active tickets (status='active') for the authenticated traveler with package counts.
Only travelers (type='traveler') can access this endpoint.</p>

<span id="example-requests-GETapi-sender-traveler-active-trips">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost/api/api/sender/traveler/active-trips?trip_type=one-way&amp;transport_type=Car&amp;from_city=Beirut&amp;to_city=Tripoli&amp;departure_date_from=2025-11-01&amp;departure_date_to=2025-11-30&amp;search=Beirut&amp;page=1&amp;per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost/api/api/sender/traveler/active-trips"
);

const params = {
    "trip_type": "one-way",
    "transport_type": "Car",
    "from_city": "Beirut",
    "to_city": "Tripoli",
    "departure_date_from": "2025-11-01",
    "departure_date_to": "2025-11-30",
    "search": "Beirut",
    "page": "1",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-sender-traveler-active-trips">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
  &quot;success&quot;: true,
  &quot;message&quot;: &quot;Active trips retrieved successfully&quot;,
  &quot;data&quot;: [...],
  &quot;meta&quot;: {...}
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-sender-traveler-active-trips" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-sender-traveler-active-trips"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-sender-traveler-active-trips"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-sender-traveler-active-trips" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-sender-traveler-active-trips">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-sender-traveler-active-trips" data-method="GET"
      data-path="api/sender/traveler/active-trips"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-sender-traveler-active-trips', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-sender-traveler-active-trips"
                    onclick="tryItOut('GETapi-sender-traveler-active-trips');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-sender-traveler-active-trips"
                    onclick="cancelTryOut('GETapi-sender-traveler-active-trips');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-sender-traveler-active-trips"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/sender/traveler/active-trips</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-sender-traveler-active-trips"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-sender-traveler-active-trips"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>trip_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="trip_type"                data-endpoint="GETapi-sender-traveler-active-trips"
               value="one-way"
               data-component="query">
    <br>
<p>Filter by trip type (one-way, round-trip). Example: <code>one-way</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>transport_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="transport_type"                data-endpoint="GETapi-sender-traveler-active-trips"
               value="Car"
               data-component="query">
    <br>
<p>Filter by transport type. Example: <code>Car</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>from_city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="from_city"                data-endpoint="GETapi-sender-traveler-active-trips"
               value="Beirut"
               data-component="query">
    <br>
<p>Filter by from city. Example: <code>Beirut</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>to_city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="to_city"                data-endpoint="GETapi-sender-traveler-active-trips"
               value="Tripoli"
               data-component="query">
    <br>
<p>Filter by to city. Example: <code>Tripoli</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>departure_date_from</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="departure_date_from"                data-endpoint="GETapi-sender-traveler-active-trips"
               value="2025-11-01"
               data-component="query">
    <br>
<p>date Filter tickets with departure date from. Example: <code>2025-11-01</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>departure_date_to</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="departure_date_to"                data-endpoint="GETapi-sender-traveler-active-trips"
               value="2025-11-30"
               data-component="query">
    <br>
<p>date Filter tickets with departure date to. Example: <code>2025-11-30</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-sender-traveler-active-trips"
               value="Beirut"
               data-component="query">
    <br>
<p>Search in cities, transport type, or notes. Example: <code>Beirut</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-sender-traveler-active-trips"
               value="1"
               data-component="query">
    <br>
<p>Page number for pagination. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-sender-traveler-active-trips"
               value="15"
               data-component="query">
    <br>
<p>Items per page (default: 15, max: 100). Example: <code>15</code></p>
            </div>
                </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>

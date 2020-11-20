Countly.init({
    app_key: countlyAppKey,
    url: "https://nis.count.ly",
    debug: false
});

Countly.begin_session();

//track sessions automatically
Countly.track_sessions();

//track pageviews automatically
Countly.track_pageview();

//track any clicks to webpages automatically
Countly.track_clicks();

//track link clicks automatically
Countly.track_links();

//track form submissions automatically
Countly.track_forms();

//track javascript errors
Countly.track_errors({jquery:"1.10", jqueryui:"1.10"});
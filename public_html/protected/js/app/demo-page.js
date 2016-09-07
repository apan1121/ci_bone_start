//Load common code that includes config, then load the app logic for this page.
require([jsVars.baseResUrl + 'js/lib/common.js'], function(common) {
    require([
        'jquery',
        'app/app',


    ], function($, App) {
        var app = new App({ "HeaderBarTrans": true });
    })
});

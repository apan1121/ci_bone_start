//The build will inline common dependencies into this file.

//For any third party dependencies, like jQuery, place them in the lib folder.

//Configure loading modules from the lib directory,
//except for 'app' ones, which are in a sibling
//directory.
requirejs.config({
    baseUrl: jsVars.baseResUrl+'js',
    // The shim config allows us to configure dependencies for
    // scripts that do not call define() to register a module
    shim: {
        underscore: {
            exports: '_'
        },
        underscore_string: {
            deps: [
                'underscore',
            ],
        },
        backbone: {
            deps: [
                'underscore',
                'jquery'
            ],
            exports: 'Backbone'
        },
        backbone_validation: {
            deps: [
                'backbone',
            ],
        },
    },
    urlArgs: "ver="+jsVars.version,
    paths: {
        jquery: CDN.jquery,
        underscore: CDN.underscore,
        underscore_string: CDN.underscore_string,
        backbone: CDN.backbone,
        backbone_validation: CDN.backbone_validation,
    }
});


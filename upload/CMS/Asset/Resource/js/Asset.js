CMS.Use([], function (CMS) {

    domElement: null,

    CMS.Asset = Class.extend({

        init: function (data) {
            console.log(data);
            $.extend(this, data);

        }

    });

});
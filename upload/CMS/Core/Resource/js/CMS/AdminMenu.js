CMS.Use([], function (CMS) {
    CMS.AdminMenu = Class.extend({

        page: null,
        actions: [],

        domElement: null,

        init: function (data) {
            $.extend(this, data);
            this.domElement = $('#adminMenu');
            this._setupActions();
        },

        // @todo make this use actions that are passed from the backend
        _setupActions: function () {
            var self = this;
            $('.addPage', this.domElement).click(function (e){
                // fire ajax to load add form
                // show form in modal window
                return false;
            });

            $('.editPage', this.domElement).click(function (e){
                // fire ajax to load edit form
                // show form in modal window
                return false;
            });
            
            $('.deletePage', this.domElement).click(function (e){
                if (confirm('Are you sure you want to delete this page?')) {
                    $.get('/direct/page/delete?id=' + self.page.id, function (data) {
                        if (data.code.id <= 0) {
                            alert('Page deleted successfully.');
                            window.location = '/';
                        }
                    }, 'json');
                }
                return false;
            });
        }
    });
});
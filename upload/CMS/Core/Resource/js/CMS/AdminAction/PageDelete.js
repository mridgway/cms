CMS.Use(['Core/CMS.AdminAction.Action'], function (CMS) {
    CMS.AdminAction.PageDelete = CMS.AdminAction.Action.extend({

        init: function (data) {
            this._super(data);
            var self = this;
            this.domElement.click(function (e) {
                self.deletePage();
                return false;
            });
        },

        deletePage: function () {
            var self = this;
            if (confirm('Are you sure you want to delete this page?')) {
                $.get('/direct/page/delete?id=' + self.page.id, function (data) {
                    if (data.code.id <= 0) {
                        alert('Page deleted successfully.');
                        window.location = '/';
                    }
                }, 'json');
            }
        }
    });
});
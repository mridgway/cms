CMS.Use(['Core/CMS.PageAction.Action'], function (CMS) {
    CMS.PageAction.PageDelete = CMS.PageAction.Action.extend({

        showForm: function () {
            var self = this;
            if (confirm('Are you sure you want to delete this page?')) {
                $.get('/direct/page/delete?id=' + self.page.id, function (data) {
                    if (data.code.id <= 0) {
                        alert(data.code.message);
                        window.location = '/';
                    } else {
                        alert(data.code.message);
                    }
                }, 'json');
            }
        }
    });
});
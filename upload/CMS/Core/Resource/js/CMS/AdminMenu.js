CMS.Use([], function (CMS) {
    CMS.AdminMenu = Class.extend({

        page: null,
        actions: [],

        domElement: null,

        init: function (data) {
            $.extend(this, data);
            this.domElement = $('#adminMenu');
            this._setupActions();
            $('#modal').jqm(); // this should be done globally
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
                $.get('/direct/page/edit?id=' + self.page.id, function (data) {
                    if (data.code.id <= 0) {
                        var html = $(data.html);
                        var form = html.is('form') ? html : html.find('form:first');
                        // hook submit
                        form.submit(function (e) {
                            var data = $(this).serialize();
                            $.post('/direct/page/edit?id=' + self.page.id, data, function(data) {
                                alert('test');
                                if (data.code.id <= 0) {
                                    $('#modal').jqmHide();
                                    window.location = '/';
                                } else {
                                    CMS.alert(data.code.message);
                                }
                            }, 'json');
                            return false;
                        });
                        // add cancel link
                        var cancelLink = $('<a>', {
                            click: function () {
                                $('#modal').jqmHide();
                                return false;
                            },
                            text: 'Cancel',
                            href: '#'
                        });
                        form.append(cancelLink);
                        $('#modal').html(html).jqm({modal: true}).jqmShow();
                    }
                }, 'json');
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
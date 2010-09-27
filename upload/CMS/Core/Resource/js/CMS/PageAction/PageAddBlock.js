CMS.Use(['Core/CMS.PageAction.Action'], function (CMS) {
    CMS.PageAction.PageAddBlock = CMS.PageAction.Action.extend({

        page: null, // page id
        location: null, // location id

        caption: 'Add Block',
        name: 'block-add',
        blockTypes: {
            standard: {
                title: 'Standard Text',
                sysname: 'standard'
            },
            shared: {
                title: 'Shared Text',
                sysname: 'shared'
            },
            dynamic: {
                title: 'Dynamic',
                sysname: 'dynamic'
            }
        },
        postback: '/direct/page/add-block',

        init: function (data) {
            this._super(data);
            this._initDomElement();
        },

        _initDomElement: function () {
            var addBlockMenu = $('<dl>', {class: 'addBlockMenu'});
            addBlockMenu.append($('<dt>', {
                html: $('<a>', {
                    text: this.caption,
                    href: '#',
                    click: function () {
                        $(this).parents('dl').find('dd').show('fast');
                    }
                })
            }));
            var self = this;
            $.each(this.blockTypes, function(index, value) {
                var type = $('<dd>');
                type.append($('<a>', {
                    text: value.title,
                    href: '#',
                    click: function () {
                        self.processClick(index);
                    }
                }));
                addBlockMenu.append(type);
            });
            addBlockMenu.find('dd').hide();
            this.domElement = addBlockMenu;
        },

        processClick: function (type) {
            var data = {
                id: this.page,
                location: this.location,
                type: type
            }
            switch(type) {
                case 'standard' :
                    this.clickStandard(data);
                    break;
                case 'shared' :
                    this.clickShared(data);
                    break;
                case 'dynamic' :
                    this.clickDynamic(data);
                    break;
            }
        },

        clickStandard: function (sendData) {
            $.get(this.postback, sendData, function (data) {

            }, 'json');
        },

        clickShared: function (sendData) {
            $.get(this.postback, sendData, function (data) {

            }, 'json')
        },

        clickDynamic: function (sendData) {
            $.get(this.postback, sendData, function (data) {
                
            }, 'json')
        }
    });
});
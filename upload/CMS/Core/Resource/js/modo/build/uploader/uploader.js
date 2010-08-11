YUI(
).add('uploader', function (Y) {
    var Uploader = function(config) {
        Uploader.superclass.constructor.apply(this, arguments);
    };

    Uploader.NAME = 'uploader';

    Uploader.ATTRS = {
        node : {
           value : null,
           validator : function (val) {
               if (Y.Lang.isString(val) && Y.one(val)) {
                   return true;
               }

               return val instanceof Y.Node;
           },
           setter : function (val) {
               if (Y.Lang.isString(val)) {
                   return Y.one(val);
               }

               return val;
           }
        },

        errorNode : {
           value : null,
           validator : function (val) {
               if (Y.Lang.isString(val) && Y.one(val)) {
                   return true;
               }

               return val instanceof Y.Node;
           },
           setter : function (val) {
               if (Y.Lang.isString(val)) {
                   return Y.one(val);
               }

               return val;
           }
        },

        AssetList: {
            value: null
        },

        SWFSettings: {
            value: {}
        },

        SWFUploader: {
            value: null,
            validator: Y.Lang.isObject
        }
    }

    Uploader.HTML_PARSER = {

    };

    Y.extend(Uploader, Y.Widget, {
        initializer: function(config) {
            this.set('node', config.node);
            this.set('errorNode', config.errorNode);
            this.set('AssetList', new Y.AssetList({
                node: Y.one('#new-file-list')
            }));
            this.set('SWFSettings', {
                upload_url : "/direct/asset/manager/upload/",
                flash_url : "/resources/core/swf/swfupload.swf",
                file_size_limit : "20 MB",
                file_post_name : 'file',
                http_success : [201, 202],
                file_queue_limit : 1,
                file_upload_limit : 0,
                button_image_url : '/resources/core/img/upload.gif',
                button_action : SWFUpload.BUTTON_ACTION.SELECT_FILE,
                button_cursor : SWFUpload.CURSOR.HAND,
                button_width : 102,
                button_height : 25,
                button_placeholder : Y.Node.getDOMNode(config.node),
                swfupload_loaded_handler : this.swfUploadLoaded,
                file_dialog_start_handler : this.fileDialogStart,
                file_queued_handler : this.fileQueued,
                file_queue_error_handler : this.fileQueueError,
                file_dialog_complete_handler : this.fileDialogComplete,
                upload_start_handler : this.uploadStart,
                upload_progress_handler : this.uploadProgress,
                upload_error_handler : this.uploadError,
                upload_success_handler : this.uploadSuccess,
                upload_complete_handler : this.uploadComplete,
                custom_settings: {
                    uploader: this
                }
                //debug_handler : this.debug,
                //debug : true
            })
            this.set('SWFUploader', new SWFUpload(this.get('SWFSettings')));

            YUI({
                // filter: 'debug',
                groups : {
                    modo : {
                        base : '/resources/core/js/modo/build/',
                        modules : {
                            'uploader' : {
                                requires : ['widget']
                            }
                        }
                    }
                }
            }).use('uploader', function (Y) {
                Y.use('uploader', function (Y) {
                    try {
                        uploader = new Y.Uploader({
                            node: Y.one('#UploadButton'),
                            errorNode: Y.one('#UploadMessage')
                        });
                    } catch (e) {}
                });
            });
        },

        _setupNewFileList : function () {
           var ds = new Y.DataSource.Local({
               source: this.get('AssetList')
           });

           ds.plug({
               fn: Y.Plugin.DataSourceJSONSchema,
               cfg: {
                   schema: {
                      resultFields: [
                          'id',
                          'thumb',
                          'url_template',
                          'name',
                          'type',
                          'upload_date',
                          'sizes',
                          'actions',
                          'caption'
                      ]
                   }
               }
           });
        },

        swfUploadLoaded : function () {
        },

        fileDialogStart : function () {
        },

        fileQueued : function () {
            this.setButtonDisabled(true);
            this.startUpload();
        },

        fileQueueError : function () {
        },

        fileDialogComplete : function (numFilesSelected, numFilesQueued, queueTotal) {
        },

        uploadStart : function (file) {
            Y.one('#UploadMessage').setContent('<div class="notification">Uploading asset... (0%)</div>');
            return true;
        },

        uploadProgress : function (file, bytesComplete, bytesTotal) {
            var percentComplete = Math.round((bytesComplete/bytesTotal)*100);
            Y.one('#UploadMessage').setContent('<div class="notification">Uploading asset... ('+ percentComplete +'%)</div>');
        },

        uploadError : function (file, errorCode, message) {
            switch(message) {
                case '409' :
                    Y.one('#UploadMessage').setContent('<div class="notification negative">Asset already exists in library.</div>');
                    break;
                case '415' :
                    Y.one('#UploadMessage').setContent('<div class="notification negative">Asset type not allowed.</div>');
                    break;
            }
        },

        uploadSuccess : function (file, data, response) {
            Y.one('#UploadMessage').setContent('<div class="notification positive">Asset uploaded successfully.</div>');
            // Add asset to new file list
            var AssetList = this.customSettings['uploader'].get('AssetList');

            var ds = new Y.DataSource.Local({
               source: data
            });

            ds.plug({
                fn: Y.Plugin.DataSourceJSONSchema, cfg: {
                    schema: {
                        metaFields: {
                            code: 'code',
                            html: 'html',
                            templates: 'templates',
                            rowCount: 'data.rowCount',
                            perPage: 'data.perPage',
                            currentPage: 'data.currentPage'
                        },
                        resultListLocator: "data.assets",
                        resultFields: [
                            'id',
                            'thumb',
                            'url_template',
                            'name',
                            'type',
                            'upload_date',
                            'sizes',
                            'actions',
                            'caption'
                        ]
                    }
                }
            });
            ds.on('response', AssetList.receiveResponse, AssetList);
            ds.sendRequest({});
        },

        uploadComplete : function (file) {
            this.setButtonDisabled(false);
        },

        debug : function (message) {
            if (console) {
                console.log(message);
            }
        },

        destructor: function () {
            this.get('AssetList').destroy();
        }
    });

    Y.Uploader = Uploader;
}, '3.1.0', {
    requires : ['widget', 'assetlist', 'datasource']
});
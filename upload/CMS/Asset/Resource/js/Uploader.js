CMS.Use(['/resources/vendor/js/swfupload.js', 'Asset/CMS.Asset'], function (CMS) {

    CMS.Uploader = Class.extend({

        postback: '/direct/asset/manager/upload',

        swfUrl: '/resources/vendor/swf/swfupload.swf',
        swfSettings: null,

        domElement: null,
        swfUploader: null,

        assetList: null,

        init: function (data) {
            $.extend(this, data);
            this.swfSettings = {
                upload_url : this.postback,
                flash_url : this.swfUrl,
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
                button_placeholder : data.domElement[0],
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
            };
            this.swfUploader = new SWFUpload(this.swfSettings);
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
            $('#UploadMessage').html('<div class="notification">Uploading asset... (0%)</div>');
            return true;
        },

        uploadProgress : function (file, bytesComplete, bytesTotal) {
            var percentComplete = Math.round((bytesComplete/bytesTotal)*100);
            $('#UploadMessage').html('<div class="notification">Uploading asset... ('+ percentComplete +'%)</div>');
        },

        uploadError : function (file, errorCode, message) {
            switch(message) {
                case '409' :
                    $('#UploadMessage').html('<div class="notification negative">Asset already exists in library.</div>');
                    break;
                case '415' :
                    $('#UploadMessage').html('<div class="notification negative">Asset type not allowed.</div>');
                    break;
                default :
                    if (message) {
                        $('#UploadMessage').html('<div class="notification negative">' + message + '</div>');
                    } else {
                        $('#UploadMessage').html('<div class="notification negative">An unkown error occurred.</div>');
                    }
            }
        },

        uploadSuccess : function (file, data, response) {
            data = JSON.parse(data);
            if ('undefined' !== typeof data.code && data.code.id <= 0) {
                $('#UploadMessage').html('<div class="notification positive">Asset uploaded successfully.</div>');
                if (null !== this.assetlist) {
                    console.log(data);
                    this.customSettings['uploader'].assetList.addAsset(new CMS.Asset(data.assets[0]));
                }
            } else {
                this.customSettings['uploader'].uploadError(file, data, data);
            }
        },

        uploadComplete : function (file) {
            this.setButtonDisabled(false);
        },

        debug : function (message) {
            CMS.log(message, true);
        }

    });

});
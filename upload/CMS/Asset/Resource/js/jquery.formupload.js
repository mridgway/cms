(function($) {
    $.formUpload = {
        defaults: {
            classes : {
                field : "asset-formupload-field"
            },
            config : {
                upload_url      : "/direct/asset/manager/upload",
                display_url     : "/assets/${group}/${(function (sysname) {return sysname.substr(0,2);})()}/${sysname}/small.${extension}",
                flash_url       : "/resources/vendor/swf/swfupload.swf",
                file_size_limit : "4 MB",
                file_queue_limit : 1,
                button_image_url: "/resources/core/img/upload.gif",
                button_width    : "102",
                button_height   : "25",
                file_post_name  : "file"
            },
            group: 'tmp'
        }

    };

    $.fn.extend({
        formUpload: function(settings) {
            settings = $.extend({}, $.formUpload.defaults, settings);

            var count = 1;

            return this.each(function() {
                var current = $(this);
                var swfu;
                var localConfig = {};

                if (typeof current.attr("id") == "undefined") {
                    current.attr("id", "asset-formupload-field-" + count);
                }

                if (current.val()) {
                    var value = current.val();
                    $('#' + current.attr('id') + '-image').remove();

                    current.after(
                        $('<img src="' + value + '" />')
                        .attr('id', current.attr('id') + '-image')
                    ).after(
                            $('<a href="#">Remove Image</a>').click(function () {
                                $('#' + current.attr('id') + '-image').remove();
                                $('#' + current.attr('id')).val('');
                                $(this).remove();

                                return false;
                            }).attr('id', current.attr('id') + '-link')
                        );
                }

                localConfig.button_placeholder_id = current.attr("id");
                swfu = new SWFUpload($.extend(settings.config, localConfig, {
                    swfupload_loaded_handler: function () {
                        $('input[name=' + current.attr('name') + ']').remove();
                        $('#' + swfu.movieName).after(
                            $('<input type="hidden" />')
                            .attr('id', swfu.settings.button_placeholder_id)
                            .attr('name', current.attr('name'))
                            .val( current.val() )
                        );
                    },
                    file_dialog_start: function () { }, // does not trigger in FF3.5 or Safari 4 (IE not tested)
                    file_queued_handler: function (oFile) {
                        //swfu.setButtonDisabled(true);
                        swfu.startUpload();
                        $('#' + swfu.movieName).after(
                            $('<p>Uploading Image...</p>')
                            .attr('id', swfu.movieName + '-message')
                        );
                    },
                    file_queue_error_handler: function (oFile, errorCode, message) {
                        alert(message);
                    },
                    file_dialog_complete_handler: function (numberOfSelectedFiles, numberOfFilesQueued, totalNumberOfFilesInQueue) { },
                    upload_start_handler: function (oFile) {
                        return true;
                    },
                    upload_progress_handler: function (oFile, bytesCompleted, totalBytes) { },
                    upload_success_handler: function (oFile, serverData, receivedResponse) {
                        serverData = eval('(' + serverData + ')');
                        $('#' + current.attr('id') + '-link').remove();
                        $('#' + current.attr('id') + '-image').remove();
                        $('#' + swfu.movieName).after(
                            $('<img src="' + serverData.data.assets[0].thumb + '?' + Math.random() + '"/>')
                            .attr('id', current.attr('id') + '-image')
                        ).after(
                            $('<a href="#">Remove Image</a>').click(function () {
                                $('#' + current.attr('id') + '-image').remove();
                                $('#' + swfu.settings.button_placeholder_id).val('');
                                $(this).remove();

                                return false;
                            }).attr('id', current.attr('id') + '-link')
                        );

                        $('#' + swfu.settings.button_placeholder_id).val(serverData.data.assets[0].thumb + '?' + Math.random());
                    },
                    upload_error_handler: function (oFile, errorCode, message) {
                        alert('errorCode ' + errorCode);
                        alert('message ' + message);
                    },
                    upload_complete_handler: function (oFile) {
                        $('#' + swfu.movieName + '-message').remove();
                        //swfu.setButtonDisabled(false);
                    }
                }));

                count++;
            });
        }
    });

})(jQuery);
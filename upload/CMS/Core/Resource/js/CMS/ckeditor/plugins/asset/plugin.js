; CKEDITOR.plugins.add( 'asset',
{
    init : function( editor )
    {
        var config = editor.config;

        var assetCommand = function () {};
        assetCommand.prototype = {
            exec: function (editor) {
                if($('#assetModal').size() < 1) {
                    $('body').append('<div id="assetModal" />');
                }
                
                $("#assetModal").jqm({
                        ajax: "/asset/index/index/format/html",
                        overlayClass: "modalOverlay",
                        modal: true,
                        onLoad: function(modal) {
                            $(modal.t).trigger('modal.onload', [modal]);

                            modal.w.css({
                                top: $('body').scrollTop()+20
                            });

                            var width = modal.w.context.offsetWidth;
                            modal.w.css({
                                'left' : '50%',
                                'opacity' : 1,
                                // this next line exists because IE6 is still terrible
                                'marginLeft' : modal.w.context.offsetWidth / -2
                            })
                            .hide()
                            .fadeIn("fast");

                            $('.assetmanager a.close').click(function() {
                                modal.w.jqmHide();
                                return false;
                            });

        					modal.w.bind('close.jqm', function(event) {
        						modal.w.jqmHide();
        					});

        					modal.w.bind('insert.wym', function(event, data) {
                                switch(data.type) {
                                    case 'image':
                                        editor.insertHtml('<img src="/asset/'+data.sysname+'/'+data.size+'" alt="'+data.caption+'" />');
                                        break;
                                    case 'extimage':
                                        editor.insertHtml('<img src="'+data.url+'" alt="'+data.caption+'" />');
                                        break;
                                    case 'media':
                                    case 'extmedia':
                                        break;
                                    case 'file':
                                        default:
                                        var src = "/asset/"+data.sysname;
                                        src = (data.ext=="pdf")?(src+'/pdf'):(src);
                                        editor.insertHtml('<a href="'+src+'" title="'+data.caption+'">'+data.name+'</a>');
                                }

                                if(data.type == 'media') {
                                    data.url = '/asset/'+data.sysname;
                                }

                                if(data.type.match('media')) {
                                    var params = {
                                        'movie' : data.url,
                                        'bgcolor' : data.bgcolor,
                                        'scale' : data.scale,
                                        'wmode' : data.wmode,
                                        'flashvars' : data.flashvars
                                    };

                                    // create object
                                    var object = $('<object />').attr({
                                        'width' : data.width,
                                        'height' : data.height
                                    });

                                    // append parameters
                                    for(x in params) {
                                        object.append(
                                            $('<param />').attr({
                                                'name' : x,
                                                'value' : params[x]
                                            })  
                                        );
                                    }

                                    // append embed tag
                                    object.append(
                                        $('<embed />').attr({
                                            'width' : data.width,
                                            'height' : data.height,
                                            'src' : data.url,
                                            'type' : 'application/x-shockwave-flash'
                                        })
                                    );

                                    var html = $('<div />').append(object).html()

                                    editor.insertHtml(html);
                                }

        					    modal.w.jqmHide();
        					    return false;
        					});
                        },
                        onShow: function(modal) {
                            // opacity isn't 0 because IE6 is terrible
                            modal.w.css("opacity", 0.01).show();
                        },
                        onHide: function(modal) {
        	      			modal.w.unbind('insert.wym');
                            modal.w.fadeOut("fast", function() {
                                $('.modo-assetuploader', modal.w).assetuploader('destroy');
                            });

                            modal.o.fadeOut("fast", function(){
                                $(this).remove();
                        });
                    }
                });

                $('#assetModal').jqmShow();
            }
        };

        var command = new assetCommand();
        editor.addCommand( 'asset', command );

        editor.ui.addButton('Asset', {
            label: 'Asset Manager',
            title: 'Asset Manager',
            className: 'modo_asset',
            command: 'asset'
        });		
    }
});
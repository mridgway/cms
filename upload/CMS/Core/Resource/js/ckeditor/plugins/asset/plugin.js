CKEDITOR.plugins.add( 'asset',
{
    assetManager: null,

    init : function( editor )
    {
        var self = this;
        var config = editor.config;

        var assetCommand = function () {};
        assetCommand.prototype = {
            exec: function (editor) {

                var insertFunction = function (asset, size) {
                    var data = asset;
                    switch(data.type) {
                        case 'image':
                            editor.insertHtml('<img src="'+ data.getUrl(size) +'" alt="'+(data.caption ? data.caption : '')+'" />');
                            break;
                        case 'extimage':
                            editor.insertHtml('<img src="'+data.getUrl(size)+'" alt="'+(data.caption ? data.caption : '')+'" />');
                            break;
                        case 'media':
                        case 'extmedia':
                            break;
                        case 'file':
                        default:
                            editor.insertHtml('<a href="'+data.getUrl(size)+'" title="'+(data.caption ? data.caption : '')+'">'+data.name+'</a>');
                    }

                    if(data.type.match('media')) {
                        var params = {
                            'movie' : data.getUrl(size),
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
                                'src' : data.getUrl(size),
                                'type' : 'application/x-shockwave-flash'
                            })
                        );

                        var html = $('<div />').append(object).html()

                        editor.insertHtml(html);
                    }

                    self.assetManager.close();
                    return false;
                }
                
                if (!self.assetManager) {
                    self.assetManager = new CMS.AssetManager({
                        onInsert: insertFunction
                    });
                } else {
                    self.assetManager.setInsertFunction(insertFunction)
                }
                self.assetManager.open();
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
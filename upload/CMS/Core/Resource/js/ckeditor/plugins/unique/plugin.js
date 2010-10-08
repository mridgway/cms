; CKEDITOR.plugins.add( 'unique',
{
    init : function( editor )
    {
	    function S4() {
           return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
        }
        function guid() {
           return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
        }

        var guid = guid();
        CKEDITOR.instances[guid] = CKEDITOR.instances[editor.name];
        delete(CKEDITOR.instances[editor.name]);
        editor.name = guid;
    }
});
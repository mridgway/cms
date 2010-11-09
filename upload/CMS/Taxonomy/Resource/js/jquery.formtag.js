/*
    Tagger Widget v1.0
    Copyright (C) 2008 Chris Iufer (chris@iufer.com

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
*/

(function($){

    $.fn.formTag = function(){
        $(this).wrap($('<div>', {
            class: 'taxonomy-formtag-container'
        }));
        $(this).data('name', $(this).attr('name'));
        $(this).removeAttr('name');
        // add a hidden field with empty value to allow submitting with now tags
        $(this).before($('<input type="hidden" value="" />').attr('name',$(this).data('name').replace(/([^a-zA-Z0-9\s\-\_])|^\s|\s$/g, '')));
        var b = $('<button type="button">Add</button>').addClass('taxonomy-formtag-button')
            .click(function(){
                var tagger = $(this).data('tagger');
                $(tagger).addTag( $(tagger).val() );
                $(tagger).val('');
                $(tagger).stop();
            })
            .data('tagger', this);
        var l = $('<ul />').addClass('taxonomy-formtag-list');
        $(this).data('list', l);
        $(this).after(l).after(b);
        $(this).bind('keypress', function(e){
            if( 13 == e.keyCode){
                //console.log(e.keyCode);
                $(this).addTag( $(this).val() );
                $(this).val('');
                $(this).stop();
                return false;
            }
        });

        return $(this);
    };

	$.fn.addTag = function(v){
		var r = v.split(',');
		for(var i in r){
			var n = r[i].replace(/([^a-zA-Z0-9\s\-\_])|^\s|\s$/g, '');
			if(n == '') return false;
			var l = $(this).data('list');
            var found = false;
            $('input', l).each(function (index, value) {
                if ($(value).val() == n) {
                    found = true;
                    return false;
                }
            });
            if (found) return false;
			var fn = $(this).data('name');
			var i = $('<input type="hidden" />').attr('name',fn).val(n);
			var t = $('<li />', {
                html : $('<span>', {
                    html : $('<a>').text('remove').click(function(e){e.preventDefault();})
                }).append(n)
            })
				.click(function(){
					// remove
					var hidden = $(this).data('hidden');
					$(hidden).remove();
					$(this).hide(200, function () {
                        $(this).remove();
                    });
				})
				.data('hidden',i);
			$(l).append(t).append(i);
		}

        return $(this);
	};

})(jQuery);

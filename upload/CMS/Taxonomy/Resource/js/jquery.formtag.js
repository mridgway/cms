;(function($) {
    
    var current;
    
    $.formtag = {
        defaults: {
            classes: {
                field       : "taxonomy-formtag-field",
                list        : "taxonomy-formtag-list",
                button      : "taxonomy-formtag-button",
                container   : "taxonomy-formtag-container"
            },
            slideDuration   : "fast",
            enableKeySubmit : true
        }
    };
    
    $.fn.extend({
        formtag: function(settings) {
            settings = $.extend({}, $.formtag.defaults, settings);
            
            return this.each(function() {
                init(this, settings);
            });
        },
        formtagClear: function()
        {
            current = $(this);
            current.val("").formtagSetTags(new Array());
            
            return current;
        },
        formtagAdd: function(name) {
            current = $(this);
            name = $.trim(name);
            
            var tags     = current.formtagGetTags();
            var settings = current.formtagGetSettings();
            var tagName  = name.toLowerCase();
            
            if ($.inArray(tagName, tags) != -1) {
                return false;
            }
            
            var tagItem = getNewTagListItem(current);
            var tagList = $("." + settings.classes.list, current.parent());
            
            $("a", tagItem).after(name);
            
            tagList.append(tagItem);
            
            tags.push(tagName);
            
            // if there were no tags before
            if (tags.length == 1) {
                tagList.slideDown(settings.slideDuration);
            }
            
            return true;
        },
        formtagRemove: function(name, button) {
            current = $(this);
            name = name.toLowerCase();
            
            var tags     = current.formtagGetTags();
            var settings = current.formtagGetSettings();
            
            if (tags.length == 1) {
                $("." + settings.classes.list, current.parent())
                    .slideUp(settings.slideDuration);
            }
            
            for(var i in tags) {
            	if (name == tags[i]) {
                	tags.splice(i, 1);
                    break;
                }
            }
            
            button.remove();
            
            return current;
        },
        formtagGetButton: function() {
            current = $(this);
            
            var settings = current.formtagGetSettings();
            
            return $("." + settings.classes.button, current.parent());
        },
        formtagGetTags: function() {
            current = $(this);
            
            return current.data("formtag.tags");
        },
        formtagSetTags: function(tags) {
            current = $(this);
            
            current.data("formtag.tags", tags);
            
            return current;
        },
        formtagGetSettings: function() {
            current = $(this);
            
            return current.data("formtag.settings");
        },
        formtagSetSettings: function(settings) {
            current = $(this);
            
            current.data("formtag.settings", settings);
            
            return current;
        },
        formtagHasTags: function() {
            current = $(this);
            
            if (current.formtagGetTags().length == 0) {
                return false;
            }
            
            return true;
        }
    });
    
    /**
     * Turn a specified input field into an open tag field
     * 
     * @param object input    The input field to initialize
     * @param object settings Configuration settings
     */
    function init(input, settings)
    {
        current = $(input);
        
        current.parents("form:first").submit(function(){
            var tags = current.formtagGetTags();
            current.val(tags.join(","));
        });
        
        var originalValue = current.val();
        
        current.hide()
               .formtagClear()
               .formtagSetSettings(settings)
               .formtagSetTags(new Array());
               
        var tagInputField = getNewTagInputField(current);
        var tagList       = getNewTagList(current);
        var tagButton     = getNewTagFieldButton(current);
        var tagContainer  = $("<div />").addClass(settings.classes.container)
                                        .append(tagInputField)
                                        .append(tagButton)
                                        .append(tagList);
            
        current.after(tagContainer);
        tagContainer.append(current);
        
        if (originalValue != "") {
            var tags = originalValue.split(",");
            
            for (var x in tags) {
                current.formtagAdd(tags[x]);
            }
        }
        
        if (current.formtagHasTags()) {
            tagList.slideDown(settings.slideDuration);
        }
    }
    
    function getNewTagInputField(helper)
    {
        var settings = helper.formtagGetSettings();
        
        var field = helper.clone()
                          .removeAttr("id")
                          .removeAttr("class")
                          .removeAttr("name")
                          .removeAttr("value")
                          .addClass(settings.classes.field)
                          .show()
                          .bind('result.formtag', function () {
                              helper.formtagGetButton().click();
                          })
                          .keydown(function(e) {
                              if (e.which == 13) {
                                  if (settings.enableKeySubmit) {
                                      alert("ENTER KEYDOWN");
                                      $(this).trigger('result.formtag');
                                  }
                                  
                                  return false;
                              }
                          });
        
        return field;
    }
    
    function getNewTagList(helper)
    {
        var settings = helper.formtagGetSettings();
        
        return $("<ul />").addClass(settings.classes.list).hide();
    }
    
    function getNewTagListItem(helper)
    {
        var tagItem = $("<li />").html("<span><a href=\"#\">remove</a></span>");
        $("a", tagItem).click(function(){
            var html = tagItem.clone();
            $("a", html).remove();
            helper.formtagRemove(html.text(), tagItem);
            return false;
        });
        return tagItem;
    }
    
    function getNewTagFieldButton(helper)
    {
        var settings = helper.formtagGetSettings();
        
        var button = $("<button />").addClass(settings.classes.button).html("Add");
            button.click(function(){
                var field = $("." + settings.classes.field, helper.parent());
                
                var tag = $.trim(field.val());
                if (tag != "") {
                    if (tag.indexOf(",") >= 0)
                    {
                        alert("Tags cannot contain the character ','");
                        return false;
                    }
                    
                    helper.formtagAdd(tag);
                    field.val("");
                }
                
                return false;
            });
        
        return button;
    }
    
})(jQuery);
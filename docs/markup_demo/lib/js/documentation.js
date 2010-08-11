$( document ).ready( function() {
    $('h4').each(function () {
        $('#table_of_contents ul').append(
            $("<li />").append(
                $('<a />').attr('href', '#' + $(this).attr('id')).html($(this).html())
            )
        );
    });
    
    $('.section').each(function () {
        $(this).append(
            $('<a />').attr('href', '#' ).addClass("toplink").html("Back to top")
        );
    });
    
    $('.documentation .section').each(function () {
        $(this).append(
            $('<a />').attr('href', '#').addClass("example_link").html("Show Example")
        );
    });
    
    $("a.example_link").toggle(
        function () {
            $(this).html("Hide Example").parents(".section").find(".demo").fadeIn("fast");
        },
        function () {
            $(this).html("Show Example").parents(".section").find(".demo").fadeOut("fast");
        }
    );
      
    $('.documentation .no-example .example_link').hide();
    
    $('.demo .element').each(function () {
        $(this).wrap(
            $('<div />').addClass("demo_box")
        );
    });
    
    
    

    $("a.one-column-btn").click(function() {
        $('#wrapper').addClass("one-column").removeClass("two-column two-column-alt three-column");
        return false;
    });
    
    $("a.two-column-btn").click(function() {
        $('#wrapper').addClass("two-column").removeClass("one-column two-column-alt three-column");
        return false;
    });
    
    $("a.two-column-alt-btn").click(function() {
        $('#wrapper').addClass("two-column-alt").removeClass("one-column two-column three-column");
        return false;
    });
    
    $("a.three-column-btn").click(function() {
        $('#wrapper').addClass("three-column").removeClass("two-column two-column-alt one-column");
        return false;
    });
    
    
    
    $("#growl").hide();        
    $(".growl").click(function() {
        $("#growl").fadeIn("fast");
        return false;
    });
    
    
    
$(".slide").click(function(){
    $(this).animate({
        left: "-36px"
    }, 100 );
    return false;
});    
    
    
});
jQuery(document).ready(function() {
    if (jQuery('#event_search_form').length > 0) search_toggle();
    if (jQuery('.toggler').length > 0) mobile_toggle();
    if (jQuery('.calendar.list').length > 0) infinite_setup();
});

function mobile_toggle() {
    jQuery('.choose').click(function(e) {
        jQuery(this).parent().toggleClass('hover');
        e.preventDefault()
    });
}

function search_toggle() {
    jQuery('#mobile_search').click(function(e) {
        if (jQuery('#event_search_form input[type="text"]').val() !== 'Search calendar') {
            jQuery('#event_search_form').submit();
        } else {
            jQuery('#event_search_form').toggleClass('open');
        }
        e.preventDefault();
    });
    jQuery('#event_search_form #s').focus(function() {
        jQuery(this).addClass('focus');
    });
    jQuery('#event_search_form #s').blur(function() {
        jQuery(this).removeClass('focus');
    });
}

function infinite_setup() {
    jQuery('#nav-below .nav-next a').click(function(e) {
        jQuery.get( jQuery(this).attr('href'), function( data ) {
            var d = jQuery('#elist',data).html();
            if (jQuery('#nav-below .nav-next a',data).length > 0) {
                var a = jQuery('#nav-below .nav-next a',data).attr('href')
                jQuery('#nav-below .nav-next a').attr('href',a);
            } else {
                jQuery('#nav-below .nav-next a').remove();
            }
            jQuery('#elist').append(d);
        });
        e.preventDefault();
    });
}
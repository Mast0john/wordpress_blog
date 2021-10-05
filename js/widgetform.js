/**
 * WidgetForm.js 1.0.0
 * @author Template
 * GPL Licensed
 */
function select_widget(obj) {

    (function () {

        var parent = jQuery(obj).parent();
        var name = parent.attr('class');
        jQuery('.' + name).attr('checked', 'checked');

    })();
}

function unselect_widget(obj) {

    (function () {

        var parent = jQuery(obj).parent();
        var name = parent.attr('class');
        jQuery('.' + name).removeAttr('checked');

    })();
}
        
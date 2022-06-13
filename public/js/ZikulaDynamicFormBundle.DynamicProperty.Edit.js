// Copyright Zikula, licensed MIT.
(function($) {
    $(document).ready(function() {
        let changeHandler = function(event) {
            let form;
            let data;

            form = $(this).closest('form');
            let formName = form.attr('name');
            data = $(form).serializeArray();
            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: data,
                complete: function(jqXHR, status) {
                    let html = jqXHR.responseText;
                    let newForm = $(html).filter('form').filter('form[name="'+formName+'"]');
                    form.replaceWith(newForm);
                },
            })
        }
        let addToCollectionHandler = function () {
            let list = $($(this).attr('data-list-selector'));
            // Try to find the counter of the list or use the length of the list
            let counter = list.data('widget-counter') || list.children().length;

            // grab the prototype template
            let newWidget = list.attr('data-prototype');
            // replace the "__name__" used in the id and name of the prototype
            // with a number that's unique to your emails
            // end name attribute looks like name="contact[emails][2]"
            newWidget = newWidget.replace(/__name__/g, counter);
            // Increase the counter
            counter++;
            // And store it, the length cannot be used if deleting widgets is allowed
            list.data('widget-counter', counter);

            // create a new list element and add it to the list
            let newElem = $(list.attr('data-widget-tags')).html(newWidget);
            newElem.appendTo(list);

            // add change handler to selector
            $(newElem).find('.dynamic-property-form-type-select').change(changeHandler);
            // add remove handler
            $(newElem).find('.remove-from-collection').click(removeFromCollection);
        }
        let removeFromCollection = function () {
            $(this).closest('li').remove();
        }

        $('.dynamic-property-form-type-select').change(changeHandler);
        $('.add-another-collection-widget').click(addToCollectionHandler);
        $('.remove-from-collection').click(removeFromCollection);
    });
})(jQuery);

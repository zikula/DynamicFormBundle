// Copyright Zikula, licensed MIT.
(function($) {
    $(document).ready(function() {
        let changeHandler = function(event) {
            let formTypeField = $(event.target);
            let form;
            let data;

            let formOptions = $(formTypeField).closest("[id$='_formOptions']");
            formOptions.html('<i class="fa-solid fa-gear fa-3x fa-fw fa-spin" aria-hidden="true"></i>');
            form = $(this).closest('form');
            let formName = form.attr('name');
            // console.log("formName:", formName);
            data = $(form).serializeArray();
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: data,
                success: function(html) {
                    let newContents = $(html).find('form[name="'+formName+'"]');
                    console.log('o_form:',form, 'n_form:',newContents);
                    form.replaceWith(
                        newContents
                    );
                }
            });
        }
        let formTypeSelectEl = $('.dynamic-property-form-type-select');
        formTypeSelectEl.change(changeHandler);

        // add-collection-widget.js
        $('.add-another-collection-widget').click(function (e) {
            var list = $($(this).attr('data-list-selector'));
            // Try to find the counter of the list or use the length of the list
            var counter = list.data('widget-counter') || list.children().length;

            // grab the prototype template
            var newWidget = list.attr('data-prototype');
            // replace the "__name__" used in the id and name of the prototype
            // with a number that's unique to your emails
            // end name attribute looks like name="contact[emails][2]"
            newWidget = newWidget.replace(/__name__/g, counter);
            // Increase the counter
            counter++;
            // And store it, the length cannot be used if deleting widgets is allowed
            list.data('widget-counter', counter);

            // create a new list element and add it to the list
            var newElem = $(list.attr('data-widget-tags')).html(newWidget);
            newElem.appendTo(list);


            // add change handler to selector
            $(newElem).find('.dynamic-property-form-type-select').change(changeHandler);
        });
    });
})(jQuery);

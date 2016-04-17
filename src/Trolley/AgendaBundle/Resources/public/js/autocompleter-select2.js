/* Useage:
    $('.js_selectuser').autocompleter({
            url_list: '{{ path('trolley_agenda_autocomplete_searchusername') }}',
            placeholder: '{{ 'label.preacher'|trans }}'
    });
 */
(function ($) {
    'use strict';
    $.fn.autocompleter = function (options) {
        var settings = {
            url_list: '',
            placeholder: '',
            select2options: {
                minimumInputLength: 2,
                theme: 'bootstrap'
            }
        };
        return this.each(function () {
            if (options) {
                $.extend(settings, options);
            }
            var $this = $(this),
                select2options = {
                    ajax: {
                        url: settings.url_list,
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term
                            };
                        },
                        processResults: function (data, params) {
                            var results = [];
                            $.each(data, function (index, item) {
                                results.push({
                                    id: item.id,
                                    text: item.text
                                });
                            });
                            return {
                                results: results
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) {
                        return markup;
                    }
                };

            if (settings.select2options) {
                $.extend(select2options, settings.select2options);
            }
            if (settings.placeholder) {
                select2options.placeholder = settings.placeholder;
            }

            $this.select2(select2options);
        });
    };
})(jQuery);

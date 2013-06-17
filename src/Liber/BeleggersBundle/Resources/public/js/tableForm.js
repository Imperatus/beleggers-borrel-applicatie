/**
 * Created with JetBrains PhpStorm.
 * User: ijay
 * Date: 6/1/13
 * Time: 1:47 PM
 */
var Liber =  Liber || {};

Liber.TableForm = function() {
    "use strict";
    var collectionHolder, addLink, removeLink, removeExisting, addForm, getIntensityLabel, deleteCount = 0;

    this.init = function() {
        collectionHolder = $('tbody.stockCollection');

        // TODO - Change to bootstrap + / -
        addLink = $('.add_stock_link');
        removeLink = $('<a href="#" class="remove_stock_link" data-toggle="tooltip" title="'+ Translator.get('form.control.general.remove')+ ' ' + Translator.get('form.control.stockType.type') + ' ' + Translator.get('form.control.general.addition') + '"><span class="icon-remove"></spam></a>');
        removeExisting = $('.stock_remove_existing');

        removeExisting.each(function() {
            $(this).on('click', function(e) {
                e.preventDefault();
                var message = confirm('Are you sure?');
                if(message) {
                    var thisTr = $(this).closest('tr');
                    thisTr.hide();
                    thisTr.find('input').val(null);
                    thisTr.find('input').attr('disabled', 'disabled');
                    thisTr.find('select').attr('disabled', 'disabled');
                    thisTr.find('select').val(null);

                    deleteCount++;
                    if(deleteCount > 0) {
                        if($('.alert-error').length <= 0) {
                            $('ul.nav-tabs').after('<div class="alert alert-error">' +
                                Translator.get('form.control.general.removePending') + ': ' + deleteCount +
                            '</div>');
                        } else {
                            $('.alert-error').html(Translator.get('form.control.general.removePending') + ': ' + deleteCount);
                        }
                    }

                }
            });
        });

        $('.remove_entire_stock').on('click', function(e) {
            e.preventDefault();
            var message = confirm(Translator.get('form.confirm.stock.remove'));
            if(message) {
                // add a new tag form (see next code block)
                $('tbody').remove();
                $("form").submit();
            }
        });

        collectionHolder.data('index', collectionHolder.find(':input').length);

        addLink.on('click', function(e) {
            e.preventDefault();

            addForm(collectionHolder);

        });

        // Init sliders if present
        $('.formMinutesSlider').each(function() {
            var inputField = $(this).parent().parent().find('input'),
                resultContainer =  $(this).parent().find('.sliderValueContainer'),
                initial = inputField.val();

            if(initial.length <= 0) {
                initial = 10;
            }

            resultContainer.text(Math.floor(initial/60)+'h '+initial%60+'m');

            $(this).slider({
                min: 10,
                max: 180,
                step: 10,
                value: initial,
                slide: function(e, ui) {
                    resultContainer.text(Math.floor(ui.value/60)+'h '+ui.value%60+'m');
                    inputField.val(ui.value);
                }
            });
        });

        $('.formMultiplierSlider').each(function() {
            var inputField = $(this).parent().parent().find('input'),
                resultContainer =  $(this).parent().find('.sliderValueContainer'),
                initial = inputField.val().toString().replace(',','.'),
                resultHtml;

            if(initial.length <= 0) {
                initial = 0.1;
            }

            resultHtml = getIntensityLabel(initial);


            resultContainer.html(resultHtml);

            $(this).slider({
                min: 0.1,
                max:1,
                step: 0.1,
                value: initial,
                slide: function(e, ui) {
                    resultHtml = getIntensityLabel(ui.value);
                    resultContainer.html(resultHtml);
                    inputField.val(ui.value);
                }
            });
        });
    };

    getIntensityLabel = function(value) {
        var result;

        if(value > 0 && value < 0.4) {
            result = '' +
                '<span style="cursor: default;" title="'+ value +'" data-toggle="tooltip" class="label label-warning">' +
                    'Slow' +
                '</span>';
        } else if (value >= 0.4 && value < 0.7) {
            result = '' +
                '<span style="cursor: default;" title="'+ value +'" data-toggle="tooltip" class="label label-success">' +
                    'Medium' +
                '</span>';
        } else {
            result = '' +
                '<span style="cursor: default;" title="'+ value +'" data-toggle="tooltip" class="label label-important">' +
                    'Fast' +
                '</span>';
        }

        return result;
    };

    addForm = function(collectionHolder) {
        var newRemLink = removeLink.clone();

        // Get the data-prototype explained earlier
        var prototype = collectionHolder.data('prototype');

        // get the new index
        var index = collectionHolder.data('index');

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__name__/g, index);

        // increase the index with one for the next item
        collectionHolder.data('index', index + 1);

        // Display the form in the page in an tr, in the table container
        var newFormTr = $('<tr></tr>').append(newForm);
        $('.stockCollection').append(newFormTr);

        // SLIDERS
        // Init sliders if present
        var newMinuteSlider = newFormTr.find('.formMinutesSlider');

        if(newMinuteSlider.length > 0) {

            var minuteInputField = newMinuteSlider.parent().parent().find('input'),
                minuteResultContainer =  newMinuteSlider.parent().find('.sliderValueContainer'),
                minuteInitial = minuteInputField.val();

            if(minuteInitial.length <= 0) {
                minuteInitial = 10;
            }

            minuteResultContainer.text(Math.floor(minuteInitial/60)+'h '+minuteInitial%60+'m');
            minuteInputField.val(minuteInitial);

            newMinuteSlider.slider({
                min: 10,
                max: 180,
                step: 10,
                value: minuteInitial,
                slide: function(e, ui) {
                    minuteResultContainer.text(Math.floor(ui.value/60)+'h '+ui.value%60+'m');
                    minuteInputField.val(ui.value);
                }
            });
        }

        var newSlider = newFormTr.find('.formMultiplierSlider');

        if(newSlider.length > 0) {

            var inputField = newSlider.parent().parent().find('input'),
                resultContainer =  newSlider.parent().find('.sliderValueContainer'),
                initial = inputField.val();
            var resultHtml;

            if(initial.length <= 0) {
                initial = 0.1;
            }

            inputField.val(initial);

            resultHtml = getIntensityLabel(initial);

            resultContainer.html(resultHtml);

            newSlider.slider({
                min: 0.1,
                max: 1,
                step: 0.1,
                value: initial,
                slide: function(e, ui) {
                    resultHtml = getIntensityLabel(ui.value);
                    resultContainer.html(resultHtml);
                    inputField.val(ui.value);
                }
            });
        }

        // If it is a new field, preset current price and stock to starting price and stock
        var startingPrice = newFormTr.find('.startingPrice');
        var currentPrice = newFormTr.find('.currentPrice');
        var startingStock = newFormTr.find('.startingStock');
        var currentStock = newFormTr.find('.currentStock');

        startingPrice.on('keyup', function(e, value) {
            currentPrice.val($(this).val());
        });

        startingStock.on('keyup', function(e, value) {
            currentStock.val($(this).val());
        });


        // Add the remove link to remove mistakes
        var remLink = $('<td class="form_control"></td>').append(newRemLink);
        newFormTr.append(remLink);

        newRemLink.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // add a new tag form (see next code block)
            $(this).closest('tr').remove();
        });

    };
};
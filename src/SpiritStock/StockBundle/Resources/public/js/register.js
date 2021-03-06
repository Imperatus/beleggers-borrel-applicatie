/**
 * Created with JetBrains PhpStorm.
 * User: ijay
 * Date: 5/29/13
 * Time: 10:58 PM
 * To change this template use File | Settings | File Templates.
 */
var SpiritStock =  SpiritStock || {};

SpiritStock.Register = function() {
    "use strict";
    var stockItems, addToOrderList, findItemRow, addItemRow, updateItemRow, calculateTotal, container, formContainer, submitButton, clearButton;

    /**
     * Initialization
     */
    this.init = function() {
        var addLink;

        stockItems = $('tr');
        container = $('#orderContainer');
        formContainer = $('#formContainer');
        submitButton = $('#submitButton');
        clearButton = $('#clearOrderButton');

        stockItems.each(function() {
            var data = [];
            data.push($(this).find('.itemName').text());
            data.push($(this).find('.itemPrice').attr('data-price'));

            addLink = $(this).find('a');
            addLink.on('click',function(e) {
                e.preventDefault();
                
                addToOrderList($(this).attr('data-stocktype'), $(this).attr('data-item'), data);
            })
        });

        clearButton.on('click', function(e) {
            e.preventDefault();

            if(confirm(Translator.trans('form.confirm.cashier.order'))) {

                formContainer.empty();
                container.empty();
                calculateTotal();
            }
        })
    };

    /**
     * Adds order to the list of ordered items
     *
     * @param stockType
     * @param item
     * @param data
     */
    addToOrderList = function(stockType, item, data) {
        if(findItemRow(stockType, item)) {
            updateItemRow(stockType, item);
        } else {
            addItemRow(stockType, item, data);
            if(submitButton.hasClass('disabled') || clearButton.hasClass('disabled')) {
                submitButton.removeClass('disabled');
                clearButton.removeClass('disabled')
            }
        }

        calculateTotal();
    };

    /**
     * Is the item already added to the overview?
     *
     * @param stockType
     * @param item
     * @returns {boolean}
     */
    findItemRow = function(stockType, item) {
        var row = $('#list-'+stockType+'-'+item);

        if (row.length <= 0) {
            return false;
        }
        return true;
    };

    /**
     * Add item to the overview
     *
     * @param stockType
     * @param item
     * @param data
     */
    addItemRow = function(stockType, item, data) {
        var row = $('' +
                '<tr id="list-'+stockType+'-'+item+'">' +
                    '<td>'+ data[0] +'</td>' +
                    '<td class="itemPrice" data-price="'+ data[1] +'">'+ data[1] +'</td>' +
                    '<td class="orderAmount" data-amount="1">x 1</td>' +
                    '<td style="line-height: 23px; text-align: center">' +
                        '<a href="#"><span class="icon-minus-sign"></span></a>' +
                    '</td>' +
                '</tr>'),
            formRow = $('<input name="'+item+'" id="item-'+stockType+'-'+item+'" type="text" value="1" />');

        var removeButton = row.find('a');

        // TODO - refactor to use updateItemRow
        removeButton.on('click', function(e) {
            e.preventDefault();
            var amount = row.find('.orderAmount').attr('data-amount'),
                updateFormRow = formContainer.find('#item-'+stockType+'-'+item),
                totalRows;

            amount = parseInt(amount);
            if(amount > 1) {
                amount--;
                row.find('.orderAmount').attr('data-amount', amount);
                row.find('.orderAmount').text('x '+ amount);
                updateFormRow.val(amount);
            } else {
                row.remove();
                updateFormRow.remove();
                totalRows = formContainer.find('tr');

                if(totalRows.length <= 0) {
                    if(!submitButton.hasClass('disabled') || clearButton.hasClass('disabled')) {
                        submitButton.addClass('disabled');
                        clearButton.addClass('disabled');
                    }
                }
            }
            calculateTotal();
        });

        container.append(row);
        formContainer.append(formRow);
    };

    /**
     * Update the row of the item
     *
     * @param stockType
     * @param item
     */
    updateItemRow = function(stockType, item) {
        var cell, amount, newAmount,
            updateRow = $('#list-'+stockType+'-'+item),
            updateFormRow = formContainer.find('#item-'+stockType+'-'+item);

        amount = updateFormRow.val();
        newAmount = parseInt(amount) + 1;

        updateFormRow.val(newAmount);

        cell = updateRow.find('.orderAmount')
        cell.attr('data-amount', newAmount);
        cell.text('x ' + newAmount);
    };

    /**
     * Calculate total price
     */
    calculateTotal = function() {
        var rows = container.find('tr'),
            totalContainer = $('#totalPrice'),
            unitContainer = $('#totalUnits'),
            total = 0.00,
            units = 0,
            unitPrice = parseFloat(unitContainer.attr('data-units')),
            amount,price;

        rows.each(function() {
            price = $(this).find(".itemPrice").attr('data-price');
            amount = $(this).find('.orderAmount').attr('data-amount');

            total = total + (parseFloat(price) * parseInt(amount));
        });

        units = total / unitPrice;
        totalContainer.text(total.toFixed(2));
        unitContainer.text(Math.ceil(units));
    };
};
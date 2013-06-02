/**
 * Created with JetBrains PhpStorm.
 * User: ijay
 * Date: 5/29/13
 * Time: 10:58 PM
 * To change this template use File | Settings | File Templates.
 */
var Liber =  Liber || {};

Liber.Register = function() {
    "use strict";
    var stockItems, addToOrderList, findItemRow, addItemRow, updateItemRow, calculateTotal, container, formContainer, submitButton;

    this.init = function() {
        var addLink;
            stockItems = $('tr');
            container = $('#orderContainer');
            formContainer = $('#formContainer'),
            submitButton = $('#submitButton');

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
    };

    addToOrderList = function(stockType, item, data) {
        if(findItemRow(stockType, item)) {
            updateItemRow(stockType, item);
        } else {
            addItemRow(stockType, item, data);
            if(submitButton.hasClass('disabled')) {
                submitButton.removeClass('disabled');
            }
        }

        calculateTotal();
    };

    findItemRow = function(stockType, item) {
        var row = $('#list-'+stockType+'-'+item);

        if(row.length <= 0) {
            return false;
        }
        return true;
    };

    addItemRow = function(stockType, item, data) {
        var row = $('<tr id="list-'+stockType+'-'+item+'"><td>'+ data[0] +'</td><td class="itemPrice" data-price="'+ data[1] +'">'+ data[1] +'</td><td class="orderAmount" data-amount="1">x 1</td></tr>'),
            formRow = $('<input name="'+item+'" id="item-'+stockType+'-'+item+'" type="text" value="1" />');

        container.append(row);
        formContainer.append(formRow);
    };

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

    calculateTotal = function() {
        var rows = container.find('tr'),
            totalContainer = $('#totalPrice'),
            unitContainer = $('#totalUnits'),
            total = 0.00,
            units = 0,
            unitPrice = parseFloat(unitContainer.attr('data-units')),
            amount,price;
        console.log(rows);

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
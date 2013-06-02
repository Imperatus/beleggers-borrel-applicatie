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
    var stockItems, addToOrderList, findItemRow, addItemRow, updateItemRow, calculateTotal, container;

    this.init = function() {
        var addLink;
        stockItems = $('tr');
        container = $('#orderContainer');

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
        var row = $('<tr id="list-'+stockType+'-'+item+'"><td>'+ data[0] +'</td><td class="itemPrice" data-price="'+ data[1] +'">'+ data[1] +'</td><td class="orderAmount" data-amount="1">x 1</td></tr>');

        container.append(row);
    };

    updateItemRow = function(stockType, item) {
        var cell, amount;
        var updateRow = $('#list-'+stockType+'-'+item);

        cell = updateRow.find('.orderAmount')
        amount = cell.attr('data-amount');

        var newAmount = parseInt(amount)+1;

        cell.attr('data-amount', newAmount);
        cell.text('x ' + newAmount);
    };

    calculateTotal = function() {
        var rows = container.find('tr');
        var totalContainer = $('#totalPrice');
        var total = 0.00;

        rows.each(function() {
            var price = $(this).find(".itemPrice").attr('data-price');
            var amount = $(this).find('.orderAmount').attr('data-amount');

            total = total + (parseFloat(price) * parseInt(amount));
        });
        totalContainer.text(total.toFixed(2));
    };

};
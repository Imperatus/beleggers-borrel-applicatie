/**
 * Created with JetBrains PhpStorm.
 * User: ijay
 * Date: 6/1/13
 * Time: 1:47 PM
 * To change this template use File | Settings | File Templates.
 */
var Liber =  Liber || {};

Liber.StockForm = function() {
    "use strict";
    var collectionHolder, addStockLink, removeStockLink, removeExisting, newLinkLiAdd, addStockForm, remStockForm;

    this.init = function() {
        collectionHolder = $('tbody.stockCollection');

        // TODO - Change to bootstrap + / -
        addStockLink = $('.add_stock_link');
        removeStockLink = $('<a href="#" class="remove_stock_link">Remove stock</a>');
        removeExisting = $('.stock_remove_existing');

        removeExisting.each(function() {
            $(this).on('click', function(e) {
                e.preventDefault();
                var message = confirm('Are you sure?');
                if(message) {
                    // add a new tag form (see next code block)
                    $(this).closest('tr').remove();
                }
            });
        });

        // add the "add a tag" anchor and li to the tags ul
        collectionHolder.append(newLinkLiAdd);

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        collectionHolder.data('index', collectionHolder.find(':input').length);

        addStockLink.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            addStockForm(collectionHolder);

        });
    };

     addStockForm = function(collectionHolder) {
         var newRemLink = removeStockLink.clone();

         // Get the data-prototype explained earlier
         var prototype = collectionHolder.data('prototype');

         // get the new index
         var index = collectionHolder.data('index');

         // Replace '__name__' in the prototype's HTML to
         // instead be a number based on how many items we have
         var newForm = prototype.replace(/__name__/g, index);

         // increase the index with one for the next item
         collectionHolder.data('index', index + 1);

         // Display the form in the page in an li, in the ul container
         var newFormLi = $('<tr></tr>').append(newForm);
         $('.stockCollection').append(newFormLi);

         // Add the remove link to remove mistakes
         var dingen = $('<td></td>').append(newRemLink);
         newFormLi.append(dingen);

         newRemLink.on('click', function(e) {
             // prevent the link from creating a "#" on the URL
             e.preventDefault();

             // add a new tag form (see next code block)
             $(this).closest('tr').remove();
         });
    };
};
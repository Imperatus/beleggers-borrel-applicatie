/**
 * Created with JetBrains PhpStorm.
 * User: ijay
 * Date: 5/29/13
 * Time: 10:58 PM
 * To change this template use File | Settings | File Templates.
 */
var SpiritStock =  SpiritStock || {};

/**
 * Tab functionality to set correct tab as active
 *
 * @constructor
 */
SpiritStock.Tabs = function() {
    "use strict";

     this.setActiveTab = function(tabName) {
        $("#"+tabName).addClass("active");
    }
}
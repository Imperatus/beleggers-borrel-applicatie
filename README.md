SpiritStock (Beleggersborrel) Application
=========================================

This web application, with Symfony2 as a basis, emulates a stock market, fluctuating prices based on supply and
demand each time a purchase is done via the in-app cashier.
The application was originally developed for a student society in the Netherlands (Liber Amsterdam), and has been used for over three
iterations of their yearly event.

Currently, the application is in beta nearing an official release. Currently a road map for the 1.0 version is being drawn out,
and we expect to share a complete app at the end of the year.

1) Installation
---------------

Refer to the Symfony2 installation guide to set up a Symfony2 environment.
Pull this repository over your Symfony2 (2.3.0) installation and do a composer install.

2) Configuration
-------------------------------------

First off, you need to make an account to use the application. Simply create an account via the web interface and verify it via e-mail.
Make sure your email is correctly configured according to the Symfony2 standard, and possibly check your spam folder.

After logging in, you are possibly prompted to set some global settings. If not, select the Settings option from the main menu and select the Global tab.
Here, select your desired currency (only Euro currently), and fill in a unit name and price. Units are used to represent custom forms of currency like bar slips, etc.
Even though you define a unit, prices will still be shown in your currency as well.

After defining your Global settings, you will need to define some Types to keep the Cashier tidy. For instance, you can define the Type Draft Beer, Soda, Wine, etc.
In the interface, stock items will be sorted under the corresponding Type. Furthermore, on the Type tab, you can define the speed at which prices drop when items are not ordered,
or the speed in which prices increase. The best way to describe the algorithm is "fuzzy", so there isn't a single good setting. If you want a specific type to fluctuate wildly,
set both sliders to their opposite extremes.

Q: "Why is the price decrease slider specific in its description, while price increase is fuzzy?"
A: Short answer is it sorta happened during the development process...
Long answer: As people tend not to like making a loss, I wanted it to be clear what the slider exactly did. The increase, however, didn't have this requirement.
I did want to make it a bit unpredictable, so there's an element of chance there. The slider represents the factor in which the calculated random increase is applied.

For instance, a random increase of 2 Euros is calculated, and the slider is set halfway. In that case the price will "probably" increase by 1 Euro. The higher the slider,
the bigger the price difference "can" be, depending on supply, demand, chance, Venus and Saturn, and the God of Hellfire.

Last but far from least, you should define your Stock. Here, you create items such as Duff Beer, give them a starting, current, minimum and maximum price.
If you don't want to make a net loss, make sure you don't set your minimum prices under the cost price! (though people will hate you for it)
Make sure you fill in your starting stock and current stock as precisely as possible, as this determines most of the price fluctuation! If you fill in you only start with
6 beers, but in reality you have over 300, you're gonna have a bad time! (or good time, if you enjoy seeing prices go all over the place)

Make sure each item falls under the correct type.

When all's set, DON'T FORGET TO CONFIRM YOUR CHANGES! I realise the system should be a tad more user-friendly and less error-prone, but as long as the XML/CSV/Excel import
feature is not yet implemented, I'm afraid you'll just have to be careful ;)



What's inside?
---------------

A settings page:

  * Create Stock items and define their current, minimum and maximum prices! Also keeps track of your stock!

  * Define Stock Types to group your items by for a clutter-free interface! (BETA)

  * Overview page for all your settings!

  * Global settings supporting one whole currency type (Euro) and custom currencies! (Whatever you wish!)

A cashier page:

  * Let your barkeeps use the cashier interface to select ordered items and calculate the price in currency and custom units for them!

  * Prices fluctuate on each order depending on Science and horoscopes!

  * A fancy news ticker displaying all (recent) price changes!

A history page:

  * Woo your audience with a lame graph that shows all price fluctuations of the past century!

Enjoy!

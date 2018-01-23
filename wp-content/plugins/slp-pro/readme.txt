=== Store Locator Plus :: Pro Pack ===
Plugin Name:  Store Locator Plus :: Pro Pack
Contributors: charlestonsw
Donate link: https://www.storelocatorplus.com/product/slp4-pro/
Tags: store locator plus, pro pack
Requires at least: 4.0
Tested up to: 4.6.1
Stable tag: 4.6.4

A premium add-on for the Store Locator Plus location management system for WordPress.

== Description ==

This plugin will help site admins manage large listings of locations with tools for processing location data en mass.

== Installation ==

= Requirements =

* Store Locator Plus: 4.5.02
* WordPress: 4.0
* PHP: 5.2.4

= Install After SLP =

1. Go fetch and install the latest version of Store Locator Plus.
2. Purchase this plugin from the [Store Locator Plus website](https://www.storelocatorplus.com) to get the latest .zip file.
3. Go to plugins/add new.
4. Select upload.
5. Upload the zip file.

== Frequently Asked Questions ==

= What are the terms of the license? =

The license is GPL.  You get the code, feel free to modify it as you wish.
Hopefully you like it enough to want to support the effort to bring useful software to market.
Learn more on the [CSA License Terms](https://www.storelocatorplus.com/products/general-eula/) page.

== Changelog ==

= 4.6.4 =

Updated language files for slp-pro text domain.

= 4.5.07 =

Change

* Include self-defined import class.   This is going away in SLP 4.5.07 base plugin.

= 4.5 =

Enhancements

* Updated de_DE translations.

Fixes

* GENERAL TAB settings in non-English languages. Use slug based settings to keep settings from disappearing when incorrect/incomplete translations files are in place.

Changes

* Requires SLP 4.5.02

= 4.4.04 =

Fixes

* Immediate import on remote CSV files via the CRON import system.
* Saving of Import checkbox state when using Upload Location or Import Locations.

= 4.4.02 =

Fixes

* Delete duplicate report table indexes.
* Fix the index creation SQL.

= 4.4.01 =

Enhancements

* Location sensor is now a standard serialized option , reducing map page load times.
* Add a new slp_prepare_for_import hook for extending the import options.
* Reduce the overhead of the CSV Import process
* Reduce memory footprint of plugin.

Fixes

* Lat/lng import in csv files.
* Clean up the settings interfaces.

Changes

* Requires SLP 4.4.26

= 4.4 (2015-Dec-15) =

* Change: Make sure the Experience / View setting does not fight with the new Experience add-on.
* Enhancement: Simplify codebase by removing debugging stubs from older development environment.
* Change: More efficient code-loading sequence with Store Locator Plus.
* Change: Requires SLP 4.3.21+

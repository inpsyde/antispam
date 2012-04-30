=== Inpsyde AntiSpam ===
Contributors: eteubert, Bueltge
Donate link: 
Tags: spam, protection, javascript, anti-spam
Requires at least: 3.0
Tested up to: 3.4-alpha
Stable tag: 2.0.0

Simple antispam solution with the magic of javascript

== Description ==
Simple antispam solution. Scrambles a word and pieces it together automatically via JavaScript. Users with JavaScript enabled won't notice anything. Those who have JavaScript disabled have to type the word into a textfield. This plugin won't help against a targeted spam attack but should prevent a lot of random spam.

No remote service needed. Does not send or retrieve data from third party services.

= Bugs, technical hints or contribute =
Please give us feedback, contribute and file technical bugs on [GitHub Repo](https://github.com/inpsyde/inpsyde-antispam).

== Installation ==
= Requirements =
* WordPress (current not Multisite) version 3.3 and later (tested at 3.4)
* PHP 5.3 (use namespaces)

= Installation =
* Use the installer via backend of your install or ...
 
1. Unpack the download-package
1. Upload all files to the `/wp-content/plugins/` directory, without folder
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to *Settings* -> *Antispam* and configure the plugin
1. Ready

== Changelog ==
= 2.0.0 =
* Rewrite of all, only the idea is the same

= 1.2.4 (05/05/2010) =
* fix js-error for oben boxes
* add `uninstall.php` for default deinstall options without source of the plugin

= 1.2.3 (04/23/2010) =
* change include js for metaboxes
* test with WP 3.0beta1

= 1.2.2 =
* add ID `js_antispam` for p-tag
* small changes on code

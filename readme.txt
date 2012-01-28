=== Plugin Name ===
Contributors: jermainemaree
Tags: framework, theme framework
Requires at least: 3.1
Tested up to: 3.3.1
Stable tag: 1.2.5

Feather is a lightweight and powerful WordPress theme framework. Easily configure your theme using a configuration file.

== Description ==

Feather is a lightweight and powerful WordPress theme framework by [WPBandit](http://wpbandit.com). Easily configure your theme using a single configuration files. Visit Feather's [website](http://banditmedia.github.com/feather/) for more information.

== Installation ==

To quickly get going follow the instructions below:

1. Upload feather to the `/wp-content/plugins/` directory
2. Create a **feather** folder in your theme's directory.
3. Create a **config** folder inside the _feather_ directory.
4. Create an empty **config-feather.php** file in the _config_ directory.
5. Activate the plugin through the 'Plugins' menu in WordPress.

== Screenshots ==

1. General Options
2. Sidebar Options
3. Advanced Options

== Changelog ==

= 1.2.5 - 2012.01.27 =
* Extend the $allowedposttags variable for meta fields

= 1.2.3 - 2011.12.02 =
* Added missing login module
* Updated colorpicker.css

= 1.2.2 - 2011.12.01 =
* Upgrade in admin only
* Fix radio selected value in settings library
* Add $page variable to print_tabs function
* Run theme init method before theme configuration
* Verify $meta['args'] variable before saving meta
* Set default meta template to null
* Use wp_parse_args to set script defaults

= 1.2 - 2011.11.28 =
* Restructured framework core and libraries
* Updated login module styles for WP 3.3

= 1.1 - 2011.11.22 =
* Added config option to register scripts
* Fix bug when feather-config.php is missing
* Ability to load modules from folder
* Added meta validation
* Load modules before config files
* Fix maintenance mode bug
* Add login page tab + options

= 1.0 - 2011.09.20 =
* Feather released.

== Upgrade Notice ==

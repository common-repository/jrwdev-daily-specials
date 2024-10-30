=== JRWDEV Daily Specials ===

Contributors: jrwdev
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=EPD25LMXF4A4E
Tags: restaurant, specials, daily specials, store, store specials
Requires at least: 3.0
Tested up to: 3.7.1
Stable tag: 1.5.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a custom post type Daily Specials allowing you to easily feature daily specials from your store in either a widget or on a page or post via shortcode. It requires the advanced custom fields plugin to operate correctly.

== Description ==

This plugin adds a custom post type Daily Specials to your site allowing you to easily feature daily specials from your store in either a widget or on a page or post via shortcode. It requires the <a href="http://wordpress.org/extend/plugins/advanced-custom-fields/">advanced custom fields plugin</a> (by Elliot Condon v3.0 or later) to operate correctly.

== Installation ==

1. Install (if not already installed) advanced custom fields widget by Elliot Condon
2. Upload `jrwdev-daily-specials` folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Create Daily Specials in your Admin Panel
5. Activate Daily Specials Widget in your sidebar

== Frequently Asked Questions ==

= Where's the Documentation? =
I'm working on documentation for the shortcode. It will be available when I get to it.

= I dragged the widget into the sidebar, and now my layout is broken and parts of the page are missing. What's going on? =
You need to install and activate the advanced custom fields plugin. This plugin requires the <a href="http://wordpress.org/extend/plugins/advanced-custom-fields/">advanced custom fields plugin</a> (by Elliot Condon v3.0 or later) to operate correctly.

== Screenshots ==

No Screenshots Yet. Coming Soon.

== Changelog ==

= 1.5.2 =
* Maintenance release to fix a bug introduced by Advanced Custom Fields v4.3.0 (thanks to @hifidesign for pointing out the issue)
* Fixed plugin dependance errors
* Fixed small warnings

= 1.5.1 =
* Maintenance release to fix a bug introduced by Advanced Custom Fields v4.3.0 (thanks to @hifidesign for pointing out the issue)
* In order for this new version to work, you'll need to open/edit each of the daily specials that you have on your site and re-save them. The plugin needs to recognize the new custom fields that the ACF plugin introduced.

= 1.5 =
* Added an archives page for the daily specials post type
* Allowed users to override archives page in their theme by adding an archive-daily_specials.php file to their theme folder

= 1.4.3 =
* Modified the description field to accept html code and media uploads from the media library

= 1.4.2 =
* Code Cleanup

= 1.4.1 =
* Added deal field to daily specials to allow for custom deals instead of fixed dollar amount prices

= 1.4 =
* Added categories to daily specials to allow for multiple widgets showing different specials
* Added multiple ordering options
* Fixed ordering issues
* Fixed several bugs
* Consolidated some extraneous code

= 1.3 =
* Fixed bug where two specials on the same day wouldn't show

= 1.2 =
* Fixed some php notices and warnings
* Updated docs and FAQ

= 1.1 =
* Added settings page
* Added default options
* Added widget options

= 1.0 =
* Initial Stable Release
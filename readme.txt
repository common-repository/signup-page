=== Plugin Name ===
Contributors: sgamon
Donate link: http://gamon.org/
Tags: signup
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: trunk

Creates a public signup form where people can sign up for pot lucks, volunteer jobs, whatever.

== Description ==

*signup-page* is a plugin that allows you to create a public signup page. A signup page is just a list of stuff
that you can sign up for, with a space next to each item where someone can sign up.

There is no validation of input, and no login is required. This is a true public signup page. However, when a
person signs up, that slot is frozen to the public (no further changes). Site admins can make changes to any slot,
at any time.


== Installation ==

1. Download the plugin.
1. Unpack the zip archive to your `/wp-content/plugins/` folder
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Create a new page for the signup form. Give it a title for what you are
signing up for (ie, "Pot Luck").
1. Add this shortcode to the signup page: `[signup_page list_title="List" field_title="Signup"]` 
(*item_title* and *field_title* are optional. The default values are "List" and "Signup".)
1. Create one or more child pages of the signup page. (ie, create pages called "Salad", "Chips", "Hot Dogs", 
and "Apple Pie". Make them children of "Pot Luck".)

On the signup page, each of the child pages will be listed, alongside a field to *sign up* for that page.


== Frequently Asked Questions ==

= How do I create a list of things to sign up for? =

All the child pages of the signup page make of the list.


== Screenshots ==

1. A signup form used on a girl scout camp site.


== Changelog ==

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.0 =
Initial release.


=== Plugin Name ===
Contributors: en0x
Tags: ticket, simple, system, support, plugin, request, help, desk
Requires at least: 3.0.1
Tested up to: 4.3
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple, fast and responsive ticket system to receive and store Problems of Customers or Visitors, with a great search function.

== Description ==
This plugin adds a ticket system to your page, to handle Questions, problems or other requests from visitors of your page.
You can use it as a help desk for co-workers in your Intranet or as a contact form for your website for example.

= Demo =
[Right here](http://wp12405556.server-he.de/)

= GitHub =
[This Plugin on Github](https://github.com/en00x/Simple-Ticket-System-WordPress)

= Key features =

*   ticket submission through the frontend
*   ticket editing through the frontend
*   wordpress-independent user administration in backend
*   normal users can take, edit their own and adopt tickets. They also can add appointments.
*	admin users can edit all tickets and allocate tickets to other users
*   you can add notes and solutions to every ticket
*	you can filter tickets for Issuer, name or mail of creator, done tickets, solution or problems
*	datepicker for appointments

== Installation ==


1. Upload `simple-support-ticket-system` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Place `<?php do_shortcode('[ts_form]'); ?>` in your templates or [ts_form] in page edit mode for the ticket submission form.
4. Place `<?php do_shortcode('[ts_tickets]'); ?>` in your templates or [ts_tickets] in page edit mode for the ticket system page.

== Languages and Localization? ==
The plugin currently comes with the following translations:
English, German

== Frequently asked questions ==

Not yet!

== Screenshots ==

1. ticket submission form
2. ticket system with a new ticket
3. ticket in editing mode
4. ticket with appointment
5. filter for tickets
6. backend page


== Changelog ==
= 1.0.1 =
* Deleted Salutation because the email function is not ready
* added titles for buttons
= 1.0 =
* First release

== Upgrade notice ==
= 1.0 =
First release

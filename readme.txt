=== Plugin Name ===
Contributors: en0x
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YA24QZ2SP4NP6
Tags: ticket, simple, system, support, plugin, request, help, desk
Requires at least: 3.0.1
Tested up to: 4.7.1
Stable tag: 1.3.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple, fast and responsive ticket system for the Front-End to receive and store Problems of Customers or Visitors, with a great search function.

== Description ==
The idea was it to create a Ticket System completely for the Front-End, and that's the result!
This plugin adds a ticket system to your page, to handle Questions, problems or other requests from visitors of your page.
You can use it as a help desk for co-workers in your Intranet or as a contact form for your website for example.

= Demo =
[Right here](http://demo.en0x.de)

= GitHub =
[This Plugin on Github](https://github.com/en00x/Simple-Ticket-System-WordPress)


= Key features =

*	Completely for the Front-End.
*   Independet Login from Wordpress, or when you are logged in into Wordpress, then you will be automatically logged in.
*	E-Mail Notification
*	To every ticket you get a link so you can check your ticket and also write with the support like a chat.
*   Normal users can take, edit their own and adopt tickets. They also can add appointments.
*	Admin users can edit all tickets and allocate tickets to other users.
*   You can add notes and solutions to every ticket.
*	You can filter tickets for Issuer, name or mail of creator, done tickets, solution or problems.
*	Datepicker Field
*	Under "New Tickets" the tickets automatically reload every 30 seconds.

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
6. General Settings
7. User Administration


== Changelog ==
= 1.3.5 =
* Function added to send a ticket-link within the notification e-mails.
= 1.3.4 =
* Added Multisite support
= 1.3.3 =
* User Guide added
= 1.3.2 =
* Fixed search function
= 1.3.1 =
* Fixed unintentional backslashes in text
= 1.3 =
* BIG UPDATE
* Email notification for those who create the ticket. They will be informed when their ticket was "taken", when it's "done" and when there is a new answer.
* You can consult and answer your ticket under an generated link.
* Following you can answer when you work on tickets, so it's kind like a chat.
* Problem Field is renameble now.
* "Report problem" was changed to "Submit".
= 1.2.2 =
* IMPORTANT
* When you updated to 1.2.2 before 19.11.2015 (UTC+1 13:00h) and your wordpress backend doesn't load anymore please redownload fixed v1.2.2
* 
* Only whitespace in necessary fields isn't allowed anymore.
* Database Update only loads when user is admin.
= 1.2.1 =
* Security Update
* Special thanks to the user "iberiam"
= 1.2 =
* You can rename the optional fields and the datepicker field.
* You can also hide these fields.
* Appointment field is now the datepicker field.
* Appointment added from the issuer are shown under the ticket itself.
* When logged in Name and Email in Form are not editable.
= 1.1 =
* PLEASE REACTIVATE THE PLUGIN TO APPLY CHANGES THIS TIME
* When you are already logged in in Wordpress, you don't have to log in again
* When you are logged in, Name and Email will be filled in into submit form
* Added a Title to tickets
* Loading Spinner now with transparent background
= 1.0.1 =
* Deleted Salutation because the email function is not ready
* added titles for buttons
= 1.0 =
* First release
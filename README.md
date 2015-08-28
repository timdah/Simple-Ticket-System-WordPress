* [Description](#description)
* [Screenshots](#screenshots)
* [Installation](#installation)
* [Languages](#languages)

---------

## Description 
This plugin adds a ticket system to your page, to handle Questions, problems or other requests from visitors of your page.
You can use it as a help desk for co-workers in your Intranet or as a contact form for your website for example.


Key features:
*   ticket submission through the frontend
*   ticket editing through the frontend
*   wordpress-independent user administration in backend
*   normal users can take, edit their own and adopt tickets. They also can add appointments.
*	admin users can edit all tickets and allocate tickets to other users
*   you can add notes and solutions to every ticket
*	you can filter tickets for Issuer, name or mail of creator, done tickets, solution or problems
*	datepicker for appointments


##  Screenshots
#### Ticket submission form
![screenshot-1](https://cloud.githubusercontent.com/assets/13997715/9519994/09460dc4-4cc4-11e5-9d74-cdc392052a59.png)

#### Ticket system with a new ticket
![screenshot-2](https://cloud.githubusercontent.com/assets/13997715/9519992/0944c70c-4cc4-11e5-9d19-a6eb4e45dd83.png)

#### Ticket in editing mode
![screenshot-3](https://cloud.githubusercontent.com/assets/13997715/9519993/09457a3a-4cc4-11e5-8864-fa9946e4b2fc.png)

#### Ticket with appointment
![screenshot-4](https://cloud.githubusercontent.com/assets/13997715/9519991/0943a778-4cc4-11e5-9951-e18e89a5dc3f.png)

#### Filter for tickets
![screenshot-5](https://cloud.githubusercontent.com/assets/13997715/9519996/0949566e-4cc4-11e5-82f9-ee559a5113ad.png)
filter for tickets

#### Backend page
![screenshot-6](https://cloud.githubusercontent.com/assets/13997715/9519995/094827f8-4cc4-11e5-9a1a-9689b6b49607.png)




## Installation

1. Upload `ticket-system-simple` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Place `<?php do_shortcode('[ts_form]'); ?>` in your templates or [ts_form] in page edit mode for the ticket submission form.
4. Place `<?php do_shortcode('[ts_tickets]'); ?>` in your templates or [ts_tickets] in page edit mode for the ticket system page.


## Languages
The plugin currently comes with the following translations:
English, German

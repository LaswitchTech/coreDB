# ChangeLog

## Version 22.04-dev Build: 121
* (README.md): Updated the readme file to match the license

## Version 22.04-dev Build: 120
* (LICENSE): Change license to GNU GPLv3
* (TASKS.md): Updated Tasks list
* (dist/css/stylesheet.css): Added support for various bootstrap switches sizes
* (dist/js/engine.js): Increased the size of the profile status button
* (dist/js/engine.js): Added a notification tab to the profile so the user can control his notifications
* (dist/js/engine.js): Fixed some responsive issues with the details layout
* (dist/js/engine.js): Added a component switch to forms
* (dist/js/engine.js): Added shadows to timeline
* (src/lib/api.php): Fixed an issue with the init method of Helper that would not accept multiple parameters at once. Meaning a single plugin could not create multiple notification types for exemple.
* (src/lib/notification.php): Change the notification type SQL to Application. This is meant so that users will better understand the type of notifications

## Version 22.04-dev Build: 119
* (TASKS.md): Updated the tasks list
* (dist/css/stylesheet.css): Fixed an issue where the background would appear above the application layout
* (dist/js/engine.js): Fixed an issue where if the api options array came out as null, it would break the init function.
* (dist/js/engine.js): Changed the debug logo to a rotating cog
* (dist/js/engine.js): Fixed an issue with crud forms where the last input would not considerate if it should take full width correctly
* (dist/js/engine.js): Added noselect to sidebar to prevent any text selection
* (dist/js/engine.js): Added support for notification subject and body
* (dist/languages/english.json): Added new fields
* (src/lib/api.php): Added helpers and support for plugins
* (src/lib/api.php): The Helper->init() function will now be executed and used to expand the API configurations
* (src/lib/api.php): All user data are now loaded within Auth
* (src/lib/api.php): Fixed an issue where if no language settings exist, system language would not be setup
* (src/lib/auth.php): All user data are now loaded within Auth
* (src/lib/helper.php): Helper now loads all plugin's helper within a Helpers property
* (src/lib/helper.php): A new method scan() has been added
* (src/lib/helper.php): A new method init() has been added
* (src/lib/helper.php): A new method plugins() has been added
* (src/lib/helper.php): A new method loadHelpers() has been added
* (src/lib/helper.php): A new method exist() has been added
* (src/lib/notification.php): New notification types are now supported and can be added through a helper init function
* (src/lib/notification.php): Added support for email notifications
* (src/lib/notification.php): Added settings to notifications to control which notifications to create and/or send
* (src/templates/template.php): Added support for plugins for stylesheets and javascripts extansions

## Version 22.04-dev Build: 118
* (TASKS.md): Updated the tasks list
* (dist/js/engine.js): Changed the color of the debug icon to primary
* (dist/js/engine.js): Fixed an issue with forms components where the name attribute would be set to all lowerCase
* (dist/js/engine.js): Added an option to all forms components to hide the input. This will create the html and add the class d-none to the main container
* (dist/js/engine.js): Completed the table control new. The control now generates a form to create a new record based on the table. A predifined form can also be set to the table.
* (dist/js/engine.js): Completed the table action edit. The action now generates a form to edit a record in the table. Again based on the table. A predefined form can also be set to the table for the update form.
* (dist/js/engine.js): Completed the table action delete. This action deletes a record from the table and database.
* (dist/js/engine.js): Added support for custom forms in the table.
* (dist/js/engine.js): The row.data() function now also allow you to update the data of the row.
* (dist/js/engine.js): Fixed an issue where adding a row based on a record that did not have the same keys order would create a row with the bad key order.
* (dist/js/engine.js): Form header translation can now be toggled
* (dist/js/engine.js): The basic CRUD pages have now been updated using the new list method of the CRUD api.
* (src/lib/api.php): Added Helpers to the api. The helpers can be used to extends some existing methods.
* (src/lib/crud.php): A new list method has been added to fetch all the necessary information for a table listing in one request.
* (src/lib/database.php): Fixed an issue in the prepare method where a DELETE type would not automatically add the primary key condition.
* (src/lib/installer.php): Added a group administrator to each new group created during install.

## Version 22.04-dev Build: 117
* (TASKS.md): Updated the task list
* (dist/js/engine.js): Added shadows to table cards
* (dist/js/engine.js): Added a loader to tables
* (dist/js/engine.js): Added a cursor pointer to the debug button
* (dist/js/engine.js): Fixed an issue where the listing layout would not provide the entire table functions
* (dist/js/engine.js): Added an empty notice to listings

## Version 22.04-dev Build: 116
* (dist/js/engine.js): The default layout for the Dashboard is now empty
* (dist/js/engine.js): Fixed the modal header padding
* (dist/js/engine.js): Fixed the table controls padding

## Version 22.04-dev Build: 115
* (dist/css/stylesheet.css): Fixed an issue with timeline items. Upgraded the classes to support font-awesome v6.
* (dist/js/engine.js): Minor changes to the dashboard

## Version 22.04-dev Build: 114
* Attempted a fix for and issue where if any quote is found in the changelog, it would create an error during the commit.

## Version 22.04-dev Build: 113
* (TASKS.md): Updated Tasks list
* (dist/css/stylesheet.css): Added classes for JQuery Sortable
* (dist/js/engine.js): Updated all font-awesome icons to v6
* (dist/js/engine.js): Added a dashboard layout
* (dist/js/engine.js): Dashboard can be customized by the user
* (dist/js/engine.js): Engine.Builder.forms.select can now be updated using the function .update(list = {})
* (dist/js/engine.js): Added a modal component to the Engine.Builder.
* (dist/js/engine.js): Fixed the padding of navbar controls to make them easier to click or press
* (dist/js/engine.js): The notification icon now jumps whenever a new notification is added
* (dist/languages/english.json): Added new fields
* (src/lib/api.php): Added a new option class with method to save user or group options
* (src/lib/api.php): Fixed an issue with fetching user's options
* (src/lib/api.php): Updated all font-awesome icons to v6
* (src/lib/auth.php): Updated all font-awesome icons to v6
* (src/lib/database.php): Fixed several issues with the CRUD functions. Mostly related to nested arrays conversion to JSON
* (src/lib/notification.php): Updated all font-awesome icons to v6
* (vendor/fontawesome-free/css/all.css): Updated all font-awesome icons to v6
* (vendor/fontawesome-free/css/all.min.css): Updated all font-awesome icons to v6
* (vendor/fontawesome-free/css/brands.css): Updated all font-awesome icons to v6
* (vendor/fontawesome-free/css/brands.min.css): Updated all font-awesome icons to v6
* (vendor/fontawesome-free/css/fontawesome.css): Updated all font-awesome icons to v6
* (vendor/fontawesome-free/css/fontawesome.min.css): Updated all font-awesome icons to v6
* (vendor/fontawesome-free/css/regular.css): Updated all font-awesome icons to v6
* (vendor/fontawesome-free/css/regular.min.css): Updated all font-awesome icons to v6
* (vendor/fontawesome-free/css/solid.css): Updated all font-awesome icons to v6
* (vendor/fontawesome-free/css/solid.min.css): Updated all font-awesome icons to v6
* (vendor/fontawesome-free/css/svg-with-js.css): Updated all font-awesome icons to v6
* (vendor/fontawesome-free/css/svg-with-js.min.css): Updated all font-awesome icons to v6
* (vendor/fontawesome-free/css/v4-shims.css): Updated all font-awesome icons to v6
* (vendor/fontawesome-free/css/v4-shims.min.css): Updated all font-awesome icons to v6
* (vendor/fontawesome-free/webfonts/fa-brands-400.ttf): Updated all font-awesome icons to v6
* (vendor/fontawesome-free/webfonts/fa-brands-400.woff2): Updated all font-awesome icons to v6
* (vendor/fontawesome-free/webfonts/fa-regular-400.ttf): Updated all font-awesome icons to v6
* (vendor/fontawesome-free/webfonts/fa-regular-400.woff2): Updated all font-awesome icons to v6
* (vendor/fontawesome-free/webfonts/fa-solid-900.ttf): Updated all font-awesome icons to v6
* (vendor/fontawesome-free/webfonts/fa-solid-900.woff2): Updated all font-awesome icons to v6

## Version 22.04-dev Build: 112
* (dist/css/colors.css): Fixed text color of light elements
* (dist/css/stylesheet.css): Added classes for JQuery.sortable()
* (dist/js/engine.js): Added the ability to sort widgets in the dashboard
* (dist/js/engine.js): Added button groups support in the navbar
* (dist/js/engine.js): Fixed an issue with padding on buttons with only an icon in the navbar
* (dist/languages/english.json): Added new fields
* (src/lib/api.php): Added group options

## Version 22.04-dev Build: 111
* (src/lib/cli.php): Avoid quotes in changelog I guess

## Version 22.04-dev Build: 110
* (src/lib/cli.php): Testing quote '

## Version 22.04-dev Build: 109
* Testing quote '

## Version 22.04-dev Build: 108
* Testing quotes "

## Version 22.04-dev Build: 107
* (src/lib/cli.php): Fixed quotes

## Version 22.04-dev Build: 106
* (src/lib/cli.php): Fixed an issue with the publish method were if you entered quotes "'", git would cut out the changelog.

## Version 22.04-dev Build: 105
* (dist/css/stylesheet.css): Added a z-index to the background class
* (dist/js/engine.js): Dedicated a property to the Debug Logger. Now accessible at Engine.Logger
* (dist/js/engine.js): Rework the logger entirely. The original console is now entirely replaced with the new logger
* (dist/js/engine.js): Fixed gutters and padding on the dashboard
* (dist/js/engine.js): Added support for mutiple windows sizes to the dashboard
* (dist/js/engine.js): Added a background to the application view
* (dist/js/engine.js): Some code cleanup
* (dist/js/engine.js): Added a stick setting to navbar menu items. This tells the navbar.render(); which items to remove when loading a new layout.
* (dist/js/engine.js): Added a linkAction setting to dropdowns. This allows you to add a callback to all dropdown items created.
* (dist/js/engine.js): Fixed an issue where Sidebar dropdown do not update the active status
* (src/lib/api.php): Fixed an issue were user's options would not be loaded. It would load permissions into the Options property instead of the options
* (src/lib/database.php): Added a getPrimary method to retrieve the primary key of a table
* (src/lib/database.php): Made all CRUD method use the new getPrimary method. This made the methods support any primary key.

## Version 22.04-dev Build: 104
* Updated languages and database structure

## Version 22.04-dev Build: 103
* (TASKS.md): Updated the tasks list
* (dist/js/engine.js): Fixed an issue were stopping the Engine.Clock would not actually stop it
* (dist/js/engine.js): Fixed an issue were starting the Engine.Clock would start an additionnal Clock if the Engine.Clock was already started
* (dist/js/engine.js): The application layout will now be updated if the logged in user's status changes.
* (dist/js/engine.js): A new layout for disabled accounts was added.
* (dist/js/engine.js): The search results are now being verified for permissions.
* (dist/js/engine.js): Fixed various issues with table selection.
* (dist/js/engine.js): Inserting a new layout will now properly update the correct layout container
* (dist/js/engine.js): The CRUD dropdown in the sidebar now requires an administrator
* (dist/languages/english.json): Added new fields
* (src/lib/api.php): The logger is now turned off by default
* (src/lib/api.php): Fixed an issue were the logger's status would not update correctly with the application settings
* (src/lib/application.php): The logger is now turned off by default
* (src/lib/application.php): Fixed an issue were the logger's status would not update correctly with the application settings
* (src/lib/auth.php): The logger is now turned off by default
* (src/lib/auth.php): Fixed an issue were the logger's status would not update correctly with the application settings
* (src/lib/auth.php): Fixed an issue with users deactivation. Disabled account now uses status 3. While Deactived account uses status 2.
* (src/lib/cli.php): The logger is now turned off by default
* (src/lib/cli.php): Fixed an issue were the logger's status would not update correctly with the application settings
* (src/lib/cli.php): The clear method now verifies if the log file exist before trying to delete it.
* (src/lib/crud.php): The search results are now being verified for permissions.

## Version 22.04-dev Build: 102
* (TASKS.md): Updated tasks list
* (dist/js/engine.js): Fixed an issue with the toCSV Helper. There was a leftover line from previous code.
* (dist/js/engine.js): Fixed an issue with table search where if the number of results were less then 10 and the results were at the end of the table, the table would not properly render the search results and pagination.
* (dist/js/engine.js): Fixed an issue caused when renaming the header in the application layout to navbar. This caused several navbar elements to stop working due to references to the old Engine.Layout.header.
* (dist/js/engine.js): Fixed the padding of the sidebar dropdowns to make them easy to click or press.
* (dist/languages/english.json): Added new fields
* (src/lib/database.php): Fixed an issue in the prepare method that would cause the conditions to use the value as column name instead of the name of the column

## Version 22.04-dev Build: 101
* (TASKS.md): Updated the Task list
* (dist/js/engine.js): Added dropdowns to sidebar nav

## Version 22.04-dev Build: 100
* (.gitignore): Added an exception for a local command
* (README.md): Corrected some typos
* (TASKS.md): Updated the task list
* (dist/js/engine.js): Debug is now turned off by default
* (dist/js/engine.js): Fixed an issue where the Debug button would not render properly when debug was toggled on,off then on again
* (dist/js/engine.js): Fixed an issue with the new logger that would allow console output even if debug was off
* (dist/js/engine.js): Added a clock to the javascript Engine
* (dist/js/engine.js): Added a parameter show to settings to allow the Engine to understand which settings should appear in the DOM
* (dist/js/engine.js): Added notifications support to the Javascript Engine. The icon appears in the navbar if enabled
* (dist/js/engine.js): Added options to Engine.Builder.components.dropdown to allow new links to be inserted before and after another link
* (dist/js/engine.js): Added a Notification property to the Javascript Engine to control the notifications
* (dist/languages/english.json): Added new fields
* (src/lib/api.php): Added a Notification class to provide easy access to notifications
* (src/lib/api.php): Added a readNotifications method to change the status of notifications
* (src/lib/api.php): Added a show property to settings to allow the Engine to understand which settings should appear in the DOM
* (src/lib/cli.php): Added a method debug to toggle debug mode on or off
* (src/lib/database.php): Fixed an issue with the prepare method where sometimes it would prepare a statement if duplicated conditions
* (src/lib/notification.php): Added a Notification class to provide easy access to notifications
* (src/lib/smtp.php): Added a method setLanguage to update the language fields. This allows the SMTP class to change language without reconnecting the SMTP Server or reloading the Class.

## Version 22.04-dev Build: 99
* (dist/js/engine.js): Moved the debug click action to a dedicated property to allow developper to trigger it
* (src/lib/api.php): Added a notification Class to create and read notifications
* (src/lib/api.php): Added support for boolean value in logger
* (src/lib/api.php): Added support for objects value in logger
* (src/lib/auth.php): Added the Debug property
* (src/lib/auth.php): Added support for boolean value in logger
* (src/lib/auth.php): Added support for objects value in logger
* (src/lib/crud.php): Added a require_once to import the derivative class
* (src/lib/database.php): Added support for subarrays and objects to sql queries. Those are automatically converted into JSON.
* (src/lib/installer.php): Added support for boolean value in logger
* (src/lib/installer.php): Added support for objects value in logger

## Version 22.04-dev Build: 98
* (src/lib/cli.php): Fixed the same issue as build 97

## Version 22.04-dev Build: 97
* (src/lib/cli.php): fixed an issue with the dynamic update of README.md where the version badge would be replaced by a build badge

## Version 22.04-dev Build: 96
* (README.md): Added a feature

## Version 22.04-dev Build: 95
* (src/lib/cli.php): Updated the publish method to perform dynamic updates on the README.md file instead of static updates. Thus the file can now be edited by developper without issues.

## Version 22.04-dev Build: 94
* (dist/js/engine.js): Added the abilty for users to set there own language
* (src/lib/api.php): Added a private method to set the application language
* (src/lib/auth.php): The administration email will now be notified of suspicious activity
* (src/lib/smtp.php): Added support for BCC and CC in the send method

## Version 22.04-dev Build: 93
* (src/lib/installer.php): Added support for the gkey

## Version 22.04-dev Build: 92
* (dist/js/engine.js): Fixed issues with the navigation not updating the active class of the active link
* (dist/js/engine.js): Added support for the gkey (GoogleAPIKey)
* (src/lib/api.php): Added support for the gkey (GoogleAPIKey)

## Version 22.04-dev Build: 91
* (dist/js/engine.js): Added a log function to Engine.Debug.logger. This function provides a lot more debug data to the standard console.log. Such as complete tracing and datetime.
* (dist/js/engine.js): Fixed an issue with the encoder and decoder functions in Engine.Helper. This fixes the issue where some language symbole would not encode properly and thus return false
* (dist/languages/francais.json): Now generated using Google Translate API
* (src/lib/api.php): Added support for Google Translate API
* (src/lib/cli.php): Added a method translate to translate all language fields to various languages

## Version 22.04-dev Build: 90

## Version 22.04-dev Build: 89
* (src/lib/cli.php): Moving the dev workflow to templates.

## Version 22.04-dev Build: 88
* (src/lib/cli.php): The publish method will now use the changelog as the commit message

## Version 22.04-dev Build: 87

## Version 22.04-dev Build: 86
* (src/lib/cli.php): Fixed an issue while updating the workflows

## Version 22.04-dev Build: 85
* (.github/workflows/dev-release.yml): rename the file
* (.github/workflows/pre-release-release.yml): rename the file

## Version 22.04-dev Build: 84
* (src/lib/cli.php): Added support for all existing branches (Dev,Pre-Release and Stable)

## Version 22.04-dev Build: 83
* (src/lib/cli.php): The publish method now updates the workflows and creates a LATEST.md file containing the latest changes

## Version 22.04-dev Build: 82
* (README.md): Removed the duplicate section for issues
* (TASKS.md): Updated the tasks list
* (src/lib/auth.php): Fixed various issues related to login and activation that were caused by the last update
* (src/lib/cli.php): Now updates the repository in the ##Installation section
* (src/lib/database.php): Added a deleteRelationships method to remove relations to and of a record

## Version 22.04-dev Build: 81
* (documentation/javascript engine/README.md): Updated the listing of properties
* (documentation/javascript engine/auth.md): Updated the documentation
* (documentation/javascript engine/helper.md): Updated the documentation
* (documentation/javascript engine/request.md): Updated the documentation
* (documentation/javascript engine/toast.md): Updated the documentation
* (src/lib/auth.php): The method getUser will now protect the various hashed values.

## Version 22.04-dev Build: 80
* (dist/languages/english.json): Added some fields
* (src/lib/auth.php): Fixed Login using Cookies (remember me) will not work if the PHPSESSID has changed.
* (src/lib/auth.php): replaced all instance of variable $_COOKIE["PHPSESSID"] to session_id()
* (src/lib/auth.php): Added a setCookie method to properly set or refresh the login cookie

## Version 22.04-dev Build: 79
* (src/lib/auth.php): Fixed an issue where user would not be able to logout

## Version 22.04-dev Build: 78
* (src/lib/auth.php): Auth will now record all active sessions in the database.
* (src/lib/auth.php): $_SESSION variable is now set using the PHPSESSID value
* (src/lib/auth.php): $_COOKIE variable is now set using the PHPSESSID value
* (src/lib/auth.php): The logout method will now erase all $_SESSION variables
* (src/lib/auth.php): Added the getClientBrowser method to identify the client's browser
* (src/lib/database.php): Removed commented lines

## Version 22.04-dev Build: 77
* (src/lib/database.php): Added support for attribute ON UPDATE CURRENT_TIMESTAMP

## Version 22.04-dev Build: 76
* Fixed spacing in logo

## Version 22.04-dev Build: 75
* Updated logo

## Version 22.04-dev Build: 74

## Version 22.04-dev Build: 73
* (src/lib/cli.php): Fix for the manifest name

## Version 22.04-dev Build: 72
* (src/lib/cli.php): The publish method will now setup a default name to both the README.md file and the manifest.json

## Version 22.04-dev Build: 71
* (.gitignore): Now ignore the entire config/ directory

## Version 22.04-dev Build: 70
* Repository moved from https://github.com/LouisOuellet/stm to https://github.com/LaswitchTech/coreDB

## Version 22.04-dev Build: 69
* (dist/css/stylesheet.css): Removed all scrollbars from the sidebar
* (dist/css/stylesheet.css): Added some pagination and search classes
* (dist/js/engine.js): Fixed an overflow issue with the main container
* (dist/js/engine.js): moved the profile controls to the navbar
* (dist/js/engine.js): added the code for select all and select none functions
* (dist/js/engine.js): added the delete Toast to the delete function
* (dist/js/engine.js): added support for pagination in tables
* (dist/js/engine.js): fixed multiple issues with search on a paginated table
* (dist/js/engine.js): the collapse button on table now collapse the entire body and footer of the table
* (dist/js/engine.js): Added various counts to table footer such as Selected count, total count and filtered count
* (dist/js/engine.js): Added a table.data() function to set and retrieve table data
* (dist/js/engine.js): Added the ability to specify a column to use as the rowID
* (dist/js/engine.js): Added a row.data() function to set and retrieve row data
* (dist/js/engine.js): Moved search data to the row instead of cell.
* (dist/js/engine.js): Added a row.search() function to set and retrieve row search formatted data
* (dist/js/engine.js): Fixed the padding of the action button to make it easier to press
* (dist/js/engine.js): Fixed the padding of the control button to make it easier to press
* (dist/languages/english.json): Added multiple text fields
* (src/lib/database.php): Added support for UNIQUE columns to database structure methods

## Version 22.04-dev Build: 68
* (dist/css/colors.css): Added gray tints to .link
* (dist/js/engine.js): Fixed an issue with Engine.request where the Engine would throw an error when no data would be received from the AJAX call
* (dist/js/engine.js): Fixed an issue with the isSet function in Engine.Helpers where if the first parameter provided was undefined, the function would throw an error instead or false
* (dist/js/engine.js): Removed controls and actions by default on the search results layout
* (dist/js/engine.js): Added results count to each generated tables of the search results
* (dist/js/engine.js): Added Controls to the table component. Available controls are 'new' and 'select'.
* (dist/js/engine.js): Added Actions to the table component. Available actions are 'edit' and 'delete'.
* (dist/js/engine.js): Added plugin support to table default settings. This way developpers can create plugins that will interact with the default Controls and Actions.
* (dist/js/engine.js): Added support for a table title. Only available when the table is put in a Card. Which is the default behavior
* (dist/js/engine.js): The table.add.row() function now accept a record to be inserted at the creation of the row.
* (dist/js/engine.js): Row selection trigger was moved to individual cells. Thus giving the ability to create the actions buttons that would not trigger the row selection.
* (dist/js/engine.js): Added an option to the dropdown component to trigger the active behavior. Meaning wether or not when a button is pressed should it be set as active.
* (dist/js/engine.js): Fixed an issue with the dropdown component where it would always take 100% and align itself center.
* (dist/js/engine.js): Fixed an issue where if you submitted a search, the sidebar and profile dropdown active links would not reset.
* (dist/languages/english.json): Added additional missing fields
* (src/lib/crud.php): Fixed an issue with the search method where if you submitted a '0' to it, it would interpret it as an empty search and thus not return anything.

## Version 22.04-dev Build: 67
* (dist/css/stylesheet.css): Added class selectedRow for tables
* (dist/js/engine.js): Added a search layout
* (dist/js/engine.js): The search input will now trigger a general search through the API on submit
* (dist/js/engine.js): Search Results can now be filtered after a general search
* (dist/js/engine.js): Added a card by default to the table component
* (dist/js/engine.js): Added a title to tables
* (dist/js/engine.js): Added rowData to each rows of a table
* (dist/js/engine.js): Rows can now be selected including multiple rows
* (dist/js/engine.js): Listing layout was updated to reflect the changes of the table component
* (dist/languages/english.json): Added additionnal fields
* (src/lib/crud.php): Added an exception to the search method to filter empty searches

## Version 22.04-dev Build: 66
* (dist/js/engine.js): Fixed an issue where if the search field had a search query, the timeline would still show all items upon rendering

## Version 22.04-dev Build: 65
* (dist/js/engine.js): Added search support to timelines
* (dist/js/engine.js): Fixed an issue with search on tables when value would be null

## Version 22.04-dev Build: 64
* (src/lib/cli.php): Fixed an issue with multi line request where a new empty line would not trigger the break

## Version 22.04-dev Build: 63
* (src/lib/cli.php): Added support for newline as a mean to end the multi line request

## Version 22.04-dev Build: 62
* (dist/languages/english.json): Added more fields
* (src/lib/api.php): Removed the search method
* (src/lib/cli.php): Cleared the test method
* (src/lib/crud.php): Added the search method
* (src/lib/crud.php): Fix the search method
* (src/lib/database.php): Added support for OR conditions in the prepare method

## Version 22.04-dev Build: 61
* (dist/js/engine.js): Added a table component to the Builder
* (dist/js/engine.js): Added a listing layout to the Builder's layout
* (dist/js/engine.js): Added support for the search in the listing and tables
* (dist/js/engine.js): Fixed debug data of Layouts
* (dist/js/engine.js): Added support for isAdministrator to isAllowed. If a user is Administrator, he has all rights
* (dist/js/engine.js): Fixed an issue with the Copy to Clipboard notification
* (dist/js/engine.js): Fixed an issue with the label of the checkbox component
* (dist/js/engine.js): Added CRUD - Read functions to all tables in GUI
* (dist/languages/english.json): Added a bunch fields
* (src/lib/api.php): Fixed an issue with the init method where if the application was not installed the $this->Auth->SQL->database->isConnnected() would not exist
* (src/lib/api.php): Started working on a search method. Which will be moved into the CRUD class
* (src/lib/auth.php): Added support for isAdministrator to isAllowed. If a user is Administrator, he has all rights
* (src/lib/cli.php): Added :Q to the options to end the multi line request
* (src/lib/crud.php): Fix an issue with the read method where it would return an error when no result were found
* (src/lib/crud.php): Added a headers method to retrieve the headers of a table
* (src/lib/database.php): Fixed an issue with the read method where it would try to access the Auth class which is not available and required to the database class.
* (src/lib/database.php): Trying to add support for multiple conditions in the prepare method
* (src/lib/installer.php): Removed unnecessary permissions to the Administrators Role

## Version 22.04-dev Build: 60
* (dist/css/colors.css): Fix an issue with transparent backgrounds where text-color would always be set to gray-700
* (dist/js/engine.js): Dashboard layout created
* (dist/js/engine.js): plugin support planned for the dashboard layout
* (dist/js/engine.js): widget templates suported in dashboard layout
* (dist/js/engine.js): created the infoBox template
* (dist/js/engine.js): created some example widgets (newUsers,newTickets,openTickets,closedTickets)
* (dist/js/engine.js): Successfull request toast are now turned off by default
* (dist/js/engine.js): Added plugin support for the details layout
* (dist/js/engine.js): Created a timeline tab for the details layout
* (dist/js/engine.js): The dashboard will now automatically open on landing
* (dist/languages/english.json): Added multiple text fields
* (src/lib/database.php): Improve the prepare method by modifying the default condition statement to transform everything to uppercase for searches.
* (src/lib/database.php): Added support for earlierThen and olderThen conditions

## Version 22.04-dev Build: 59
* (dist/js/engine.js): Updated the SweetAlert2 JS Library
* (dist/js/engine.js): Added SweetAlert2 templates. Available in Engine.Toast.
* (dist/js/engine.js): Completed the profile view and settings
* (dist/js/engine.js): Completed the application settings view
* (dist/js/engine.js): Added a Form property with several functions to save form content to cookies.
* (dist/js/engine.js): Added a Cookie property with several functions to manage cookies.
* (dist/js/engine.js): Added a Debug property with several functions and properties to centralize debugging information and functions.
* (dist/js/engine.js): A circled ! will now appear whenever debugging is turn on.
* (dist/js/engine.js): Added theme colors. Available in Engine.Colors.
* (dist/js/engine.js): Added a reload function in Engine.reload(). This will simply refresh the page and will not keep any url parameters.
* (dist/js/engine.js): Added a Layout property with several functions and properties to manage the graphical user interface.
* (dist/js/engine.js): Added a load function in Engine.Layout to easily load a new layout in the graphical user interface.
* (dist/languages/english.json): Added several missing fields
* (src/lib/api.php): Added a method getSettings to retreive the application settings
* (src/lib/api.php): Added a method saveSettings to validate and save new application settings.
* (src/lib/api.php): Streamlined the init method
* (src/lib/api.php): Added a property Tables to save the list of tables and provide it to the javascript Engine. In preparation of a basic CRUD system.
* (src/lib/cli.php): Added support for multi line input to the request method.
* (src/lib/cli.php): Added updates to CHANGELOG.md in the publish method.
* (src/lib/cli.php): Added updates to README.md in the publish method.
* (src/lib/cli.php): Updated method enable to support languages.
* (src/lib/cli.php): updated method disable to support languages.
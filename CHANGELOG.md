# ChangeLog

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
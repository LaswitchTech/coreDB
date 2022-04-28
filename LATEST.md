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

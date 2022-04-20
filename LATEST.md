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

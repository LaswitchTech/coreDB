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

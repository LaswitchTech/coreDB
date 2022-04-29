## Version 22.04-dev Build: 120
* (LICENSE): Change license to GNU GPLv3
* (TASKS.md): Updated Tasks list
* (dist/css/stylesheet.css): Added support for various bootstrap switches sizes
* (dist/js/engine.js): Increased the size of the profile status button
* (dist/js/engine.js): Added a notification tab to the profile so the user can control his notifications
* (dist/js/engine.js): Fixed some responsive issues with the details layout
* (dist/js/engine.js): Added a component switch to forms
* (dist/js/engine.js): Added shadows to timeline
* (dist/js/engine.js): Fixed an issue with the init method of Helper that would not accept multiple parameters at once. Meaning a single plugin could not create multiple notification types for exemple.
* (src/lib/api.php): Fixed an issue with the init method of Helper that would not accept multiple parameters at once. Meaning a single plugin could not create multiple notification types for exemple.
* (src/lib/notification.php): Change the notification type SQL to Application. This is meant so that users will better understand the type of notifications

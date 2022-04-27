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

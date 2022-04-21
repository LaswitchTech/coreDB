## Version 22.04-dev Build: 102
* (TASKS.md): Updated tasks list
* (dist/js/engine.js): Fixed an issue with the toCSV Helper. There was a leftover line from previous code.
* (dist/js/engine.js): Fixed an issue with table search where if the number of results were less then 10 and the results were at the end of the table, the table would not properly render the search results and pagination.
* (dist/js/engine.js): Fixed an issue caused when renaming the header in the application layout to navbar. This caused several navbar elements to stop working due to references to the old Engine.Layout.header.
* (dist/js/engine.js): Fixed the padding of the sidebar dropdowns to make them easy to click or press.
* (dist/languages/english.json): Added new fields
* (src/lib/database.php): Fixed an issue in the prepare method that would cause the conditions to use the value as column name instead of the name of the column

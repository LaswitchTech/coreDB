# Toast

The JavaScript Engine includes the Toast Object containing functions to control the alert notifications.

| Function | Description | Argument(s) | Code |
| -------- | ----------- | ----------- | ---- |
| save | This toast can be used to get a confirmation from the user to save something. | successCallback, deniedCallback | ```Engine.Toast.save()``` |
| delete | This toast can be used to confirm the deletion of a record by a user. | successCallback | ```Engine.Toast.delete()``` |
| success | This will display the Success Toast alert with the dataset "success" property. | dataset | ```Engine.Toast.success()``` |
| warning | This will display the Warning Toast alert with the dataset "warning" property. | dataset | ```Engine.Toast.warning()``` |
| error | This will display the Error Toast alert with the dataset "error" property. | dataset | ```Engine.Toast.error()``` |
| report | Mostly used to report debug log in the console. | dataset | ```Engine.Toast.report()``` |

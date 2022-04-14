# Auth
The JavaScript Engine includes some functions and properties for authentication handling.

## Engine.Auth.isLogin
This property contains a boolean value of the user's login status.

## Engine.Auth.logout()
This function logout the authenticated user.

## Engine.Auth.isAllowed()
This function is used to test if the logged in user has a specific premission. Testing permission is simple.
```Javascript
Engine.Auth.isAllowed([Permision], [Level])
```
The default level value is 1. So if you do not provide a custom level, your permission will be tested as boolean. So either true or false.

Adding a custom level allows you to support granular permissions. For example, CRUD functions could be set as such:
* 0 - No access
* 1 - ```Engine.Auth.isAllowed(myTable)``` - Read access
* 2 - ```Engine.Auth.isAllowed(myTable, 2)``` - Create access
* 3 - ```Engine.Auth.isAllowed(myTable, 3)``` - Update access
* 4 - ```Engine.Auth.isAllowed(myTable, 4)``` - Delete access

Note that in the case of Read access I did not specify the level requirement. This is because the default value of the level is 1.
If a permission does not exist or is not associated with the user, isAllowed will return false.

## Administrators
Any user with permission isAdministrator set to 1 / true is considered an Administrator and therefore has no restrictions.

### Example

This example shows how you can identify an Administrator.
```Javascript
Engine.Auth.isAllowed('isAdministrator')
```

Engine.Auth.isAllowed('timeline'+Engine.Helper.ucfirst(item))
Engine.Auth.isAllowed('access'+Engine.Helper.ucfirst(table))
Engine.Auth.isAllowed('isAdministrator')

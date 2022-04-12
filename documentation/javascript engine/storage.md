# Storage
The JavaScript Engine includes a database handler to get and set data in the local database.
## Functions
| Function | Description | Argument(s) |
| -------- | ----------- | ----------- |
|get|This will return an object of the targeted keyPath in the object specified.| object,keyPath = null |
|set|This will save an object and its keys into the database| object,keyPath,value = null |
### GET
Here is how the function looks:
```javascript
Engine.Storage.get([Object],[keyPath]);
```
If the keyPath is provided as a string, the function will assume it is the key you want to get. So to get the object language.current, You can do:
```javascript
Engine.Storage.get('language','current');
```
```javascript
Engine.Storage.get('language',['current']);
```
And if the keyPath is an array. You can go down multiple levels. So to get the object language.fields.Initiated, You can do:
```javascript
Engine.Storage.get('language',['fields','Initiated']);
```
If the keyPath or object does not exist, the function will return and empty object.
### SET
Here is how the function looks:
```javascript
Engine.Storage.set([Object],[keyPath],[value]);
```
If the keyPath is provided as a string, the function will assume it is the key you want to set. So to set the object language.current, You can do:
```javascript
Engine.Storage.set('language','current','english');
```
```javascript
Engine.Storage.set('language',['current'],'english');
```
And if the keyPath is an array. You can go down multiple levels. So to set the object language.fields.Initiated, You can do:
```javascript
Engine.Storage.set('language',['fields','Initiated'],'Initiated');
```
Note: If the keyPath provided does not exist, the function will create it.

Now if you do not provide a keyPath, the function will assume you are looking to set the actual object. So you can also do this:
```javascript
Engine.Storage.set('language',{current:'english'});
```
You can also store json values. The set function will simply convert them back to objects.
```javascript
Engine.Storage.set('language','{current:'english'}');
```

# Translate
The JavaScript Engine includes a translation tool.
## Usage
```javascript
Engine.Translate([field]);
```
If a language field does not exist, it will simply return the requested field. Thus your application will always have text fields. If you have debug enabled, the unknown field will be logged in the console.

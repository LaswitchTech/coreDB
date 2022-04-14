# Request
The JavaScript Engine includes a request function to make it simpler for you to exchange data between the server and the client.

## Warning
This function should not be modified because of the integration within the Engine. Be careful if you make modifications this function.

## Basics
Some important thing to know are that the fault api is ```API``` and the default method is ```init```. Therefore, if they are not provided, they will set to defaults. Here is the request itself:
```javascript
Engine.request([API],[METHOD],[OPTIONS],[CALLBACK]).then([PROMISE]);
```
## Options
| Key | Description | Default | Debug |
| --- | ----------- | ------- | ----- |
|toast|This controls whether or not to display a toast alert|false|false|
|pace|This controls whether or not to initialize the PACE loader|true|true|
|report|This controls whether or not to log the Engine output into the console|false|true|
|data|This contains additional data that may be sent over to the Engine|unset|unset|
## Requesting data
```javascript
Engine.request([API],[METHOD],[OPTIONS],function(dataset){
  // You can access the Engine diagnostic data
  console.log(dataset);
}).then(function(dataset){
  // You can access the Engine output from here
  console.log(dataset);
});
```
### Examples
These will request the ```API``` class and execute the ```init``` method.
```javascript
Engine.request();
```
```javascript
Engine.request('API','init');
```
This is how you can handle the ```API``` response.
```javascript
Engine.request('API','init',function(dataset){
  // You can access the Engine diagnostic data
  console.log(dataset);
}).then(function(dataset){
  // You can access the Engine output from here
  console.log(dataset);
});
```
This is how you can pass data to your ```API```.
```javascript
Engine.request('settings','save',{data:{language:'english'}});
```
This is how you can customize the request.
```javascript
// Turning pace.js off
Engine.request({pace:false});
// Turn off all toast
Engine.request({toast:false});
// Turn off the report toast
Engine.request({report:false});
```

# Debug
This property contains properties and functions for the built-in debugger.

## Getting the status of the debugger
```javascript
Engine.Debug.status
```

## Enable and Disable the debugger
Firstly, the debugger will automatically be turned on if the application debug switch is set to true. Now here is how you can do it manually:
```javascript
// To enable:
Engine.Debug.enable()

// To disable:
Engine.Debug.disable()
```

## Rendering the debugger
This can be done like this:
```javascript
Engine.Debug.render();
```

## Debugger data
There is several properties used to store useful debugging information.
```javascript
Engine.Debug.request.parameters // Contains the parameters used for the last API request
Engine.Debug.request.dataset // Contains the dataset from the last API request
```
To access these in realtime, simply click on the debug icon. This icon is automatically displayed blinking when the debugger is enabled.

## Console.log output
The Engine.Debug.logger contains the logger. The logger is used to control console output. Disabling it will prevent all console output.

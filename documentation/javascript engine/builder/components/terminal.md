# Terminal
A simple terminal box.

## Basics
Here is how you can generate a terminal box. The terminal box always scrolls to the bottom if height is limited. Additional properties and functions are made available to the callback function.
```javascript
Engine.Builder.components.terminal([CONTENT],[OPTIONS],[CALLBACK]);
```

## Options
Currently no options available for this component.
| Key | Description | Default | Debug |
| --- | ----------- | ------- | ----- |

## Properties
| Property | Description |
| -------- | ----------- |
| id | Contains the id of the main DOM element. |

## Functions
| Function | Description | Objects |
| -------- | ----------- | ------- |
| setContent | This function changes the content of the terminal a formats it to support both tabs and newline. | text |

## Examples
Basic terminal box:
```javascript
Engine.Builder.components.terminal().appendTo('body');
```
Changing the terminal box height:
```javascript
Engine.Builder.components.terminal('Hello World!',function(terminal){
  terminal.addClass('mt-4').css("overflow-y", "scroll").css("max-height", "300px");
}).appendTo('body');
```
Changing the terminal box content
```javascript
Engine.Builder.components.terminal(function(terminal){
  terminal.setContent('Hello World!');
}).appendTo('body');
```

# Form
This property contains functions to track a save form content as they are being filled.

## Track
### Basics
Everything starts here. Initiating this function will add an event listener to every user input field that are currently available in the DOM. This new event trigger the save() function to save the content of the input. The forms data is then saved into a cookie. This function is quite useful if you a want to make sure that user's do not loose their work in progress.
```Javascript
Engine.Form.track()
```

## Restoring forms
Now restoring the form's data. Again, this function made easy only requires that you call it. It will then fetch the relevant cookie and start filling the forms available in the DOM.
```Javascript
Engine.Form.restore()
```

## Form validation
Alright now we need something for form validation. The Form property includes a validate property with various functions to validate specific types of data. This property can also easily be expand later.
```Javascript
// Validate emails
Engine.Form.validate.email('user@domain.com')

// Validate passwords
Engine.Form.validate.password('Password123!')
// You can also set a minimum length
Engine.Form.validate.password('Password123!', 12)
```

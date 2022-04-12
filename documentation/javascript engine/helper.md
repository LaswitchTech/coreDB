# Helper

The JavaScript Engine includes a bunch of Helper functions that can make your programming a little simpler.

| Function | Description | Argument(s) | Code | Return |
| -------- | ----------- | ----------- | ---- | ------ |
| isJson | This function returns true if the string is a JSON string. Otherwise it returns false. | string | ``` Engine.Helper.isJson('{"key1":"value1"}')``` | ```true``` |
| parse | This function returns an object if you supply a JSON string. Otherwise it returns the original string. | string | ``` Engine.Helper.parse('{"key1":"value1"}')``` | ```{key1:"value1"}``` |
| encode | This function encodes your array in JSON, Base64 and URL. This can be useful if you want to create friendly URLs. | array or object | ``` Engine.Helper.encode({key1:"value1"})``` | ```'eyJrZXkxIjoidmFsdWUxIn0%3D'``` |
| decode | This decodes a string previously encoded by the encode Helper or the PHP API. | string | ``` Engine.Helper.decode('eyJrZXkxIjoidmFsdWUxIn0%3D')``` | ```{key1:"value1"}``` |
| formatURL | This converts and array into a friendly URL. Encoding the array in the process. | array or object | ``` Engine.Helper.formatURL({key1:"value1",key2:"value2"})``` | ```'key1=InZhbHVlMSI%3D&key2=InZhbHVlMiI%3D'``` |
| copyToClipboard | This function copies the provided string into the user's clipboard. | string | ``` Engine.Helper.copyToClipboard('Hello World!')``` ||
| toCSV | This function converts an array or object into a CSV string. | array or object, options | ``` Engine.Helper.toCSV({key1:"value1",key2:"value2"})``` | ```'value1,value2'``` |
| toString | This function converts a date object into a string. | date object | ``` Engine.Helper.toString()``` |  |
| html2text | This function converts html to text. | html | ``` Engine.Helper.html2text('<span>Hello World!</span>')``` | ```'Hello World!'``` |
| htmlentities | This function encodes an object to html | object | ``` Engine.Helper.htmlentities({string:'Copyright © Company'})``` | ```{string: 'Copyright &copy; Company'}``` |
| ucfirst | This function set the first character of the provided string to an uppercase | string | ``` Engine.Helper.ucfirst('hello world!')``` | ```'Hello world!'``` |
| clean | This function replaces symbols that are usually reserved for spaces. For example in the case of an username. | string | ``` Engine.Helper.clean('john.doe')``` | ```'john doe'``` |
| isOdd | This function true if the integer provided is odd. | integer | ``` Engine.Helper.isOdd(3)``` | ```true``` |
| trim | This function trims a string from the specified character. | string, character | ``` Engine.Helper.trim(';username;;',';')``` | ```'username'``` |
| isInt | This function true if the string provided is an integer. | string | ``` Engine.Helper.isInt(1)``` | ```true``` |
| padNumber | This function adds leading 0s to a number until the length is reached. | number, length | ``` Engine.Helper.padNumber(8901,8)``` | ```'00008901'``` |
| padString | This function adds the leading character(s) to a string until the length is reached. | string, length, character | ``` Engine.Helper.padString(' World!',10,'Hello')``` | ```'Hel World!'``` |
| set | This function will set a Value to the object Key and will create the path if it does not exist. | object, keyPath, value | ``` Engine.Helper.set(myObject,['string'],'Hello World!')``` |  |
| isSet | This function will test if the given key path exist in the object. | object, keyPath | ``` Engine.Helper.isSet(myObject,['string'])``` | ```true``` |
| addZero | This will format a number to '00' | string or integer | ``` Engine.Helper.addZero(8)``` | ```'08'``` |
| now | This function will format a date into a another format. | type = 'UTF8' | ``` Engine.Helper.now()``` |  |
| getUrlVars | This function will return all URL parameter into an object | [none] | ``` Engine.Helper.getUrlVars()``` | ```{p: 'dashboard'}``` |
| getFileSize | This function converts bytes into human readable file sizes. | bytes | ``` Engine.Helper.getFileSize(1234567890)``` | ```'1.1 GiB'``` |
| isFuture | This function returns true if the date provided is in the future. | date | ``` Engine.Helper.isFuture()``` | ```true/false``` |
| download | This function will trigger a file download of the specified url and change the original filename to the filename if provided. | url, filename = null | ``` Engine.Helper.download('https://domain.com/logo.png')``` ||

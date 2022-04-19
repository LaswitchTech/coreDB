## Version 22.04-dev Build: 91
* (dist/js/engine.js): Added a log function to Engine.Debug.logger. This function provides a lot more debug data to the standard console.log. Such as complete tracing and datetime.
* (dist/js/engine.js): Fixed an issue with the encoder and decoder functions in Engine.Helper. This fixes the issue where some language symbole would not encode properly and thus return false
* (dist/languages/francais.json): Now generated using Google Translate API
* (src/lib/api.php): Added support for Google Translate API
* (src/lib/cli.php): Added a method translate to translate all language fields to various languages

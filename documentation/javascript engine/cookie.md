# Cookie
This property contains functions to manage browser cookies. It includes 3 functions, create, read and delete.

## Create
### Basics
To create a new cookie, all you need is a name and something to store.
```Javascript
Engine.Cookie.create([name],[object],[days] = 30)
```
### Example
```Javascript
Engine.Cookie.create('cart',[{itemNo:'100000',itemDesc:'Tenis ball',itemQty:2,itemUnitPrice:12.99,total:25.98}])
```

## Read
### Basics
To read a cookie all you need is its name.
```Javascript
Engine.Cookie.read([name])
```
### Example
```Javascript
Engine.Cookie.read('cart')
// Return [{itemNo:'100000',itemDesc:'Tenis ball',itemQty:2,itemUnitPrice:12.99,total:25.98}]
```

## Delete
### Basics
To delete a cookie all you need is its name.
```Javascript
Engine.Cookie.delete([name])
```
### Example
```Javascript
Engine.Cookie.delete('cart')
```

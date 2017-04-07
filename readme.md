# Parcel - Request Component
The request component loads all of the data about the request so it can easily be managed from a single class.

### Initializing

```$php
$request = new \Parcel\Request\Request();
```

### Retrieving data from the request

In order to retrieve a value, you must use the `$request->Get()` method. This method will look through the GET, POST, FILES, and SERVER variables, in that order, for the value that you're looking for.

```
use \Parcel\Request\Request;
 
$request = new Request();
 
$action = $request->Get( 'action', 'view' );
 
if ( $action == 'delete' ) {
     // Delete some stuff here
}
```

## Filtering request data
Now, what if there were two `action` variables in the request. One in the `$_GET` variable, and the other in the `$_POST` variable. Since the `$request->Get()` method looks up variables in the order of GET, POST, FILES, and then SERVER, it would always return the first one found.

That means we need to specify that we are looking in the `$_POST` variable. We can do that by assigning a specific filter.

```
use \Parcel\Request\Request;
 
$request = new Request();
 
$action = $request->Get( 'action', null, Request::FILTER_POST );
 
if ( $action == 'Submit' ) {
     // Submit your data here
}
```

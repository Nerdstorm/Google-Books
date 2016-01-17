# Google Books API wrapper
This is a PHP client library for interfacing with the public Google Books API.

## Getting started
Google Books API can be used only with a valid API key generated using the 
[https://console.developers.google.com](Google Developer Console).

Once the API key is generated you can start using the library.

Initialise a `VolumeSearch` class which has functions required to call the API endpoints, then use the `LookupManager`
to call specific lookup functions to search volumes.
```
$volume_search_api = new VolumesSearch('API_KEY');
$lookup_manager = new VolumeLookupManager($volume_search_api);
```

## Examples

##### Lookup `Volumes` using the volume title
```
$volumes = $lookup_manager->lookupByTitle('Systems Analysis and Design');
```

##### Lookup `Volumes` by the author name
```
$volumes = $lookup_manager->lookupByAuthor('John Satzinger');
```

##### Find a `Volume` by volume ISBN
```
$volume = $lookup_manager->findByISBN('978-1111534158');
```

... refer to `VolumeLookupManager` for available lookup functions.
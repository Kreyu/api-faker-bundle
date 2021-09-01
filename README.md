# API Faker

A bit quirky way to create fake "API" for testing purposes.  

Imagine you have an application, that relies on multiple external APIs (e.g. for user authentication, fetching some data). Those APIs only have production environments, leaving you with no way of testing locally without connecting to them, and with stress of some data corruption on real-world users.

This bundle allows adding fake API endpoints to your application that you can connect to.    
Specified endpoints will return specified (optional) json.

## Installation

```
$ composer require --dev kreyu/api-faker-bundle
```

then, enable bundle in dev environment:

```php
# config/bundles.php

<?php

return [
    // ...
    Kreyu\Bundle\ApiFakerBundle\KreyuApiFakerBundle::class => ['dev' => true],
];
```

and add routing configuration (prefixes are optional):

```yaml
# config/routes/dev/kreyu_api_faker.yaml

kreyu_api_faker:
  resource: .
  type: kreyu_api_faker
  prefix: /fake-api # optional
  name_prefix: fake_api_ # optional
```

## Configuration reference

```yaml
# config/packages/dev/kreyu_api_faker.yaml

kreyu_api_faker:
  default_headers: # used on endpoints with response without specified headers 
    Content-Type: application/json # this header is added by default 
  applications:
    - prefix: /auth-api
      endpoints:
        - path: /login
          method: GET # GET by default
          response:
            status_code: 200 # by default 200 if given body content, 204 otherwise
            content_format: json # json (by default), xml, yaml, csv, null (serialization disabled)
            content: # no content by default
              foo: bar
              lorem: ipsum
            headers: # if not given, "default_headers" is used
              Content-Type: application/json
```

Above configuration will create a route `GET /fake-api/auth-api/login` named `fake_api_auth_api_login`, and return response with given status code and body content.

**Note**: After changing the package configuration, clear the cache.

**Tip**: Response content can be also a path to file, which contents will be used on request.

## FAQ

Q) Why not just create controllers manually for each API?  
A) Idk go ahead 

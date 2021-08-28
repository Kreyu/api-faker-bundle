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

## Configuration

```yaml
# config/packages/dev/kreyu_api_faker.yaml

kreyu_api_faker:
  applications:
    - name: Authentication API
      prefix: /auth-api
      endpoints:
        - path: /login
          method: POST
          response:
            status: 200
            body:
              token: totally_an_oauth_token
              sessionLifetime: 1629989970
```

You can also define response `body` as raw json string:

```yaml
# config/packages/dev/kreyu_api_faker.yaml

kreyu_api_faker:
  applications:
    - name: Authentication API
      prefix: /auth-api
      endpoints:
        - path: /login
          method: POST
          response:
            status: 200
            body: '{ "token": "totally_an_oauth_token", "sessionLifetime": 1629989970 }'
```

or even pass a path to a file:

```yaml
# config/packages/dev/kreyu_api_faker.yaml

kreyu_api_faker:
  applications:
    - name: Authentication API
      prefix: /auth-api
      endpoints:
        - path: /login
          method: POST
          response:
            status: 200
            body: '%kernel.project_dir%/resources/auth-api/login.json'
```

- if you skip the `response` section completely, then response will be `204 No Content` without body,
- if you skip the `status` in the `response`, then response status code will be `200 OK` or `204 No Content`, depending on the given `body`,
- if you skip the `body` in the `response`, then response will not return body

Endpoints of given path should be accessible right away. If not, clear your cache.

Above configuration will create a route `POST /fake-api/auth-api/login` named `fake_api_auth_api_login`, and return response with given status code and body.

## FAQ

Q) Why not just create controllers manually for each API?  
A) Idk go ahead 

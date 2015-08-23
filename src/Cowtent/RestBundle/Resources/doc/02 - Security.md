# Security Context

In case your ``application`` is ``workspace`` uncoupled, you'll be able to use security resources.

## User

### Create

**Endpoint** /api/user/create

**Request**

```json
{
  "username": "username_28",
  "email": "mymail@domain.tld",
  "password": "f9d+Mgj/a9"
}
```

**Response**

*To be defined*

### Update

TODO

### Change password

TODO

### Delete

TODO

## Application

### Create

**Endpoint** /api/application/create

**Request**

```json
{
  "name": "project1-preprod"
}
```

**Response**

```json
{
  "name": "project1-preprod",
  "api_key": "03b74930-46d2-11e5-8066-080027b9a9d8",
  "secret": "newpi9ddno08oso4c8css8goc8cgkks"
}
```

*To be validated*

### Update

### Reset Secret

**Endpoint** /api/application/resetSecret

**Request**

```json
{
  "api_key": "03b74930-46d2-11e5-8066-080027b9a9d8"
}
```

**Response**

```json
{
  "api_key": "03b74930-46d2-11e5-8066-080027b9a9d8",
  "secret": "newpi9ddno08oso4c8css8goc8cgkks"
}
```

*To be validated*

### Delete




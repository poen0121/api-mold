

<!-- START_PREPEND -->
## Authorization

You must register your service with provider, and provider will provide you with the information client_id and client_secrect required for login.

## Verification

Service authorization verification uses a JWT authentication token.

### Note :

<aside class="warning" style="color:#FFFFFF" >
When the access token expires, if you continue to call the service API, the request will automatically refresh the access token contained in the header authorization and return a new access token to you.
</aside>

## Error

When the request APIs fails, respond to related error messages.

### Response Body

success : false

error :

Parameter | Type | Description
--------- | ------- | ------- | -----------
status | INT | Http status code
code | INT | Error code
message | STR | Error message
description | OBJ | Other error descriptions


<!-- END_PREPEND -->
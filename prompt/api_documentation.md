# API Authentication Documentation

This document describes the API login mechanism and how to use the generated `api_token` for subsequent requests in the Payroll system.

### Postman Collections
The API collections can be found in the `prompt/` directory:
- [collection.json](file:///c:/laragon/www/new_payroll/prompt/collection.json) (Standard Postman Collection format)
- [collection_integration_employee.json](file:///c:/laragon/www/new_payroll/prompt/collection_integration_employee.json) (Alias file)

## 1. Authentication Flow

1. The client sends a `POST` request to `/api/login` containing user credentials.
2. If the credentials are valid, the server returns a JSON response containing `api_token`.
3. If the user doesn't have an `api_token` assigned yet, the system generates a new 60-character random string on the fly and saves it.
4. For all other API endpoints (which are protected by the `auth.api_token` middleware), the client must include this `api_token` using one of the three supported methods:
   - **Bearer Token (Recommended)**: Pass the token in the `Authorization` header as `Bearer <api_token>`.
   - **Custom Header**: Pass the token in the `X-API-TOKEN` header.
   - **Query Parameter**: Pass the token in the URL query string as `?api_token=<api_token>`.

---

## 2. API Reference

### Login Endpoint
Authenticates a user and retrieves/generates their API token.

* **URL**: `/api/login`
* **Method**: `POST`
* **Headers**:
  * `Accept`: `application/json`
  * `Content-Type`: `application/json`

#### Request Body
The request accepts `login` (which can be either the user's `email` or `username`), or explicitly `email`/`username` fields.

```json
{
    "login": "admin@payroll.com",
    "password": "password"
}
```

*OR*

```json
{
    "email": "admin@payroll.com",
    "password": "password"
}
```

*OR*

```json
{
    "username": "admin",
    "password": "password"
}
```

#### Success Response (200 OK)
Returns `api_token` directly in the response root and within the `user` object.

```json
{
    "success": true,
    "message": "Login successful",
    "api_token": "gQe1s2t3u4v5w6x7y8z9a0b1c2d3e4f5g6h7i8j9k0l1m2n3o4p5q6r7s8t9",
    "user": {
        "id": 1,
        "name": "Admin IT Pusat",
        "email": "admin@payroll.com",
        "username": "admin",
        "api_token": "gQe1s2t3u4v5w6x7y8z9a0b1c2d3e4f5g6h7i8j9k0l1m2n3o4p5q6r7s8t9",
        "id_permission": [99]
    }
}
```

#### Error Response - Invalid Credentials (401 Unauthorized)
```json
{
    "success": false,
    "message": "Invalid credentials"
}
```

#### Error Response - Validation Error (422 Unprocessable Content)
```json
{
    "success": false,
    "message": "Validation error",
    "errors": {
        "login": [
            "The login field is required when none of email / username are present."
        ],
        "password": [
            "The password field is required."
        ]
    }
}
```

---

## 3. Postman Integration

The Postman collection is configured with a **Test Script** on the Login request. When run, it automatically extracts `api_token` and updates the collection variable `token`:

```javascript
var response = pm.response.json();
if (response.api_token) {
    pm.collectionVariables.set("token", response.api_token);
}
```

All other requests in the collection use `Authorization: Bearer {{token}}` to authenticate automatically.

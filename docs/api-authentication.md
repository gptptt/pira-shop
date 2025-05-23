# API Authentication with Laravel Sanctum

This document outlines how API authentication is implemented in the platform using Laravel Sanctum.

## Overview

Laravel Sanctum provides a lightweight authentication system for SPAs (Single Page Applications), mobile applications, and simple token-based APIs. Sanctum allows each user of your application to generate multiple API tokens for their account. These tokens can be granted abilities or scopes which specify which actions the tokens are allowed to perform.

## Features Implemented

- Token-based API authentication
- Role-based access control for API endpoints
- Consistent API response format
- Proper error handling for API requests

## Authentication Flow

1. **Registration**: New users can register via `/api/register` endpoint
2. **Login**: Users can authenticate and receive a token via `/api/login` endpoint
3. **Protected Resources**: Access to protected resources using token authentication
4. **Logout**: Users can revoke their current token via `/api/logout` endpoint

## API Endpoints

| Method | Endpoint | Description | Authentication Required |
|--------|----------|-------------|------------------------|
| POST | `/api/register` | Register a new user | No |
| POST | `/api/login` | Login and get token | No |
| GET | `/api/user` | Get authenticated user details | Yes |
| POST | `/api/logout` | Logout (revoke token) | Yes |

## Usage Examples

### Registration

```http
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### Login

```http 
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123",
  "device_name": "iPhone 12"
}
```

Response:

```json
{
  "status": "success",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "roles": ["customer"]
  },
  "token": "1|a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6"
}
```

### Authenticated Request

```http
GET /api/user
Authorization: Bearer 1|a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6
Accept: application/json
```

### Logout

```http
POST /api/logout
Authorization: Bearer 1|a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6
Accept: application/json
```

## Role-Based Access Control

API routes can be protected based on user roles and permissions:

```php
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Admin-only routes
});

Route::middleware(['auth:sanctum', 'permission:manage-users'])->group(function () {
    // Routes for users with manage-users permission
});
```

## Integration with Frontend

For SPA integration:

1. Set `sanctum/csrf-cookie` as a path in your CORS configuration
2. Make sure `supports_credentials` is set to `true` in CORS config
3. Call `/sanctum/csrf-cookie` endpoint before making login request
4. Set `withCredentials: true` in your axios (or other HTTP client) configuration

## Error Handling

The API returns consistent error responses with appropriate HTTP status codes:

- 401 - Unauthenticated
- 403 - Unauthorized (doesn't have required role/permission)
- 404 - Resource not found
- 422 - Validation errors
- 500 - Server error 
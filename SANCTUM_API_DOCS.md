# Laravel Sanctum API Documentation

## Overview
This Laravel Libretto application implements Laravel Sanctum for API authentication with the following features:
- Token-based authentication
- Tokens expire after 24 hours (1 day)
- Single token per user (only one active token at a time)
- Automatic token expiration checking
- Token regeneration on login if current token is expired

## API Endpoints

### Base URL
```
http://127.0.0.1:8000/api
```

### Authentication Endpoints

#### 1. Register User
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

**Response:**
```json
{
    "message": "User registered successfully",
    "access_token": "1|aBcDeFgHiJkLmNoPqRsTuVwXyZ",
    "token_type": "Bearer",
    "expires_at": "2025-07-07T10:30:00.000000Z",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    }
}
```

#### 2. Login User
```http
POST /api/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response (New Token):**
```json
{
    "message": "Login successful",
    "access_token": "2|aBcDeFgHiJkLmNoPqRsTuVwXyZ",
    "token_type": "Bearer",
    "expires_at": "2025-07-07T10:30:00.000000Z",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    }
}
```

**Response (Existing Valid Token):**
```json
{
    "message": "Login successful - using existing token",
    "token_type": "Bearer",
    "expires_at": "2025-07-07T10:30:00.000000Z",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    },
    "note": "You already have a valid token. Use /api/refresh to get a new token if needed."
}
```

#### 3. Get User Details
```http
GET /api/user
Authorization: Bearer {token}
```

**Response:**
```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    },
    "token_expires_at": "2025-07-07T10:30:00.000000Z",
    "token_created_at": "2025-07-06T10:30:00.000000Z"
}
```

#### 4. Refresh Token
```http
POST /api/refresh
Authorization: Bearer {token}
```

**Response:**
```json
{
    "message": "Token refreshed successfully",
    "access_token": "3|aBcDeFgHiJkLmNoPqRsTuVwXyZ",
    "token_type": "Bearer",
    "expires_at": "2025-07-07T10:30:00.000000Z",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    }
}
```

#### 5. Logout (Current Device)
```http
POST /api/logout
Authorization: Bearer {token}
```

**Response:**
```json
{
    "message": "Logged out successfully"
}
```

#### 6. Logout All Devices
```http
POST /api/logout-all
Authorization: Bearer {token}
```

**Response:**
```json
{
    "message": "Logged out from all devices successfully"
}
```

#### 7. Get All User Tokens
```http
GET /api/tokens
Authorization: Bearer {token}
```

**Response:**
```json
{
    "tokens": [
        {
            "id": 1,
            "name": "auth_token",
            "abilities": ["*"],
            "expires_at": "2025-07-07T10:30:00.000000Z",
            "created_at": "2025-07-06T10:30:00.000000Z",
            "last_used_at": "2025-07-06T11:00:00.000000Z",
            "is_expired": false
        }
    ]
}
```

#### 8. Revoke Specific Token
```http
DELETE /api/tokens/{tokenId}
Authorization: Bearer {token}
```

**Response:**
```json
{
    "message": "Token revoked successfully"
}
```

### Resource Endpoints (Protected)

All resource endpoints require authentication. Include the token in the Authorization header:

```
Authorization: Bearer {your_token_here}
```

#### Books CRUD
- `GET /api/books` - List all books
- `POST /api/books` - Create a book
- `GET /api/books/{id}` - Get specific book
- `PUT /api/books/{id}` - Update book
- `DELETE /api/books/{id}` - Delete book

#### Authors CRUD
- `GET /api/authors` - List all authors
- `POST /api/authors` - Create an author
- `GET /api/authors/{id}` - Get specific author
- `PUT /api/authors/{id}` - Update author
- `DELETE /api/authors/{id}` - Delete author

#### Genres CRUD
- `GET /api/genres` - List all genres
- `POST /api/genres` - Create a genre
- `GET /api/genres/{id}` - Get specific genre
- `PUT /api/genres/{id}` - Update genre
- `DELETE /api/genres/{id}` - Delete genre

#### Reviews CRUD
- `GET /api/reviews` - List all reviews
- `POST /api/reviews` - Create a review
- `GET /api/reviews/{id}` - Get specific review
- `PUT /api/reviews/{id}` - Update review
- `DELETE /api/reviews/{id}` - Delete review

### Test Endpoints

#### Test API Connection
```http
GET /api/test
```

#### Test Authentication
```http
GET /api/test-auth
Authorization: Bearer {token}
```

## Token Expiration Logic

1. **Token Creation**: Every token is created with a 24-hour expiration time
2. **Login Behavior**: 
   - If user has a valid (non-expired) token, login returns existing token info
   - If user has expired tokens, they are deleted and a new token is created
3. **Automatic Expiration Check**: Every protected route checks token expiration
4. **Expired Token Response**: 
   ```json
   {
       "message": "Token has expired. Please login again.",
       "error": "TOKEN_EXPIRED"
   }
   ```

## Error Responses

### 401 Unauthorized
```json
{
    "message": "Unauthenticated."
}
```

### 422 Validation Error
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

### 404 Not Found
```json
{
    "message": "Token not found"
}
```

## Usage Examples

### Using with JavaScript/Fetch
```javascript
// Login
const response = await fetch('http://127.0.0.1:8000/api/login', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        email: 'john@example.com',
        password: 'password123'
    })
});

const data = await response.json();
const token = data.access_token;

// Use token for protected routes
const booksResponse = await fetch('http://127.0.0.1:8000/api/books', {
    headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
    }
});
```

### Using with cURL
```bash
# Login
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@example.com","password":"password123"}'

# Use token
curl -X GET http://127.0.0.1:8000/api/books \
  -H "Authorization: Bearer 1|aBcDeFgHiJkLmNoPqRsTuVwXyZ"
```

## Security Features

1. **Token Hashing**: Tokens are hashed in the database
2. **Expiration Enforcement**: Automatic token expiration checking
3. **Single Token Policy**: Only one active token per user
4. **Secure Headers**: CSRF protection for stateful requests
5. **Rate Limiting**: API throttling enabled

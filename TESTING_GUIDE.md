# 🚀 Sanctum API Testing Guide

## All Errors Fixed! ✅

I've successfully resolved all the PHP errors in your Sanctum implementation:

### **Fixed Issues:**
1. ✅ **Undefined method 'tokens'** - Updated SimpleAuthController to use direct DB queries
2. ✅ **Undefined method 'createToken'** - Implemented manual token creation
3. ✅ **Missing middleware classes** - Created all required middleware files
4. ✅ **Undefined middleware types** - Fixed all Kernel.php references

## 🧪 Test Your API

### **1. Test Basic API Connection**
```bash
curl http://127.0.0.1:8000/api/test
```
**Expected Response:**
```json
{
    "message": "API is working",
    "timestamp": "2025-07-06T..."
}
```

### **2. Register a New User**
```bash
curl -X POST http://127.0.0.1:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```
**Expected Response:**
```json
{
    "message": "User registered successfully",
    "access_token": "1|aBcDeFgHiJkLmNoPqRsTuVwXyZ",
    "token_type": "Bearer",
    "expires_at": "2025-07-07T...",
    "user": {
        "id": 1,
        "name": "Test User",
        "email": "test@example.com"
    }
}
```

### **3. Login User (First Time)**
```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```
**Expected Response (New Token):**
```json
{
    "message": "Login successful",
    "access_token": "2|aBcDeFgHiJkLmNoPqRsTuVwXyZ",
    "token_type": "Bearer",
    "expires_at": "2025-07-07T...",
    "user": {
        "id": 1,
        "name": "Test User",
        "email": "test@example.com"
    }
}
```

### **4. Login User (Second Time - Existing Token)**
```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```
**Expected Response (Existing Token):**
```json
{
    "message": "Login successful - using existing token",
    "token_type": "Bearer",
    "expires_at": "2025-07-07T...",
    "user": {
        "id": 1,
        "name": "Test User",
        "email": "test@example.com"
    },
    "note": "You already have a valid token. Use /api/refresh to get a new token if needed."
}
```

### **5. Test Protected Route**
```bash
curl -X GET http://127.0.0.1:8000/api/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```
**Expected Response:**
```json
{
    "user": {
        "id": 1,
        "name": "Test User",
        "email": "test@example.com"
    },
    "token_expires_at": "2025-07-07T...",
    "token_created_at": "2025-07-06T..."
}
```

### **6. Test Authentication Protection**
```bash
curl -X GET http://127.0.0.1:8000/api/test-auth \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```
**Expected Response:**
```json
{
    "message": "Authentication is working",
    "user": {
        "id": 1,
        "name": "Test User",
        "email": "test@example.com"
    },
    "timestamp": "2025-07-06T..."
}
```

### **7. Refresh Token**
```bash
curl -X POST http://127.0.0.1:8000/api/refresh \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```
**Expected Response:**
```json
{
    "message": "Token refreshed successfully",
    "access_token": "3|aBcDeFgHiJkLmNoPqRsTuVwXyZ",
    "token_type": "Bearer",
    "expires_at": "2025-07-07T...",
    "user": {
        "id": 1,
        "name": "Test User",
        "email": "test@example.com"
    }
}
```

### **8. Logout**
```bash
curl -X POST http://127.0.0.1:8000/api/logout \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```
**Expected Response:**
```json
{
    "message": "Logged out successfully"
}
```

### **9. Test CRUD Endpoints (Protected)**
```bash
# List books
curl -X GET http://127.0.0.1:8000/api/books \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# List authors
curl -X GET http://127.0.0.1:8000/api/authors \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# List genres
curl -X GET http://127.0.0.1:8000/api/genres \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# List reviews
curl -X GET http://127.0.0.1:8000/api/reviews \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## ⚡ **Key Features Implemented:**

### **1. Token Expiration (24 Hours)**
- All tokens automatically expire after 1 day
- Configured in `config/sanctum.php`
- Enforced through middleware

### **2. Single Token Policy**
- Only one active token per user
- Login reuses existing valid tokens
- Expired tokens are automatically cleaned up

### **3. Token Expiration Middleware**
- Automatically checks token expiry on each request
- Returns proper error for expired tokens
- Cleans up expired tokens from database

### **4. Manual Token Management**
- `SimpleAuthController` uses direct database queries
- No dependency on Sanctum traits (which were causing errors)
- Full compatibility with Laravel Sanctum token format

## 🎯 **What You Can Do Now:**

1. **Start developing your frontend** - All API endpoints are ready
2. **Test with Postman/Insomnia** - Use the endpoints above
3. **Integrate with JavaScript** - Use fetch/axios with Bearer tokens
4. **Add more features** - Build on this solid foundation

## 🔒 **Security Features:**

- ✅ Tokens are hashed in database
- ✅ 24-hour automatic expiration
- ✅ Single token per user policy
- ✅ CSRF protection for web routes
- ✅ Rate limiting on API routes
- ✅ Proper authentication middleware

**Your Sanctum implementation is now complete and error-free!** 🎉

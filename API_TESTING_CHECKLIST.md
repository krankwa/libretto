# Laravel API Testing Checklist

## ✅ **Before Testing API:**

### 1. **Start Laravel Server:**
```bash
C:\php\php.exe artisan serve
```
**Expected Output:**
```
Starting Laravel development server: http://127.0.0.1:8000
[Sat Jul  6 10:30:00 2024] PHP 8.3.15 Development Server (http://127.0.0.1:8000) started
```

### 2. **Verify Basic Connectivity:**
**URL:** `GET http://127.0.0.1:8000/api/test`
**Expected Response:**
```json
{
    "message": "API is working",
    "timestamp": "2025-07-06T..."
}
```

### 3. **Check Routes:**
```bash
C:\php\php.exe artisan route:list --path=api
```

### 4. **Run Migrations (if needed):**
```bash
C:\php\php.exe artisan migrate
```

## 🧪 **Test API Register:**

**Method:** POST
**URL:** `http://127.0.0.1:8000/api/register`
**Headers:**
```
Content-Type: application/json
```
**Body:**
```json
{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Expected Response (201):**
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

## 🚨 **If Getting 404:**

1. **Check server is running** at http://127.0.0.1:8000
2. **Verify URL is correct:** `/api/register` (not `/register`)
3. **Check route exists:** `php artisan route:list`
4. **Clear cache:** `php artisan config:clear`
5. **Restart server**

## 🎯 **Most Common Issue:**
Server not running! Make sure you see "Development Server started" message.

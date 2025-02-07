# Backend API Documentation

## Overview

This backend application provides platform blog (PlatBlog) API endpoints for user authentication, post management, and comment interactions. It uses Laravel Sanctum for authentication and follows RESTful principles.

---

## Database Schema

### **Posts Table**

| Column Name | Type        | Description             |
| ----------- | ----------- | ----------------------- |
| id          | BIGINT      | Primary key             |
| title       | STRING      | Post title              |
| body        | TEXT        | Post content            |
| author_id   | FOREIGN KEY | References `users.id`   |
| created_at  | TIMESTAMP   | Post creation timestamp |
| updated_at  | TIMESTAMP   | Post update timestamp   |

### **Comments Table**

| Column Name | Type        | Description                |
| ----------- | ----------- | -------------------------- |
| id          | BIGINT      | Primary key                |
| body        | TEXT        | Comment content            |
| post_id     | FOREIGN KEY | References `posts.id`      |
| user_id     | FOREIGN KEY | References `users.id`      |
| created_at  | TIMESTAMP   | Comment creation timestamp |
| updated_at  | TIMESTAMP   | Comment update timestamp   |

---

## API Endpoints

### **Authentication**

-   **POST** `/login` - Authenticate user and return token
-   **POST** `/register` - Register a new user
-   **POST** `/logout` - Logout authenticated user (requires token)
-   **GET** `/user` - Retrieve authenticated user details

### **Post Management**

-   **GET** `/posts` - List all posts
-   **GET** `/posts/{post}` - Get a single post by ID
-   **POST** `/posts` - Create a new post (auth required)
-   **PUT** `/posts/{post}` - Update a post (auth required)
-   **DELETE** `/posts/{post}` - Delete a post (auth required)

### **Comment Management**

-   **GET** `/posts/{post}/comments` - Get comments for a specific post
-   **GET** `/posts/{post}/{comment}` - Get a specific comment
-   **POST** `/posts/{post}/comments` - Add a comment to a post (auth required)
-   **PUT** `/posts/{post}/comments/{comment}` - Update a comment (auth required)
-   **DELETE** `/posts/{post}/comments/{comment}` - Delete a comment (auth required)
-   **GET** `/comments` - List all comments

### **Fallback Route**

-   Returns a 404 error message if the route is not found.

---

## Response Status Codes

-   `200 OK` - Successful request
-   `201 Created` - Resource created successfully
-   `401 Unauthorized` - Authentication required
-   `403 Forbidden` - Access denied
-   `404 Not Found` - Resource not found

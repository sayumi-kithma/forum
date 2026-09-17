# Campus Forum

A simple discussion forum built with PHP, MySQL, and Bootstrap. Users can register, log in, create discussion threads within categories, and reply to existing threads.

## Features

- User registration and login (passwords hashed with bcrypt)
- Discussion categories: Course Discussion, General Discussion, Help & Support
- Thread creation and replies
- Server-side and client-side form validation

## Why I built this

[Write 2-3 sentences: was this for a course, to learn PHP/MySQL, both?]

## Tech stack

- **Front end:** HTML, Bootstrap 5, vanilla JavaScript
- **Back end:** PHP (PDO for database access)
- **Database:** MySQL, 4 tables (users, categories, threads, posts)

## Setup

1. Install XAMPP (or WAMP/MAMP) and start Apache + MySQL
2. Copy this folder into your server's `htdocs` directory
3. Import `schema.sql` via phpMyAdmin
4. Visit `localhost/forum/index.php`

## What I'd add next

[List 2-3 things you'd genuinely want to add - search, edit/delete posts, pagination, etc.]

## What I learned

[1-2 sentences on something specific you ran into and solved - e.g. debugging a MySQL config issue, understanding prepared statements, session-based auth]

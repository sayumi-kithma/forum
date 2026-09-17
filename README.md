# Campus Forum — SE102.3 Reference Project

A minimal working forum app: register/login, categories, threads, replies.
Built with PHP (PDO + MySQL), Bootstrap 5, and vanilla JS validation.

## This is a starting point, not the final submission

This gives you a working skeleton that satisfies the *core* technical
requirements in the brief. To meet the "40+ hours of dedicated work" and
"deep understanding" expectations, your group should:

- Extend it: search, categories management, edit/delete posts, pagination,
  avatars, AJAX-based posting (optional per the brief), a moderation role, etc.
- Refactor/restyle it to reflect your own design decisions.
- Set up a Git repository from day one and commit incrementally — the report
  needs a commit history / repo link, and "wrote it all in one sitting the
  night before" will be obvious in the log.
- Make sure every member can explain how authentication, the database
  schema, and the request flow work — the demo is 10 minutes and everyone
  is expected to understand the whole system, not just their part.

## Setup (XAMPP / WAMP / MAMP)

1. Install XAMPP (or similar) and start Apache + MySQL.
2. Copy the `forum` folder into your server's web root
   (e.g. `htdocs/forum` for XAMPP).
3. Open phpMyAdmin (`http://localhost/phpmyadmin`), create nothing manually —
   just go to the **Import** tab and import `schema.sql`. This creates the
   `forum_db` database and all tables, plus 3 starter categories.
4. Open `config.php` and check `DB_USER` / `DB_PASS` match your MySQL setup
   (defaults `root` / empty password work for most XAMPP installs).
5. Visit `http://localhost/forum/index.php` in your browser.
6. Register an account, log in, and create a thread to test.

## File structure

```
forum/
├── config.php          # DB connection + session helpers
├── schema.sql           # Database schema + seed categories
├── index.php             # Category list (homepage)
├── register.php          # User registration
├── login.php             # User login
├── logout.php            # Destroys session
├── category.php          # Threads within a category
├── new_thread.php         # Create a new thread
├── thread.php             # View thread + post/read replies
├── includes/
│   ├── header.php         # Shared nav + head
│   └── footer.php         # Shared scripts + closing tags
└── assets/
    ├── css/style.css      # Custom styling on top of Bootstrap
    └── js/validation.js   # Client-side form validation
```

## How the pieces fit together (useful for your report's Design section)

- **Front-end**: Bootstrap 5 (via CDN) for layout/components; `validation.js`
  intercepts form submits to check required fields client-side before the
  request even reaches PHP.
- **Back-end**: Every page is a PHP script that talks to MySQL through PDO
  with **prepared statements** (prevents SQL injection). Sessions
  (`$_SESSION`) track the logged-in user across pages.
- **Database**: 4 tables — `users`, `categories`, `threads`, `posts` — linked
  by foreign keys (a thread belongs to a category and a user; a post belongs
  to a thread and a user).
- **Security basics already included**: passwords hashed with
  `password_hash()`/`password_verify()`, all output escaped with
  `htmlspecialchars()` via the `h()` helper to prevent XSS, prepared
  statements everywhere user input touches SQL.

## Ideas for "Future Work" section / extensions

- AJAX-based reply posting (no page refresh) — the brief lists this as optional
- Search across threads/posts
- Edit/delete own posts, admin moderation tools
- Pagination for long thread lists
- User profile pages, avatars, post counts
- Rich text / Markdown formatting for posts

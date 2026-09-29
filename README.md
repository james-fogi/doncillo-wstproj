# Personal Task Manager

## Project Information

- **Project Code:** WST21-PM-2026-SF
- **Student Name:** James Adrian Doncillo
- **Course & Year:** BSIT-2 SEC10
- **Database Used:** SQLite

## Features

- Add Task
- View Tasks
- Edit Task
- Delete Task
- Update Status (Pending / Completed)

## Additional features
- **Overdue** task count, badge, and filter for pending tasks past their due date
<img width="1920" height="869" alt="{2EC11723-834D-45BD-AC57-E591FB395FC8}" src="https://github.com/user-attachments/assets/02ef1534-73f7-40b8-8d34-1dae15cda3f8" />
<img width="1914" height="965" alt="{CB57F912-9157-4A34-B4C4-587BA517D4ED}" src="https://github.com/user-attachments/assets/958aacd9-97aa-4889-824c-a1ddcc993997" />
<img width="1914" height="958" alt="{492BDC0D-B6A8-4B0B-9AA3-836B77EE5EE7}" src="https://github.com/user-attachments/assets/fee83bd2-9d80-47a4-8146-e09c2ad3faad" />



## **How It Works, Step by Step**

A request enters Laravel. The web server sends the request to index.php, which loads Composer and starts the app. app.php configures routing and middleware.

Laravel matches a route. The routes in web.php send / to /tasks. The task routes cover listing, creating, editing, deleting, and changing status. The registered endpoints are standard Laravel resource routes plus a dedicated status route.

The controller handles the action. TaskController.php is the main application logic:

index reads an optional filter and fetches matching tasks.
create and edit prepare the task form.
store and update validate submitted fields before saving.
updateStatus toggles a task between pending and completed.
destroy deletes a task.
Tasks are stored in SQLite. Task.php is the Eloquent model. It allows mass assignment of the task fields and casts due_date to a date. The schema is defined in the tasks migration: task name, optional description, status, optional due date, and timestamps.

Overdue status is calculated, not stored. A task is overdue only when it is pending, has a due date, and that date is before today. The model provides isOverdue() for display; the controller uses the equivalent condition when filtering and counting overdue tasks. A task due today is not overdue.

Blade renders the page. The controller passes tasks, counts, and the active filter to the task list view. The shared layout provides the navigation and responsive page structure. The shared form is used by both create and edit pages.

User actions submit normal web forms. Forms include CSRF protection; Laravel’s method spoofing lets HTML forms submit PATCH, PUT, and DELETE actions. On success, the controller redirects back to the task list and flashes a confirmation message. On validation failure, Laravel returns to the form with errors and prior input.

CSS is bundled separately from the page logic. Vite’s config builds the CSS and JavaScript entry points. Tailwind styles are in app.css; app.js is effectively empty, so the current workflows are handled by server-rendered forms rather than custom JavaScript.

## Requirements

- PHP 8.3 or newer
- Composer
- Node.js and npm

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm install
npm run build
php artisan serve
```

Open the local URL printed by `php artisan serve`. SQLite is used by default, so no database server is needed. Add your student name and course details above before submitting.

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
- Overdue task count, badge, and filter for pending tasks past their due date

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
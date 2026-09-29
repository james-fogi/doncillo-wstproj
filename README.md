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

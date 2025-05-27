# Product Management System

A comprehensive Product management system built with CodeIgniter 4, featuring product management, authentication, and responsive UI.

## Features

- Secure Authentication System
- Product Management (CRUD)
- Image Upload with Automatic Resizing
- DataTables Integration with Server-side Processing
- Responsive Bootstrap UI
- Category Management

## Requirements

- PHP 8.2 or higher
- MySQL 5.7 or higher
- Apache/Nginx Web Server
- Composer

## Installation

1. Clone the repository:
```bash
git clone [repository-url]
cd Atts-Task
```

2. Install dependencies:
```bash
composer install
```

3. Configure your database:
- Copy `env` file to `.env`
- Update database credentials in `.env`:
```
database.default.hostname = localhost
database.default.database = atts_db
database.default.username = root
database.default.password = root
```

4. Import database schema:
-I have attached the database schema in the root folder with the filename atts_db.sql.Import this file into phpMyAdmin.

5. Configure web server:
- Point your web server to the `public` directory
- Ensure `writable` directory has proper permissions

6. Run the application:
```bash
php spark serve
```

## Default Login Credentials

- Email: admin@example.com
- Password: password

## Directory Structure

```
Atts-Task/
├── app/
│   ├── Config/
│   ├── Controllers/
│   ├── Models/
│   ├── Views/
│   └── Helpers/
├── public/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── uploads/
├── system/
└── writable/
```

## Technologies Used

- CodeIgniter 4
- Bootstrap 5
- jQuery DataTables
- MySQL
- Composer

## License

MIT License 

# Jewelry Shop Management System

A comprehensive jewelry shop management system built with CodeIgniter 4, featuring product management, authentication, and responsive UI.

## Features

- Secure Authentication System
- Product Management (CRUD)
- Image Upload with Automatic Resizing
- DataTables Integration with Server-side Processing
- Responsive Bootstrap UI
- Category Management

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx Web Server
- Composer

## Installation

1. Clone the repository:
```bash
git clone [repository-url]
cd jewellary_shop
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
database.default.database = jewelry_shop
database.default.username = your_username
database.default.password = your_password
```

4. Import database schema:
```bash
php spark migrate
php spark db:seed
```

5. Configure web server:
- Point your web server to the `public` directory
- Ensure `writable` directory has proper permissions

6. Run the application:
```bash
php spark serve
```

## Default Login Credentials

- Email: admin@example.com
- Password: admin123

## Directory Structure

```
jewellary_shop/
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
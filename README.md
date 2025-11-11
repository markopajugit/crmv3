# CRMv3 - Laravel CRM Application

A comprehensive Customer Relationship Management (CRM) system built with Laravel 11 for managing companies, persons, orders, invoices, services, and related business entities.

## Overview

CRMv3 is a full-featured CRM application designed to handle complex business relationships and workflows. It provides tools for managing client information, orders, invoicing, document management, KYC compliance, and more. The system supports both company and individual person entities with extensive relationship mapping capabilities.

## Technology Stack

- **Framework**: Laravel 11
- **PHP Version**: 8.2 or higher
- **Database**: MySQL/PostgreSQL
- **Frontend**: Blade templates, Bootstrap 5, CoreUI 4
- **PDF Generation**: dompdf (barryvdh/laravel-dompdf)
- **Authentication**: Laravel Sanctum, Laravel UI
- **Asset Compilation**: Laravel Mix (Webpack)
- **Code Style**: PSR-12 (enforced via Laravel Pint)

## Requirements

### Server Requirements

- PHP >= 8.2
- Composer
- Node.js >= 14.x and npm
- MySQL 5.7+ or PostgreSQL 10+
- Web server (Apache/Nginx) or PHP built-in server
- Required PHP extensions:
  - BCMath
  - Ctype
  - cURL
  - DOM
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PCRE
  - PDO
  - Tokenizer
  - XML

## Installation & Setup

### 1. Clone the Repository

```bash
git clone <repository-url>
cd crmv3
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

Edit the `.env` file and configure your database connection:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### 5. Run Database Migrations

```bash
php artisan migrate
```

### 6. Seed the Database (Optional)

To populate the database with sample data:

```bash
php artisan db:seed
```

### 7. Compile Frontend Assets

For development:

```bash
npm run dev
```

For production:

```bash
npm run production
```

To watch for changes during development:

```bash
npm run watch
```

### 8. Create Storage Link

Create a symbolic link from `public/storage` to `storage/app/public`:

```bash
php artisan storage:link
```

### 9. Set Permissions

Ensure the storage and cache directories are writable:

```bash
chmod -R 775 storage bootstrap/cache
```

On Windows, you may need to adjust folder permissions through the file system.

### 10. Run the Application

Using PHP's built-in server:

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`.

For production, configure your web server (Apache/Nginx) to point to the `public` directory.

## Default Login

After seeding the database, you can log in with the default user credentials (check the `UserSeeder` for default credentials).

**Note**: User registration is disabled by default. Users must be created by administrators.

## Features

### Core Entities

- **Companies**: Manage company information, registration details, VAT numbers, addresses, and contacts
- **Persons**: Manage individual person records with personal details, tax residency, and KYC information
- **Orders**: Track orders with associated companies, persons, services, and payments
- **Invoices**: Generate and manage invoices with PDF export capabilities
- **Services**: Manage service offerings with categories and pricing

### Document Management

- File uploads for companies, persons, and orders
- Archive number generation and tracking
- Virtual office document management
- Document download and viewing
- File organization by entity type

### KYC (Know Your Customer) Compliance

- KYC record management for companies and persons
- KYC expiration tracking and notifications
- Historical KYC records
- Polymorphic relationship support

### Relationship Management

- Company-to-Person relationships with role definitions
- Company-to-Company relationships
- Order-to-Service associations
- Order contacts management
- Multiple addresses and contacts per entity

### Financial Management

- Invoice generation with PDF export
- Payment tracking for orders
- Proforma invoice support
- Paid/unpaid invoice filtering
- Payment history

### Additional Features

- **Notes System**: Add notes to companies, persons, and orders
- **Risk Assessment**: Track and manage risk levels for entities
- **Tax Residency**: Manage tax residency information for persons
- **Search**: Global search with autocomplete functionality
- **Dashboard**: Overview with statistics and filtering options
- **Renewals**: Track and manage service renewals
- **Settings**: Application-wide configuration
- **User Management**: Admin user management
- **Public Client Portal**: Allow clients to update their information

## Development

### Code Style

This project follows PSR-12 coding standards. Format your code using Laravel Pint:

```bash
./vendor/bin/pint
```

### Running Tests

```bash
php artisan test
```

### Database Migrations

Create a new migration:

```bash
php artisan make:migration migration_name
```

Run migrations:

```bash
php artisan migrate
```

Rollback migrations:

```bash
php artisan migrate:rollback
```

### Asset Compilation

Watch for changes during development:

```bash
npm run watch
```

Compile for production:

```bash
npm run production
```

## Project Structure

```
crmv3/
├── app/
│   ├── Http/Controllers/    # Application controllers
│   ├── Models/              # Eloquent models
│   └── Providers/           # Service providers
├── database/
│   ├── factories/           # Model factories
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── resources/
│   ├── views/               # Blade templates
│   ├── js/                  # JavaScript files
│   └── sass/                # SCSS stylesheets
├── routes/
│   ├── web.php              # Web routes
│   └── api.php              # API routes
└── public/                  # Public assets
```

## Security

This application implements comprehensive security measures to protect against common vulnerabilities and attacks.

### Authentication & Authorization

- **Authentication Required**: All sensitive routes require user authentication via Laravel's auth middleware
- **Authorization Policies**: Laravel Policies implemented for File, Company, Order, Invoice, and Person models
- **User Registration**: Disabled by default - users must be created by administrators
- **Session Security**: 
  - HTTP-only cookies enabled
  - Same-site cookie protection (Lax)
  - Secure cookie configuration via environment variables

### Input Validation & Sanitization

- **Form Request Validation**: Custom Form Request classes for file uploads and other sensitive operations
- **Mass Assignment Protection**: All controllers use `$request->only()` with explicit field lists to prevent unauthorized field modification
- **Input Sanitization**: User input is sanitized and validated before database operations
- **SQL Injection Prevention**: 
  - All queries use Eloquent ORM with parameter binding
  - Raw SQL queries replaced with parameterized alternatives
  - No direct user input in raw SQL statements

### File Upload Security

- **File Type Validation**: Strict whitelist of allowed file extensions (PDF, DOC, DOCX, XLS, XLSX, TXT, JPG, JPEG, PNG, GIF, CSV)
- **File Size Limits**: Maximum file size of 10MB enforced
- **MIME Type Verification**: File MIME types are validated
- **Path Traversal Protection**: 
  - File names sanitized using `basename()` and custom sanitization
  - File paths validated to ensure they're within allowed directories
  - Real path verification prevents directory traversal attacks
- **Secure File Storage**: Files stored outside web root with controlled access

### XSS (Cross-Site Scripting) Protection

- **Output Escaping**: All user-generated content escaped using `htmlspecialchars()` with ENT_QUOTES
- **Blade Auto-Escaping**: Blade templates automatically escape output using `{{ }}` syntax
- **HTML Attribute Escaping**: All HTML attributes properly escaped in autocomplete and search results
- **Content Security**: No direct `echo` of user input - all output goes through proper response methods

### CSRF Protection

- **CSRF Tokens**: All forms include CSRF tokens via `@csrf` directive
- **Middleware**: CSRF protection enabled globally via middleware
- **Token Verification**: Automatic token verification on all POST/PUT/DELETE requests

### Rate Limiting

Rate limiting implemented on sensitive endpoints to prevent abuse and DoS attacks:

- **File Uploads**: 10 requests per minute
- **File Downloads/Views**: 30 requests per minute
- **File Deletions**: 10 requests per minute
- **Invoice Creation**: 10 requests per minute
- **Search Operations**: 30 requests per minute
- **Autocomplete**: 60 requests per minute

### Error Handling

- **Secure Error Messages**: Error messages don't expose sensitive system information
- **Proper Exception Handling**: All exceptions caught and handled appropriately
- **Logging**: Security-relevant events logged for audit purposes
- **No Debug Code in Production**: Test routes protected by environment checks

### Additional Security Measures

- **Test Routes Protection**: Test/debug routes only accessible in non-production environments
- **Hardcoded Data Removal**: Sensitive data (like email addresses) moved to environment variables
- **Path Validation**: All file operations validate paths to prevent directory traversal
- **Authorization Checks**: Delete operations verify user permissions
- **Input Length Limits**: String inputs have maximum length validation
- **Type Validation**: All IDs and numeric inputs validated as integers

### Security Best Practices

1. **Principle of Least Privilege**: Users should only access resources they're authorized for
2. **Defense in Depth**: Multiple layers of security (validation, authorization, sanitization)
3. **Input Validation**: Validate, sanitize, and escape all user inputs
4. **Secure File Handling**: Validate file types, scan for malware, store outside web root
5. **Error Handling**: Never expose sensitive information in error messages
6. **Logging**: Log all security-relevant events (failed auth, file access, data modifications)

### Security Recommendations

For production deployment, consider implementing:

- **Two-Factor Authentication (2FA)**: For admin users
- **Security Headers**: Add middleware for CSP, HSTS, X-Frame-Options
- **Audit Logging**: Comprehensive logging of all sensitive operations
- **Role-Based Access Control (RBAC)**: Granular permissions beyond current policy implementation
- **Regular Security Audits**: Periodic security reviews and dependency scanning
- **Penetration Testing**: Regular security testing by qualified professionals

## License

This project is proprietary software. All rights reserved.

## Support

For issues, questions, or contributions, please contact the development team.

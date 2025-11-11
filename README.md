# WisorGroup Business Management System

A comprehensive Laravel-based business management system for managing companies, persons, orders, invoices, and related services. This application provides a complete solution for business operations including client management, order tracking, invoicing, document management, and KYC (Know Your Customer) compliance.

## Features

- **Company Management**: Complete company profiles with registration details, addresses, contacts, and risk assessment
- **Person Management**: Individual client profiles with personal details, tax residency, and KYC information
- **Order Management**: Order tracking with services, payments, and status management
- **Invoice Management**: Invoice generation, PDF export, and payment tracking
- **Service Management**: Service catalog with categories and pricing
- **Document Management**: File upload, storage, and organization with archive numbering
- **KYC Compliance**: Know Your Customer verification with expiration tracking and polymorphic relationships
- **Tax Residency Management**: Multi-country tax residency tracking for individuals with date ranges
- **Entity Management**: Flexible contact and address management for companies and persons
- **Risk Assessment**: Comprehensive risk tracking and assessment history
- **Notes System**: Centralized note-taking for companies, persons, and orders
- **Search & Reporting**: Advanced search capabilities and business reporting
- **User Management**: Role-based access control
- **Public Client Portal**: External client access for information updates

## Requirements

- PHP 7.3 or higher (8.0+ recommended)
- Composer
- Node.js & NPM
- MySQL 5.7+ or PostgreSQL 9.6+
- Web server (Apache/Nginx)

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd wisorgroup
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node.js Dependencies

```bash
npm install
```

### 4. Environment Configuration

Create a `.env` file from the example:

```bash
cp .env.example .env
```

Configure your environment variables in `.env`:

```env
APP_NAME="WisorGroup"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wisorgroup
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Database Setup

Create your database and run migrations:

```bash
php artisan migrate
```

### 7. Seed the Database (Optional)

```bash
php artisan db:seed
```

### 8. Compile Assets

For development:
```bash
npm run dev
```

For production:
```bash
npm run production
```

### 9. Set Permissions

Ensure the storage and bootstrap/cache directories are writable:

```bash
chmod -R 775 storage bootstrap/cache
```

### 10. Start the Application

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`.

## Configuration

### File Storage

The application stores uploaded files in the `storage/app/public` directory. Make sure to create a symbolic link:

```bash
php artisan storage:link
```

### Mail Configuration

Configure your mail settings in the `.env` file for email notifications and KYC expiration alerts.

### PDF Generation

The application uses DomPDF for invoice generation. Ensure your server has the necessary dependencies for PDF generation.

## Usage

### Default Login

After seeding the database, you can log in with:
- **Email**: admin@example.com
- **Password**: password

### Key Functionality

1. **Dashboard**: Overview of companies, persons, orders, and payment status
2. **Companies**: Manage company profiles, contacts, addresses, risk assessments, and KYC records
3. **Persons**: Manage individual clients with personal details, tax residency, and KYC information
4. **Orders**: Create and track orders with services, payments, and custom contact data
5. **Invoices**: Generate invoices and track payments with proper foreign key relationships
6. **Services**: Manage service catalog and categories with flexible pricing
7. **Documents**: Upload and organize files with archive numbering and entity relationships
8. **KYC Management**: Track KYC compliance with expiration dates and risk assessments
9. **Tax Residency**: Manage multiple tax residencies with date ranges and primary status
10. **Entity Management**: Flexible contact and address management for all entities
11. **Notes System**: Centralized note-taking for companies, persons, and orders
12. **Search**: Advanced search across all entities with improved data structure
13. **Reports**: Business reporting and analytics

### Public Client Access

Clients can access a public portal to update their information:
- URL: `/public/client/{id}`
- Allows clients to update their contact information without logging in

## Development

### Running Tests

```bash
php artisan test
```

### Code Style

The project follows PSR-12 coding standards. Use PHP CS Fixer to maintain code style:

```bash
./vendor/bin/php-cs-fixer fix
```

### Asset Compilation

Watch for changes during development:

```bash
npm run watch
```

## API Endpoints

The application provides RESTful API endpoints for:
- Companies (`/api/companies`)
- Persons (`/api/persons`)
- Orders (`/api/orders`)
- Invoices (`/api/invoices`)
- Services (`/api/services`)

## Cron Jobs

Set up the following cron jobs for automated tasks:

```bash
# Check KYC expirations
* * * * * php /path/to/artisan cron:check-kyc-expirations

# Update last modified orders
* * * * * php /path/to/artisan cron:last-updated-orders
```

## Security

- CSRF protection enabled
- SQL injection protection through Eloquent ORM
- XSS protection with Blade templating
- File upload validation and secure storage
- Role-based access control

## Database Schema

The application uses a comprehensive database schema with the following key tables:

### Core Tables
- **users**: User authentication and management
- **companies**: Company profiles with registration details
- **persons**: Individual client profiles
- **orders**: Order tracking and management
- **invoices**: Invoice generation and payment tracking
- **services**: Service catalog and pricing
- **service_category**: Service categorization

### Relationship Tables
- **company_person**: Many-to-many relationship between companies and persons
- **company_order**: Many-to-many relationship between companies and orders
- **order_person**: Many-to-many relationship between orders and persons
- **order_service**: Order-service relationships with custom pricing and dates

### Extended Features
- **kycs**: KYC records with polymorphic relationships (companies/persons)
- **person_tax_residencies**: Tax residency tracking with date ranges
- **entity_contacts**: Polymorphic contact information (email, phone, etc.)
- **entity_addresses**: Polymorphic address management
- **entity_risks**: Risk assessment and tracking
- **notes**: Centralized note-taking system
- **files**: Document management and storage

### Data Integrity
- All foreign keys have proper cascade behaviors
- Polymorphic relationships for flexible entity management
- Proper indexing for performance optimization
- Data validation and constraints

## Troubleshooting

### Common Issues

1. **Permission Errors**: Ensure storage directories are writable
2. **Database Connection**: Verify database credentials in `.env`
3. **Asset Compilation**: Run `npm run dev` after pulling changes
4. **PDF Generation**: Check DomPDF dependencies
5. **Migration Issues**: If you encounter migration errors, ensure all migrations are properly ordered and dependencies are met

### Logs

Check application logs in `storage/logs/laravel.log` for debugging information.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support and questions, please contact the development team or create an issue in the repository.

---

**Note**: This is a business management system designed for internal use. Ensure proper security measures are in place when deploying to production.
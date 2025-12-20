<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

# DOFF NOS - Daily Overland Freight Forwarding System

## Overview

DOFF NOS is a comprehensive freight forwarding and logistics management system built on the Laravel framework. This application provides end-to-end solutions for shipping, tracking, and managing freight operations with features for both customers and administrators.

## Key Features

### Core Functionality
- **Waybill Management**: Create, track, and manage shipping waybills
- **Customer Portal**: Online booking system for freight services
- **Track & Trace**: Real-time shipment tracking capabilities
- **PCA Account Management**: Professional Customer Account system for business clients
- **Payment Processing**: Integration with GCash and other payment methods
- **QR Code Profiles**: Contact verification and QR-based identification

### User Management
- **Role-based Access Control**: Admin, Client, and PCA roles
- **Social Authentication**: Facebook and Google OAuth integration
- **Contact Verification**: ID verification system for customer accounts
- **Multi-address Support**: Users can manage multiple shipping addresses

### Business Operations
- **Branch Management**: Multiple branch locations with service areas
- **Sector-based Routing**: Geographic sector management for delivery optimization
- **Pasabox System**: Specialized delivery service management
- **Rebate System**: Customer loyalty and rebate tracking
- **Incident Reporting**: Complaint and feedback management system

## Technology Stack

### Backend
- **Framework**: Laravel 10.x
- **PHP Version**: ^8.1
- **Database**: MySQL with multiple database connections
- **Authentication**: Laravel Sanctum + Social Authentication

### Frontend
- **JavaScript**: Vue.js 2.x, React 16.x
- **CSS Framework**: Bootstrap 4
- **Build Tools**: Laravel Mix, Webpack
- **Mobile Framework**: Framework7 for mobile interfaces

### Key Packages
- **PDF Generation**: Laravel DomPDF, TCPDF
- **Image Processing**: Intervention Image
- **Permissions**: Spatie Laravel Permission
- **Social Auth**: Laravel Socialite
- **File Storage**: AWS S3 Integration

## Application Structure

### Core Models
- **User**: Authentication and user management
- **Waybill**: Central shipping document model
- **Contact**: Customer and business contact management
- **Branch**: Physical branch locations
- **Sector**: Geographic delivery sectors

### Database Connections
- `dailyove_online_site`: Main application database
- Additional connections for legacy systems and reporting

### Key Controllers
- **WaybillController**: Shipment and booking management
- **HomeController**: Main application logic and API endpoints
- **AccountController**: User account management
- **BranchController**: Branch operations
- **ChatController**: Customer support messaging

## Installation & Setup

### Prerequisites
- PHP 8.1+
- MySQL 5.7+ or MariaDB 10.3+
- Node.js 16+
- Composer
- NPM/Yarn

### Environment Configuration
1. Copy `.env.example` to `.env`
2. Configure database connections
3. Set up mail driver settings
4. Configure AWS S3 for file storage
5. Set up social authentication keys

### Installation Steps
```bash
composer install
npm install
php artisan key:generate
php artisan migrate
npm run dev
```

## API Documentation

### Core Controllers

#### HomeController
**Purpose**: Main application controller handling core business logic and API endpoints
**Key Methods**:
- `index()` - Main dashboard and landing page
- `getProvinces()` - Retrieve provinces for dropdown selections
- `getCities()` - Get cities by province
- `getBusinessTypes()` - Fetch business type categories
- `getSectors()` - Retrieve delivery sectors
- `getBranches()` - Get branch information
- `trackWaybill()` - Real-time waybill tracking
- `generateQRCode()` - QR code generation for profiles
- `uploadFile()` - File upload handler
- `sendEmail()` - Email notification system

**API Endpoints**:
```php
GET /api/provinces
GET /api/cities/{province_id}
GET /api/business-types
GET /api/sectors
GET /api/branches
POST /api/track-waybill
POST /api/generate-qr
POST /api/upload-file
```

#### WaybillController
**Purpose**: Comprehensive waybill management system
**Key Methods**:
- `create()` - Display waybill creation form
- `store()` - Process new waybill creation
- `show()` - Display waybill details
- `edit()` - Edit existing waybill
- `update()` - Update waybill information
- `destroy()` - Delete/cancel waybill
- `track()` - Track waybill status
- `calculateRate()` - Rate calculation API
- `generatePDF()` - PDF waybill generation
- `sendNotification()` - Send SMS/email notifications

**API Endpoints**:
```php
GET /waybills/create
POST /waybills
GET /waybills/{reference_no}
PUT /waybills/{reference_no}
DELETE /waybills/{reference_no}
GET /track/{reference_no}
POST /api/calculate-rate
GET /waybills/{reference_no}/pdf
```

#### AccountController
**Purpose**: User account management and authentication
**Key Methods**:
- `profile()` - User profile management
- `updateProfile()` - Update user information
- `changePassword()` - Password change functionality
- `addresses()` - Manage user addresses
- `addAddress()` - Add new shipping address
- `deleteAddress()` - Remove user address
- `verification()` - ID verification process
- `uploadID()` - Upload verification documents

#### BranchController
**Purpose**: Branch operations and management
**Key Methods**:
- `index()` - List all branches
- `show()` - Display branch details
- `getBranchSchedule()` - Branch operating hours
- `getBranchServices()` - Available services by branch
- `findNearestBranch()` - Locate nearest branch based on location

### Database Schema Documentation

#### Core Tables

**tblwaybills** (Primary: reference_no)
- Central waybill tracking table
- Contains shipment details, pricing, status tracking
- Foreign keys to contacts, branches, sectors

**tblcontacts** (Primary: id)
- Customer and business contact information
- Links to waybills, addresses, verification data

**tblbranches** (Primary: id)
- Physical branch locations and service areas
- Contains contact info, operating hours, services

**tblsectors** (Primary: id)
- Geographic delivery sectors for routing optimization
- Links to branches and waybills for delivery planning

**tbltrackandtrace** (Primary: id)
- Shipment tracking history and status updates
- Timestamped records of waybill movements

**tblonlinepayment** (Primary: id)
- Payment transaction records
- Links to waybills and payment confirmations

#### Model Relationships

**Waybill Model**:
```php
// Primary relationships
public function waybillShipment()
public function waybillContact()
public function waybillShipmentMultiple()
public function contact()
public function shipperConsignee()
public function userAddress()
public function branch()
public function sector()
public function onlinePaymentConfirmation()
public function trackAndTrace()
```

**User Model**:
```php
// Authentication and relationships
public function waybills()
public function shipperConsignee()
public function contacts()
public function userAddresses()
public function contactVerification()
public function rebateTransactions()
```

**Contact Model**:
```php
// Contact management
public function waybills()
public function contactNumbers()
public function userAddresses()
public function contactVerification()
```

## Business Workflows

### Waybill Creation Process
1. **Customer Authentication**: User login or guest checkout
2. **Address Selection**: Choose from saved addresses or enter new
3. **Shipment Details**: Specify package dimensions, weight, contents
4. **Service Selection**: Choose delivery speed and additional services
5. **Rate Calculation**: Automatic pricing based on distance and weight
6. **Payment Processing**: GCash integration or bank transfer
7. **Waybill Generation**: Create tracking number and documentation
8. **Notification**: SMS/email confirmation to customer

### Shipment Tracking Workflow
1. **Waybill Creation**: Initial status "Booked"
2. **Pickup Confirmation**: Status "Picked Up"
3. **In Transit**: Multiple tracking points during transport
4. **Out for Delivery**: Final delivery stage
5. **Delivered**: Successful completion
6. **Exception Handling**: Failed delivery, returns, incidents

### Payment Processing
1. **Order Placement**: Calculate total cost
2. **Payment Selection**: GCash, bank transfer, cash on delivery
3. **Payment Confirmation**: Verify transaction completion
4. **Receipt Generation**: PDF receipt and waybill
5. **Accounting Integration**: Update financial records

### Incident Management
1. **Incident Report**: Customer or staff reports issue
2. **Categorization**: Classify incident type and severity
3. **Investigation**: Review waybill history and tracking
4. **Resolution**: Implement corrective action
5. **Customer Communication**: Update on resolution status
6. **Documentation**: Record incident for analysis

## Deployment & Production Setup

### Server Requirements
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **PHP**: 8.1+ with required extensions
- **Database**: MySQL 5.7+ or MariaDB 10.3+
- **Memory**: Minimum 2GB RAM, 4GB recommended
- **Storage**: 50GB+ for file uploads and logs

### Environment Configuration
```bash
# Production environment variables
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=doff_production
DB_USERNAME=production_user
DB_PASSWORD=secure_password

# Cache and session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Email configuration
MAIL_DRIVER=smtp
MAIL_HOST=smtp.dailyoverland.com
MAIL_PORT=587
MAIL_USERNAME=noreply@dailyoverland.com
MAIL_PASSWORD=mail_password
MAIL_ENCRYPTION=tls

# File storage
FILESYSTEM_DRIVER=s3
AWS_ACCESS_KEY_ID=your_aws_key
AWS_SECRET_ACCESS_KEY=your_aws_secret
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=doff-uploads
```

### Deployment Steps
1. **Code Deployment**:
   ```bash
   git pull origin main
   composer install --no-dev --optimize-autoloader
   npm install --production
   npm run production
   ```

2. **Database Migration**:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

3. **Cache Optimization**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

4. **Queue Setup**:
   ```bash
   php artisan queue:work --daemon
   ```

### Security Considerations
- **HTTPS Enforcement**: SSL/TLS certificate required
- **Firewall Configuration**: Restrict database and Redis access
- **File Upload Limits**: Configure reasonable upload size limits
- **Rate Limiting**: Implement API rate limiting
- **Security Headers**: Add CSP, HSTS, and other security headers
- **Regular Updates**: Keep Laravel and dependencies updated

## Development Guidelines

### Code Standards
- **PSR-12**: Follow PHP coding standards
- **Laravel Conventions**: Use Laravel naming conventions
- **Database Naming**: Snake_case for tables, camelCase for models
- **API Responses**: Consistent JSON response format
- **Error Handling**: Proper exception handling and logging

### Testing Strategy
- **Unit Tests**: Model and service layer testing
- **Feature Tests**: Controller and API endpoint testing
- **Browser Tests**: Critical user journey testing
- **Database Tests**: Migration and seeder validation

### Git Workflow
- **Feature Branches**: Create branches for new features
- **Code Review**: Required pull request review
- **Automated Testing**: CI/CD pipeline for quality assurance
- **Semantic Versioning**: Follow versioning conventions

## Monitoring & Maintenance

### Application Monitoring
- **Error Tracking**: Sentry or similar error monitoring
- **Performance Monitoring**: Application response times
- **Database Monitoring**: Query performance and connections
- **Queue Monitoring**: Job processing status and failures

### Backup Strategy
- **Database Backups**: Daily automated backups
- **File Backups**: Weekly file storage backups
- **Configuration Backups**: Version control for config files
- **Disaster Recovery**: Documented recovery procedures

### Performance Optimization
- **Database Indexing**: Optimize slow queries
- **Caching Strategy**: Redis for session and application cache
- **CDN Integration**: Static asset delivery optimization
- **Load Balancing**: Horizontal scaling for high traffic

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[British Software Development](https://www.britishsoftware.co)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- [UserInsights](https://userinsights.com)
- [Fragrantica](https://www.fragrantica.com)
- [SOFTonSOFA](https://softonsofa.com/)
- [User10](https://user10.com)
- [Soumettre.fr](https://soumettre.fr/)
- [CodeBrisk](https://codebrisk.com)
- [1Forge](https://1forge.com)
- [TECPRESSO](https://tecpresso.co.jp/)
- [Runtime Converter](http://runtimeconverter.com/)
- [WebL'Agence](https://weblagence.com/)
- [Invoice Ninja](https://www.invoiceninja.com)
- [iMi digital](https://www.imi-digital.de/)
- [Earthlink](https://www.earthlink.ro/)
- [Steadfast Collective](https://steadfastcollective.com/)
- [We Are The Robots Inc.](https://watr.mx/)
- [Understand.io](https://www.understand.io/)
- [Abdel Elrafa](https://abdelelrafa.com)
- [Hyper Host](https://hyper.host)

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

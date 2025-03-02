## Ticket Management System
**Overview
This project is a Laravel-based ticket management system that allows users to create, track, and
 reply to support tickets. It includes authentication, a dashboard, and an email notification system.

## Assumptions

- The system is designed for authenticated users to create and manage support tickets.

- Laravel Breeze is used for authentication and user management.

- Emails are sent using Laravel's Mailable feature and are configured for testing using Mailtrap.

- The project is developed and tested in a local environment before deployment.

## Improvements

- Implemented AJAX pagination for ticket listing to improve performance.

- Used Laravel Breeze for authentication, providing a lightweight and efficient authentication flow.

- Integrated queueable email notifications for better performance when sending ticket replies.

- Optimized database queries by eager-loading relationships (Ticket::with('replies')).

## Installation & Setup

**Prerequisites

Ensure you have the following installed on your machine:

- PHP 8.x
- Composer
- Node.js & NPM
- MySQL
- Laravel 11.x

## Steps to Setup

- Clone the repository
    https://github.com/kasunkalya/ticketing.git
    cd ticketing
- Install dependencies
    composer install
    npm install && npm run build
- Configure environment
    Update .env with your database credentials and mail settings.
- Run migrations & seed database
    php artisan migrate --seed
- Setup authentication (Laravel Breeze)
    php artisan breeze:install
    npm install && npm run build
    php artisan migrate
- Start the development server
    php artisan serve
    Visit http://127.0.0.1:8000 in your browser.

## Testing the Application

- Test User name : test@example.com
  Password       : password
- Anyone can create a ticket
- Login and add reply to the ticket from the dashboard.
- Check the ticket list with AJAX-based pagination.
- Reply to a ticket and verify email notifications.
- Test emails using Mailtrap or by logging emails in storage/logs/laravel.log.

## How to Run in a New Machine

- Install dependencies (composer install, npm install)
- Configure .env
- Run php artisan migrate --seed
- Start the server (php artisan serve)
- Test using http://127.0.0.1:8000

## Technologies Used

- Laravel Breeze for authentication
- Laravel 11.x as the backend framework
- Tailwind CSS for styling
- MySQL for database management
- AJAX & Laravel Livewire for dynamic UI interactions


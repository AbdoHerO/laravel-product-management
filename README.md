# Laravel Product Management

This repository contains the Laravel backend code for a Product Management web application. (Crud, Filter, Auth JWT, ...)

## Project Setup

1. Clone the repository:

   ```bash
   git clone https://github.com/AbdoHerO/laravel-product-management.git
   cd laravel-product-management
   ```

2. Install Laravel dependencies:

   ```bash
   composer install
   ```

3. Create a copy of the .env file:

   ```bash
   cp .env.example .env
   ```

4. Configure your .env file with your database settings.

5. Generate the application key:

   ```bash
   php artisan key:generate
   ```

6. Run migrations with Seeders:

   ```bash
   php artisan migrate --seed
   ```

7. Create a symbolic link in Laravel:

   ```bash
   php artisan storage:link
   ```

8. Start the development server:

   ```bash
   php artisan serve
   ```

The application should now be accessible at http://localhost:8000.

### Demo

You can access the Video Tuto of this application at: https://www.asonfo.com/public/uploads/all/DtssZF1azsuuWW8KlbHHxOhl8VcEO2rDnDg5LsBC.mp4.

### Default User Authentication in Seeder user

- Email: admin@squahr.com
- Password: password

For more details on Laravel, please refer to the [Laravel Documentation](https://laravel.com/docs).

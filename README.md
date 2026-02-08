# Real Estate Centre

A Laravel 11 project that mirrors a Nigerian property marketplace experience with a public site and an admin dashboard.

## Features

- Public marketing site with search, featured companies, listings, market trends, and content pages
- Authentication with Bootstrap styling
- Admin area to manage listings, partners, locations, and site content

## Local setup

1. Copy the environment file: `.env.example` to `.env`.
2. Ensure `database/database.sqlite` exists (already created in this repo).
3. Run migrations:
   - `php artisan migrate`
4. Install frontend dependencies and build assets:
   - `npm install`
   - `npm run build`
5. Start the dev server:
   - `php artisan serve`

## Admin access

An admin user is seeded for you:

- Email: admin@gmail.com
- Password: 123456

You can change this user or create others in the admin panel later.

## Notes

This project uses Bootstrap for UI and Vite for asset bundling.

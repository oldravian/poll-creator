# Mini Applications Platform

A platform with two mini applications built with Laravel and Vue.js:

1. **Poll Creator** - Create, manage, and participate in polls
2. **To-Do List** - Organize and manage your tasks (Coming Soon)

## Features

### Poll Creator

- User authentication with username-based login
- Create and manage polls with multiple options
- Real-time voting with email verification
- Responsive dashboard with poll analytics
- Poll expiration settings
- Beautiful UI with modern design

### To-Do List (Coming Soon)

- Create and manage task lists
- Add, view, and delete tasks
- Prioritize tasks with drag & drop
- Clean and intuitive interface

## Installation

1. Clone the repository
2. Install dependencies:

    ```bash
    composer install
    npm install
    ```

3. Copy environment file:

    ```bash
    cp .env.example .env
    ```

4. Generate application key:

    ```bash
    php artisan key:generate
    ```

5. Configure your database in `.env` file

6. Run migrations:
    ```bash
    php artisan migrate
    ```

## Sample Data

To populate the application with sample co-working space polls and a test user, run:

```bash
php artisan db:seed --class=CoWorkingSpaceSeeder
```

This will create:

- **Test User**: Username: `habib`, Password: `password`
- **8 Co-working Space Polls** with realistic questions and options
- **Sample votes** to make the polls look active

## Development

Start the development server:

```bash
php artisan serve
npm run dev
```

## User Flow

1. **Welcome Page**: Simple landing page with Register/Login buttons
2. **Authentication**: Username-based login system
3. **App Selection**: After login, choose between Poll Creator or To-Do List
4. **Mini Apps**: Access your chosen application

## Authentication

The application uses username-based authentication:

- **Register**: Name, Username, Password
- **Login**: Username, Password

## Login Credentials

After running the seeder, you can login with:

- **Username**: `habib`
- **Password**: `password`

## Application Flow

1. Visit the welcome page
2. Register or login with username/password
3. Choose between **Poll Creator** or **To-Do List**
4. Start using your selected mini application

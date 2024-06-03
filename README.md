# Lesson Diary

## Project Description
The Lesson Diary application is designed to streamline the process of managing class schedules, recording attendance, and storing lesson materials for teachers and students. This application provides an easy-to-use interface for organizing and retrieving educational resources, enhancing the overall efficiency and productivity of the educational environment.

## Technologies
- **Frontend**: HTML, CSS
- **Backend**: PHP
- **Database**: MariaDB
- **Development Environment**: Visual Studio Code

## Database Schema
Below is the visualization of the database schema with descriptions of tables (or collections).

![Database Schema](path/to/diagram.png)

## User Guide

### System Requirements
- PHP 7.x or later
- MariaDB 10.x or later
- Visual Studio Code

### Installation Steps

1. Clone the repository:
    ```bash
    git clone https://github.com/itzbelliz/school_journal.git
    cd lesson-diary
    ```

2. Configure the database:
    - Create a new database in MariaDB.
    - Run the SQL scripts provided in the `sql` directory to set up the database schema.
  
3. Configure the `.env` file:
    ```env
    DB_HOST=localhost
    DB_PORT=3306
    DB_DATABASE=school_journal
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

4. Start the PHP server:
    ```bash
    php -S localhost:8000 -t public
    ```

5. Open Visual Studio Code and load the project to start development.

## Tests
To run unit tests (if applicable, depending on your testing setup):
```bash
# Example command, adjust based on your testing framework
php vendor/bin/phpunit

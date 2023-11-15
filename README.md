# ChicChic Shop
Welcome to ChicChic Shop! This is a Symfony-based e-commerce project.

## Prerequisites

Make sure you have the following software installed on your system:

- PHP: Confirm PHP installation by running php -v in your terminal.
- Composer: Verify Composer installation with composer -V.
- MySQL: Install MySQL to manage the database. Refer to the MySQL documentation for installation instructions.
- Node.js and npm: Install Node.js and npm to manage JavaScript dependencies. Refer to the Node.js documentation for installation instructions.


## Installation

### Clone the repository
```git clone https://github.com/yourusername/ChicChic-Shop.git```

### Install dependencies
Navigate to the project directory and run:
```composer install```
```npm install```


### Database Setup
- Create a MySQL database for the project.
- Configure the database connection in the .env file:
```DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name```

### Run Migrations
Execute the following commands:
```php bin/console doctrine:migrations:migrate```

## Compile Sass files
To compile Sass files, run the following command:
```npm run dev```

### Start the Symfony server
Run the Symfony server:
```symfony server:start```
Access your ChicChic Shop project at http://localhost:8000 in your web browser.

## Additional Information
For more advanced configurations or troubleshooting, refer to the Symfony documentation.


This README provides basic instructions to set up and run the "ChicChic Shop" project. Customize the database connection parameters and project paths accordingly.








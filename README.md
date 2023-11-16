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

## Screenshots

### ChicChic - Shop
View of the main page with a menu with categories that we can enter.
![Image](https://github.com/JanuszProgramowaniaa/ChicChic-Shop/blob/main/public/images/screenshots/home.jpg)


### Products
The view shows a collection of all products. Products are paged on the server side.
![Image](https://github.com/JanuszProgramowaniaa/ChicChic-Shop/blob/main/public/images/screenshots/products.jpg)


### Filter
The view shows the results after server-side filtering.
We filter by products added within a month, prices ranging from 2 to 10.
Additionally, we sort alphabetically.
The search engine searches for products by symbol or name, we do not have to enter the exact phrase.
![Image](https://github.com/JanuszProgramowaniaa/ChicChic-Shop/blob/main/public/images/screenshots/filter.jpg)


### Product
The view shows product details.
We can see an extensive breadcrumb menu that guides us step by step, including the category it belongs to.
![Image](https://github.com/JanuszProgramowaniaa/ChicChic-Shop/blob/main/public/images/screenshots/product.jpg)


### Contact
The view displays a contact form along with a map.
Fields are validated on the server side.
They cannot be empty and the first field must have the structure of an e-mail.
![Image](https://github.com/JanuszProgramowaniaa/ChicChic-Shop/blob/main/public/images/screenshots/contact.jpg)






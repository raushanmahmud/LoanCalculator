# LoanCalculator

> A basic loan calculator using Symfony 4.4

## Quick Start

``` bash
# Clone the git repository
git clone https://github.com/raushanmahmud/LoanCalculator.git

# cd into the application root folder
cd LoanCalculator

# Install dependencies
composer install

# Edit the env file and add DB params
# Now using Sqlite DB, unless you want to switch to a different driver, you can keep the default values in the .env file

# Create Loan and Payment schemas
php bin/console doctrine:migrations:diff
# Run migrations
php bin/console doctrine:migrations:migrate


# To run the application, run the php built in server as below
php -S 127.0.0.1:8000 -t public

# Go to http://localhost:8000 on your web browser, the application must be available
```

## App Info

### Author

Raushan Yergozhiyeva

### Version

1.0.0

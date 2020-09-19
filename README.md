# clone the git repository
git clone https://github.com/raushanmahmud/LoanCalculator.git

# ccd into the application root folder
cd LoanCalculator

# Install dependencies
composer install

# Edit the env file and add DB params
# Now using Sqlite DB, unless you want to switch to a different driver, you can keep the default values in the .env file

# Create Loan and Payment schemas
php bin/console doctrine:migrations:diff
# Run migrations
php bin/console doctrine:migrations:migrate


# To run the application, run php server as below
php -S 127.0.0.1:8000 -t public
# Database

# Packages

- Package used Spatie Multitenancy 4.0
- [Spatie Multitenancy](https://spatie.be/docs/laravel-multitenancy/v4/introduction).

# Installation

1. Copy & paste the `.env.example` to your `.env`

1. Since the package is installed just run :

- composer install

3. Migrate landlord 

- `php artisan migrate --path=database/migrations/landlord --database=landlord`


2. Create the tenant database, just seed the database

- `php artisan db:seed --class=TenantSeeder --database=landlord`

3. Run the job for creating the database

- `php artisan queue:work`

4. Run migrations for both tenants

- `php artisan tenants:artisan "migrate --path=database/migrations/tenant/ --database=tenant"`

3. Run migrations for each tenant.

- `php artisan tenants:artisan "migrate --path=database/migrations/tenant/ats --database=tenant" --tenant=1`



# Migrations

1. hris - main database
2. ats_db - Applicant Tracking System related database
3. eth_db - Employee Training Hub related database

# make:migration
1. hris - `php artisan make:migration create_jobs_table --path=database/migrations/landlord/`
2. ats_db - `php artisan make:migration create_jobs_table --path=database/migrations/tenant/ats/`
2. eth_db - `php artisan make:migration create_jobs_table --path=database/migrations/tenant/eth/`

# migrate
1. hris - `php artisan migrate --path=database/migrations/landlord --database=landlord`
2. ats_db -  `php artisan tenants:artisan "migrate --path=database/migrations/tenant/ats --database=tenant" --tenant=1` 
3. eth_db -  `php artisan tenants:artisan "migrate --path=database/migrations/tenant/eth --database=tenant" --tenant=2` 

note: you can still do `migrate:fresh` or `migrate:refresh`



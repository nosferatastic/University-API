# University API
 A small RESTful API developed in Laravel, for a University reviews system.

This API is developed in Laravel. It involves the authentication of user accounts which can leave reviews & mark-as-favourite within a collection of universities. Users and guests can search and retrieve information and reviews regarding the universities.

# Getting Started
The simplest way to initialise the API's database is to run the artisan commands
`php artisan migrate`
and
`php artisan db:seed`
within the `university-api` folder. This will generate an SQLite file stored in `database/database.sqlite` which is pre-populated with a user account (with email `testuser@testmail.com` and password `Pass123!`), a collection of Universities (retrieved from a CSV file), and a few reviews and favourites for the created user.
The API returns request responses in JSON format. When an API request returns an error, it is sent in the JSON response as an error message.

# Launching

In order to launch the API locally, firstly run the above database initialising commands, and then copy the `.env.example` file to `.env`. Then run `php artisan serve` within the `university-api` folder - you will be told when the development server starts where the local server URL is. You must have PHP (ideally 8.3 for compatibility) installed.

# Documentation

Postman documentation is accessible [by clicking here](https://documenter.getpostman.com/view/15715244/2s9YCBt9Df). It contains information on the available API routes and possible outputs.

# Authentication
The API uses Bearer tokens for authentication. You can retrieve a bearer token by using the `/api/user/login` or `/api/user/register` endpoints. If login/registration is successful, a token will be returned within the JSON response.
You must include a bearer token in add/remove favourite requests. They are optional in the `/api/university/{university_id}` , `/api/search` and `/university/{university_id}/review` endpoints but will modify behaviour, if provided, to retrieve favourite status for requested Universities, and to link submitted reviews with the authorised user account.


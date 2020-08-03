# Chat API

This is a very simple chat application API that will allow a user to:
1. Send a simple text message to another user.
2. Get the messages sent to them and see the author of those messages.
3. Mark messages as having been read.

## Requirements:
1. PHP >= 7.2.5
2. PHP SQLite3

## Getting Started
1. Clone the chat API repo 

2. Update the *DB_DATABASE* value in the *.env* file to reflect the **absolute path** to the database file in your environment.

3. Run the following command from the chat-api folder:  
php -S localhost:8000 -t public

You should now be able to run the API requests against your local environment.

## Authorisation:
All requests require an *api_token* request header attribute to be passed along with the request.  
This token can be found in the users table, and would typically be passed to the front-end during authentication.

## Rate Limiting:
Requests are throttled per domain and IP address.  
This limit can be configured in *routes/web.php* and applies to the entire routing group.

## Database Migrations & Seeding
Migrations and seeding factories have been provided for use with artisan.

To create a clean database, run:  
*php artisan migrate:refresh*

To re-seed the database, run:  
*php artisan db:seed*
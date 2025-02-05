## Number Fact API

A simple Laravel-based API that provides interesting facts and properties about numbers. It checks if a number is prime, perfect, Armstrong, and determines whether it is odd or even. Additionally, it fetches a fun fact about the number from the Numbers API.

# 📌 Project Description

Number Fact API is a simple Laravel-based API that provides interesting facts and properties about numbers.

### Features:

-   Determines whether a number is prime, perfect, Armstrong.
-   Checks if a number is odd or even.
-   Fetches a fun fact about the number from the Numbers API.

This API is designed for developers who need numerical properties and trivia for their applications.

## Seup Instructions
    Step 1: create a new project 
    Step 2: Run composer install
    Step 3: Run php artisan serve --port=9010
## Resources

Fun fact API: https://numbersapi.com/
https://en.wikipedia.org/wiki/Parity_(mathematics)

## API Documentation

Endpoint Url: https://hng-1-production.up.railway.app/api/number-fun-fact/371
Request/Response format:
    { 
        "number": 371, 
    "is_prime": false,  
    "is_perfect": false, 
    "properties": [ "armstrong", "odd" ], 
    "digit_sum": 11, "fun_fact": "371 is an uninteresting number."
    }

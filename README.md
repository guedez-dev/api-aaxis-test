API-Aaxis-Test Project Installation
-----------------------------------

This application allows managing protected resources via API by implementing Symfony JWT.


System Requirements:

* PHP 8.2
* Composer 2.x
* Symfony 7.2.x

1. Clone Repository:

`` git clone git@github.com:guedez-dev/api-aaxis-test.git``

2. Install Vendor Dependencies:

`` composer install``

3. Copy Environment File:

`` cp .env.dist .env``

4. Configure Database Connection:

Update the .env file with your database connection details. Refer to the provided example in the file.

5. Create and Verify Database:

`` php bin/console doctrine:database:create``

6. Run Migrations:

`` php bin/console doctrine:migrations:migrate``

7. Load Fixtures:

`` php bin/console doctrine:fixtures:load``

8. Generate JWT Authentication Keys:

``php bin/console lexik:jwt:generate-keypair``

9. Start Symfony Server:

`` symfony server:start``

API Testing
===========

1. **Authentication**
   
Perform API authentication using cURL:\

``curl -X POST -H "Content-Type: application/json" http://127.0.0.1:8000/api/login_check -d '{"username":"admin@info.com","password":"admin_123"}'
``

 If the provided JSON information is correct, you will receive a token in the response. Use this token in subsequent resource requests.

2. **Get List of Products**

Route: /api/products

Example cURL request:

``  curl --location 'http://127.0.0.1:8000/api/products' --header 'Authorization: BEARER [TOKEN]'
``

3. **Create Products**

Route: /api/create-products

4. **Update Products**

Route: /api/update-products

Feel free to start testing your API queries!

To view a more comprehensive example of each resource, you can import the included example Postman collection from the project.
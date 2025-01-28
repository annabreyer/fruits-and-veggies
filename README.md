This repository is the implementation of https://github.com/tturkowski/fruits-and-vegetables

It uses Symfony 7.2 with Swagger for the API Documentation and Doctrine for the entities. 
After installing the project, change the .env for your database info (login and PWD) and create the database with:  
bin/console doctrine:database:create  
bin/console doctrine:migrations:migrate  

Use symfony server:start to start the webserver and navigate to http://127.0.0.1:8000/api/docs to test the API. 

I would recommend testing the Add route first, with the query.json from tturkowski's repo. After that, you have enough
data in the DB to test the getAll and search function vie the Sawgger UI. 

The project has PHPStan and PHP-CS Fixer installed. 

It has a few tests, but because of timeboxing there was no time to write the API Call tests. 
I would have done it if I had more time.


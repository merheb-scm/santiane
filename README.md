## Santiane APP

This project is developed using Laravel Framework.
It is using **Laravel Sail** which is a module that configure **Docker** with the following container: nginx, mysql, redis and mailhog

In order to run this project, first we need to run the containers and then migrate the database and optionally seed some fake data into the database.
Run these commands from the root folder 
- **composer install**
- **./vendor/bin/sail up -d**
- **./vendor/bin/sail artisan migrate**
- **./vendor/bin/sail artisan db:seed**


The project contains 2 main pages: 
- the listing page that list all the voyages. for each voyage, we have a summary of the departure and arrival cities, and the departure and arrival time.
    - to view the steps of a selected voyage, click on the + (green sign) and it will list the sorted steps.
    - to edit (add/modify steps) click on the edit button
    - to delete the entire voyage and all its related steps, click on the delete button
    - to create a new voyage click on the "Create new Voyage" button
- the edit page, allows us to create or edit existing steps in voyage. if it is the first step, then when saving it will create the corresponding voyage in the database.
 The Seat, Gate, Baggage Drop are displayed according to the selected step type
 The rules for creating/editing a step are implemented in the StoreStepRequest file and also some additional rules are implemented directly in the controller.
 We are verifying the departure and arrival dates when creating/editing new step but we are not verifying the chain of cities.
 
 In addition, I created a faker to generate fake data in the database.
Finally, i created some test to make sure that we are able to create and save voyages and steps.
To run tests call: **./vendor/bin/sail artisan test** 
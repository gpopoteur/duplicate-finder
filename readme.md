# Duplicate Finder

Find duplicate users from a Database.

## Customer Structure

I tried to keep it as simple as possible. Ended up going with this structure:

```
first_name: string
last_name:  string
email:      string
gender:     string
last_ip:    string
```

## Duplicate Finder

To find a possible duplicate I divided the process into 2 steps, the first step is getting the necesarry data from the Database, the second step is processing and filtering the information.

In the first step I look in the Database for customers that have the same local-part of the the email address, **OR** customers that have the same First Name and Last Name as the customer being evaluated, **OR** customers that their last IP used is the same one as the customer being evaluated used, **AND** that the ID is different from the current user (just to exclude the user being evaluated).

After I get the dataset I start comparing the selected customer to each one of the customers in the dataset and give a weight to the comparison. The weight is calculated following a simple algorithm where I apply a value to different similarities, the value for the weight has been applied by me following the logic that a similar email address (or local-part of the email address) and First name might belong to the same person, those get a higher weight, while the other parts of a customer, like Last Name or Gender get a lower weight. After all this is calculated, the different weights get added to a final result, if this result is greater than an specified threshold (in this case 50) we might be infront of a possible duplicate.

This was the weight map that I applied:

```
$weightMap = [
    'email'             => 100,
    'email_local_part'  => 30,
    'first_name'        => 20,
    'last_name'         => 10,
    'gender'            => 10,
    'last_ip'           => 15,
];
```

Everything related to the Duplicate Finder can be found in the following files:

```
# Implementation
app\Contracts\DuplicateFinder.php
app\Finders\DuplicateFinder.php
app\Http\Controllers\CustomerDuplicatesController.php

# Tests
app\tests\functional\CustomerDuplicatesControllerTest.php
app\tests\unit\DuplicateFinderTest.php
```

All other tests are for the CRUD side of the application.

## Separation of concerns

Even though the project was small I tried my best to keep all concerns separated, used dependency injection and Laravel's IoC container to get a decoupled code base. I think this is really important for medium size to large projects, really small projects won't benefit a lot from this.

I decided to go following the Repository Pattern for the CRUD part of the application, wanted to make it really easy to swap implementations if a decision to change ORM or DBMS is made :) . Again, for such a small project making a tight coupled app could be OK, but for medium size to large size projects I think this makes more sense for maintainability purposes. :)
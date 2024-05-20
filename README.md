# Discounts Solution

# Introduction
A service that receives a purchase order to calculate the allowed discounts for this order
Initially these are the possible discounts:
- A customer who has already bought for over € 1000, gets a discount of 10% on the whole order.
- For every product of category "Switches" (id 2), when you buy five, you get a sixth for free.
- If you buy two or more products of category "Tools" (id 1), you get a 20% discount on the cheapest product.

The service is implemented to be able to be extended and more discounts can be added dynamically

# Structure
- `slim v4` framework
- `php v8.3`
- docker image `php:8.3-fpm` a docker container with the name `discounts_app`.
- database `postgres` installed in a container with name `discounts_db`
- nginx for running the server installed in a container with name `discounts_webserver`
- `phpunit` for automation test

# Ideas of Implementation

## Idea 1 (not implemented)

### Idea
Handling the implementation all in code using Strategy design pattern and create 2 interfaces to be implemented for each discount rule and the corresponding action

### Pros
Everytime new discount added, Developer will just implement these 2 interfaces in the new rule directory and add the needed logic inside them.

### Cons
- If we have 100 discount then project will require 200 classes to be added for implementing the logic of the rule and the action
- Only developer is the one who will be able to add the new discount

## Idea 2 (The implemented one)
- Use the database for saving the required rule of the discount and the corresponding action
- Select from the database (Rule Table) all the possible discount rules and apply them dynamically
- Use Factory design pattern for creating the corresponding rule dynamically which is also implemented in Strategy design pattern
- Once the Rule applies will also apply the needed action which is also saved in the database in the same record of the rule

### Pros
- No need for creating a class for each discount rule, also action classes are also implemented to be generic
- Later on a normal user can add another discount rule based on the implemented rule cases.
- Even if a class of new rule or a new action is required to be implemented by developer, It's not going to be that large number of classes in (Idea 1)

### Cons
- A good understanding of [the documentation](#Documentation) for adding new rules and actions.

# Installation

## Prerequisites
- Install [docker](https://docs.docker.com/engine/install/).
- Install [docker-composer](https://docs.docker.com/compose/install/).

## Installation commands
- After cloning create `.env` in the project root `teamleadercrm-coding-test/problem-1-discounts-solution/discounts`.
- copy what's in [.env.example](./problem-1-discounts-solution/discounts/.env.example) into your new `.env` file.
- Go to the directory `teamleadercrm-coding-test/problem-1-discounts-solution` in the terminal.
- Run `docker-compose up`
- Run the below command to access the bash of the container.
  `docker exec -it discounts_app bash`
- Inside the container run the below commands
```
composer install

./vendor/bin/phinx migrate --configuration=config/phnix.php

./vendor/bin/phinx seed:run --configuration=config/phnix.php
```
- Run the tests to make sure the project is running successfully
```
./vendor/bin/phpunit tests/Unit/
./vendor/bin/phpunit tests/Functional/
```

# Usage
- If you are using postman you can import [this](./Api-doc/discounts.postman_collection.json) postman collection and use it.
- If you are using other tool than postman use [this](./Api-doc/discounts.yml) api documentation.
- Request body [samples](Api-doc/example-orders/)
- Response body [samples](Api-doc/example-response)

# Documentation
## Database
- `discounts` database is created with having one table called `rules`
- `rules` table structure is as below
```
#[ORM\Id] #[ORM\GeneratedValue(strategy: "AUTO")] #[ORM\Column(type: "integer")]
#[ORM\Column(name: "type", type: "string", length: 255, enumType: RuleType::class)]
#[ORM\Column(name: "subtype", type: "string", length: 255, enumType: RuleSubtype::class)]
#[ORM\Column(name: "condition_key", type: "string", length: 255)]
#[ORM\Column(name: "operator", type: "string", length: 255, enumType: Operator::class)]
#[ORM\Column(name: "target_value", type: "string", length: 255)]
#[ORM\Column(name: "action_type", type: "string", length: 255, enumType: RuleActionType::class)]
#[ORM\Column(name: "action_value", type: "string", length: 255)]
#[ORM\Column(name: "reason", type: "string", length: 255)]
#[ORM\Column(name: "action_key", type: "string", length: 255)]
```

## Code 
- Implementation is added to be divided into 3 rule bases that the discounts will count on them:
1. Customer based rule -> depending on customer data
2. Order based rule -> depending on the order details.
3. Product based rule -> depending on the product details

- These 3 based rules which are structured in `App\Enums\RuleType` and saved in `type` column


- Rules are selected from database and divided into 3 separated objects depending on the `type` of the rule


- After separation if there are rules in `customer` `type` then we get the customer data from the external endpoint using `App\Services\ExternalAPI\CustomerClient`


- if `product` `type` exists then we get the products data filtered by order items ids from the external endpoint using `App\Services\ExternalAPI\ProductClient`


- Assuming that the customer and products data are called from external APIs, Endpoints are implemented in `App\Controllers\ExternalController` to simulate the external APIs and call it when needed using `App\Services\ExternalAPI\ExternalAPI` Service.


- After separating by rule type, depending on `App\Enums\RuleSubtype` which is saved in `subtype` column, rule will be dynamically created by using `App\Services\DiscountRules\RuleFactory`


- Order based rule (order type), and Customer based rule (customer type) are mainly added for direct discount condition on a clear attribute of Order, item, or customer objects.


- As Product based rule has no specific logic as it will be depending on the product data from external API, rule will be implemented case by case but in a generic way.


- Same as Product based rule, Actions will also have no specific logic, and will also be implemented case by case, but also in generic way that can be reusable


### Solving requirements
- Check the rules [seeds](./problem-1-discounts-solution/discounts/db/seeds/rule_seeds.php) for the problem 3 requirements

1. customer who has already bought for over € 1000, gets a discount of 10% on the whole order.
   - Used Types:
     - `RuleType::CUSTOMER`, and `RuleSubtype::CUSTOMER`
   - Used Rule:
     - `App\Services\DiscountRules\CustomerRule` -> will be applied on `condition_key` `revenue`
     - This rule also can be applied on any other customer attribute.
   - Applied Action:
     - `App\Services\DiscountActions\PercentageOnTotalAmount`


2. For every product of category "Switches" (id 2), when you buy five, you get a sixth for free.
   - Used types:
     - `RuleType::PRODUCT`, and `RuleSubtype::CATEGORY_QUANTITY` will be used.
   - Used Rule:
     - Generic rule of `App\Services\DiscountRules\CategoryQuantityRule` mapped to `RuleSubtype::CATEGORY_QUANTITY` is created counting category bought quantity
   - Applied Action:
     - `App\Services\DiscountActions\FreeProductsOfCategoryPerQuantity`

3. If you buy two or more products of category "Tools" (id 1), you get a 20% discount on the cheapest product.
   - This requirement will use the same types and rule of the second requirement but with different action
   - Applied Action:
     - `App\Services\DiscountActions\PercentageOnCheapestBought`


- `RuleType::Order` contains `RuleSubtype::ORDER` and `RuleSubtype::ITEM` which handle the condition on order or items bought attributes
  - No requirements added for that, but it will be a matter of saving new record if related requirement added

### Other rules table columns explanation

- Conditions are added to the columns condition_key, operator `App\Enums\Operator`, and `target_value` and then calculated with `App\Helpers\OperatorsHelper::evaluateCondition`


- If condition of the rule is valid then Action should be applied


- Actions is saved in `action_type` column which is mapped to `App\Enums\RuleActionType`, and will also dynamically created by using `App\Services\DiscountActions\ActionFactory`


- Action value is saved in `action_value` column which can be percentage or count that will be applied.


- `action_key` is added if needed for a specific action condition or for the return discount explanation


- `reason` is added for returning the discount requirement that will map to the reason of applying the discount


- Return applied discounts is using `App\Services\DiscountSerializer` for returning each valid the calculated discounts and corresponding reason


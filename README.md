# User Service

This service was created to fulfill the following task:

```
Write a simple API (using the Symfony Framework) to display a list of users, create new
users, and change or delete an existing user. The aim is to exchange the data source (e.g. a
database, an XML file, ...) for users without having to touch the code that uses the data
source and returns the response. Please provide documentation on how to use the API. It
would be great if you could send us your answer with a GitHub link and a small ReadMe file.
Have fun!
```

## Setup
### Requirements
* Docker

### Installation

* Clone this repository in a directory of your choice
* Execute `docker-compose build` in the same directory you find the `docker-compose.yml`- File
* Wait until the Build process has been finished
* Execute `docker-compose up -d` in the same directory
* Wait until the PHP Container finished its initial workload
* Visit `localhost/api/doc` for the open api documentation

`Note: The php container is doing a lot of work during startup. You can check its progress with 'docker logs <docker-container-id> -f'.`

### Running fixtures

To load the database with test data run:

``
docker-compose exec php bin/console hautelook:fixtures:load --no-bundles
``

This will create 9 random users along with one predictable one. 

### Running tests

The Crud Operations are tested with functional tests.
To execute tests run:

```
docker-compose exec php vendor/bin/phpunit
```

## Documentation

The Api Endpoints for the operation concerning the user are documented via an open api implementation.
To visit the documentation use following url:
``
http://localhost/api/doc
``

## Switch the data source
To fulfill the task, there is an implementation for a second datasource.
All you have to do is to change the adapter to the user repository.
Following Adapters are valid:
* App\Repository\Adapter\FileAdapter: For a file datasource, currently supported are only xml files.
* App\Repository\Adapter\DatabaseAdapter: For a database as datasource, tested with mysql, but any database should be fine.

To change the adapter, edit following lines:
```yaml
    App\Repository\UserRepository:
        arguments:
---         $adapter: '@App\Repository\Adapter\FileAdapter'
+++         $adapter: '@App\Repository\Adapter\DatabaseAdapter'    
```


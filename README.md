# Decentralised Knowledge Management System

An implementation of a knowledge management system using Cassandra, although it has been tested with legal cases but can
be adapted into other use cases.

## API Documentation

The API documentation for this implementation can be found here [here](https://documenter.getpostman.com/view/7663075/2s9YJc1NCx)

## Tests

To ensure the functionality and reliability of the system, a comprehensive set of tests has been implemented. The test suite covers various aspects, including functionality, performance, security, and usability.

Run either of `php artisan test` or `sail artisan test` depending on how you have setup the project

## Installing Cassandra Driver

To interact with the Cassandra database used in the Decentralised Knowledge Management System, follow the instructions here -
https://github.com/he4rt/scylladb-php-driver

I've included the repository as at the time of publishing this in the docker directory. You can install the driver on the PHP 8.2
Dockerfile that is provided in this repository. You may also modify the Dockerfile and combine with the instructions in the provided
Dockerfile on the repo.

## Contact

If you have any questions or need further assistance, please you can reach out to me via [Email](mailto:fabulousbj@hotmail.com)

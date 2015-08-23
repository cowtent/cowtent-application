# Cowtent

**Cowtent** is an evolutive **Content Application** both accessible by Web Browser and by **Rest Api**.

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cowtent/cowtent-application/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cowtent/cowtent-application/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/cowtent/cowtent-application/badges/build.png?b=master)](https://scrutinizer-ci.com/g/cowtent/cowtent-application/build-status/master)

Main features:
 - Multiple accounts *(like Saas profiles)*
 - Multiple users by account
 - Multiple applications by account *(Rest Api entry point)*
 - Multiple workspaces by account
 - Multiple branches by workspace *(eg: MASTER for Production environment)*
 - Polymorphic content types
 - Content versionning
 - Sync functions between branches on a same workspace
 - ...

**Still under hard development !**

Rest Api is built using Json format exchange with [WSSE security authentication](http://symfony.com/doc/current/cookbook/security/custom_authentication_provider.html#meet-wsse).

## Requirements

### System

Based on top of Symfony 2.7.x, main requirements are dicted by Symfony itself:
 - PHP >= 5.3.9
 - PHP Modules:
   - php5-curl
   - php5-gd
   - php5-intl
   - php5-mysql
   - php5-mongo
   - php5-mcrypt
   - php-apc for PHP 5.4
   - php5-apcu for PHP 5.5
 - MySQL 5.1 or above (or compatible like PerconaDB)
 - MongoDB 3.0 or above
 - Apache mod rewrite enabled

## Installation instructions

### Install Composer

If you don't have Composer yet, download it following the instructions on [getcomposer.org](http://getcomposer.org/) or just run the following command:

````
$ curl -sS https://getcomposer.org/installer | php
````

Please note that you will certainly need to provide your GitHub credentials with this method, A lot of our dependencies are coming from GitHub and this reaches the max limit of 50 API calls from anonymous users.

### Create a project with Composer

````
$ php composer.phar create-project --prefer-dist cowtent/cowtent-application ./cowtent-project --stability=dev
````

### Write permissions

As for each Symfony2 projects, rights must be adapted for some folders:
 - app/cache
 - app/logs

Read this article if you need more details:
http://symfony.com/doc/current/book/installation.html#checking-symfony-application-configuration-and-setup


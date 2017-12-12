# WiseArtisan

A command-line extension for Php7 Laravel framework.

## compose:controllers

Create a set of controllers (and its corresponding views and test files):

$ php artisan compose:controllers first second third

Create a set of controllers with resources (and its corresponding views and test files):

$ php artisan compose:controllers first second third --resources

## compose:controller

Create a single controller and its actions (and its corresponding views and test file):

$ php artisan compose:controller name action1 action2 action3

## compose:migration

Create a migration (a model and a test for it) and migrate it:

$ php artisan compose:migration create_migrations_table --schema="date:date,name,age:int,user:int:unsigned:foreign"


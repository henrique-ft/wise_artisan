# WiseArtisan

A little extension for Laravel Framework that join some of Artisan commands in one, for more productivity.

## compose:controller

Create a single controller and its actions, its corresponding views and a test file:
```bash
$ php artisan compose:controller name action1 action2 action3
``` 
## compose:controllers

Create a set of controllers and its corresponding views and test files:
```bash
$ php artisan compose:controllers first second third
``` 
Create a set of controllers with resources and its corresponding views, test files and requests files:
```bash
$ php artisan compose:controllers first second third --resources
``` 
## compose:migration

Create a migration, a model, a factory and a test for it, then migrate it:
```bash
$ php artisan compose:migration create_migrations_table --schema="date:date,name,age:int,user:int:unsigned:foreign"
``` 

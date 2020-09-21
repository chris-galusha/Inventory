# User Management

The web app has user management and authentication built-in to the project. Users can be managed by admins through the **administrator panel** in the **user management** section.

## Overview

* [Default Admin Account](#admin)
* [Roles](#roles)
* [Users](#users)
* [Logging and Accountability](#log)

## Default Admin Account <a name='admin'></a>

The web app installer automatically creates one admin user that can be used to manage users and the database. By default the admin's email address is "admin@example.com" and its password is "Administrator". The admin's login credentials can be changed after logging in once (or by directly modifying the SQL). There must always be one admin account registered.

## Roles <a name='roles'></a>
User permissions are managed by roles. The three default roles are:

* Admin
  * Unrestricted privileges
* Normal User
  * Prevented from accessing the administrator panel, and from deleting items
* Guest
  * Prevented from modifying the database in any way, and exporting data

Every user is assigned a role, with guest being the default role. Users who are not logged in will be treated as guests.

## Users <a name='users'></a>

All current registered users can be found in the user management section of the administrator panel. They are color coded by their role.

#### Creating Users

There are two ways to create a new user:
* Admins can create new users in the user management section
* Visitors can register an account, and will automatically be given the guest role

#### Editing and Deleting Users
Clicking on a user will display a summary of them. From there an admin can click on the 'edit' button to go to a form where the user's traits can be modified. Here a user's name and email can be changed, password reset, role modified, or the account can be deleted.

There are a few rules regarding creating and editing users:

* A user cannot edit their own role
* There must be at least one admin at all times
* An email address can be registered with only one account at a time

## Logging and Accountability <a name='log'></a>

Most significant user actions are logged automatically. These logs are automatically generated every day, and can be found at `storage/logs/laravel-YYYY-MM-DD.log`.

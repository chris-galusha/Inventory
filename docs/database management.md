# Database Management

Inside the web app itself, a number of useful database management tools have been provided in the administrator panel, which can be accessed by logging in as an admin and clicking on the 'Administrator Panel' button in the top right.

## Overview

* [Deleting and Restoring Items](#delAndRes)
* [Backing Up the Database](#backup)
* [Recovering the Database](#recover)
* [Creating Columns](#create)
* [Modifying Columns](#modify)
* [Deleting Columns](#delete)

## Deleting and Restoring Items <a name='delAndRes'></a>

Inside the administrator panel, an admin can click on the "Manage Deleted Items" button. They will be taken to a page listing all the items that have been marked for deletion. They can then select one or more items, and either permanently delete them or restore them. Shortcut buttons are also available for deleting or restoring all items marked for deletion.

## Backing Up the Database <a name='backup'></a>

A database backup can be ran from the administrator panel by clicking the "Backup Database" button. The resulting `.sql` backup file will be stored in `/path/to/project/folder/storage/backups/YYYY-MM-DD HH:MM:SS.sql`.

> The database is automatically backed up twice a day by default (see [Cron Jobs](cron%20jobs.md)).

## Recovering the Database <a name='recover'></a>

An administrator can recover a previous state of the database by clicking on the "Recover Database" button in the administrator panel. This will take the admin to a page listing all the backups in the `/path/to/project/folder/storage/backups` directory, ordered by most recent first. Clicking on a backup will restore the database using the backup clicked on. If an admin instead wants to upload their own `.sql` file, there is an option to do so at the bottom of the page.

## Creating New Columns <a name='create'></a>

New columns, or fields, can be added to items by going to the administrator panel and clicking on the "Database Columns" button. The admin will be taken to a page where every column is listed. At the bottom of the page, a "New Column" button can be clicked to take the admin to a new column creation page. A column has 7 properties:

* Display on Front Page
  * If the column should be displayed in the table on the home page
* Database Name
  * Name for the database to refer to the column
  * Only lowercase letters, numbers and underscores
  * Must be unique
  * Cannot be changed once set
* Display Name
  * Human readable name
* Data Type
  * Type of data the column should hold
* Values (Dropdowns Only)
  * The possible values a dropdown can have
* Protected from Editing
  * Column's value cannot be edited
* Required
  * Column cannot be empty

## Modifying Columns <a name='modify'></a>

Columns can be edited by going to the administrator panel and clicking on the "Database Columns" button. The admin will be taken to a page where every column is listed. Then the admin can click on a column, and hit the "Edit" button. Here they are met with the properties of the column they are allowed to change. The column's database name cannot be changed. When finished, the admin can press the "Update Column" button to apply their changes.

If the column is a dropdown or a text field, the admin can change the type to the other (a dropdown can be made a text field and vice versa) by clicking the "Change type to Text/Dropdown" button.

## Deleting Columns <a name='delete'></a>

Columns can be deleted by going to the administrator panel and clicking on the "Database Columns" button. The admin will be taken to a page where every column is listed. Then the admin can click on a column, and hit the "Edit" button. Finally they can click the "Delete Column" button to delete the column.

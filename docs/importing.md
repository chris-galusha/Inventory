# Importing
> Requires Normal User Authorization (see [User Management](user%20management.md))

Adding data to the database can be done through the importing tab on the home page.

## Overview

* [Uploading Data](#uploading)
* [Mapping Columns](#mapping)
* [Import Options](#options)
* [Importing](#importing2)
* [Special Cases](#special)

## Uploading Data <a name='uploading'></a>

The first page of the importing process requires the user to upload a `.csv` file containing the data to import. They must provide the *delimiter* (the character separating each column, defaults to a comma) and whether or not the first row is a header.

## Mapping Columns <a name='mapping'></a>

The second page requires the user to map the columns in the uploaded file to the columns in the web app's database. On the left are the columns from the file, and on the right are drop-downs allowing the user to select the corresponding database column. Each column may be selected only once, and if a column from the file should be ignored, leave the drop-down set to "Ignore". If you are importing a file exported from Financial Edge, this mapping process will be done automatically.

## Import Options <a name='options'></a>

There are four different types of imports a user can run:

* Keep Existing Records
  * Will only import new records, will not modify old records in any way

* Update Existing Records
  * Will modify old records to match new records
    * Will **not** empty columns in the old records to match new

* Overwrite Existing Records
  * Will modify old records to match new records
    * Will empty columns in the old records to match new
    * Columns in the old records that are not in the new records are untouched

* Replace Existing Records
  * Old record is deleted and replaced by new record

## Importing <a name='importing2'></a>

When the user presses the Import button, the server attempts to import all uploaded items. Upon completion, the user will be redirected to the home page, and given a notification stating how many items were imported. This action is logged.

## Special Cases <a name='special'></a>

Several columns are enforced as unique, such as:

* Serial Number
* FE ID
* Inventory Number

Should the server detect a duplicate during the import, the duplicate will be logged at `storage/logs/laravel-YYYY-MM-DD.log` and then tossed.

# Creating and Updating Items
> Requires Normal User Authorization (see [User Management](user%20management.md))

Creating new items and updating existing ones in the database is supported directly through the web app's interface.

## Overview

* [Creating Items](#create)
* [Updating Items](#update)
* [Validation](#valid)

## Creating Items <a name='create'></a>

Aside from importing them, there is another way to add items to the database by creating them directly through the web app's interface. On the home page, click on the green 'New Item' to get started. This will take you to a form where you can create a new item by entering values for the desired fields, and hitting the 'Create Item' button at the bottom of the form.

#### Creating Multiple Items

The last field of the new item creation form is the number of items to create. If this value is larger than one, all [unique fields](#unique) must be empty to prevent multiple items having the same unique field.

## Updating Items <a name='update'></a>

Once an item is created, its fields can be modified by clicking on the item on the home page, then hitting the edit button. This takes the user to a screen similar to item creation, where all the item's fields are listed, and their values can be edited. Columns are divided into two sections: visible on the home page and hidden on the home page.

#### Updating Multiple Items

Multiple items can be updated at one time, by selecting two or more items (see [Searching, Sorting and Selecting](searching,%20sorting%20and%20selecting.md)), and clicking the 'Update Item' button.

This will take the user to a form very similar to the individual item editing form, but with additional checkboxes to the left of every field. A field will only be updated if the checkbox to the left is checked. This allows the user to fill a column with an empty value intentionally for all selected items.

Similar to creating a new item, unique fields cannot be updated (but can be emptied) if more than one item is selected.

## Validation <a name='valid'></a>

The web app will automatically validate new item and update item requests with the following rules:

###### Special Rules <a name='unique'></a>
* Description
  * Required
  * At least 3 characters
* FE ID
  * Unique
* Inventory Number
  * Unique
* Serial Number
  * Unique

###### General Rules
* Text and Drop-down:
  * Valid Text
  * Max 255 characters
* Text Area:
  * Valid Text
* Number:
  * Valid Integer
* Number with Decimal:
  * Valid Decimal Number
* Checkbox:
  * Valid Boolean (True or False, 0 or 1)
* Date and/or Time:
  * Valid Date and/or Time in the format: mm/dd/yyyy hh:mm AM/PM
* IP Address:
  * Valid IP Address (IP v6 or v4)

If a request fails the validation, the user will be sent back to the item creation or updating page with an error message to fix their incorrect input.

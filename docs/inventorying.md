# Inventorying
> Requires Normal User Authorization (see [User Management](user%20management.md))

Items can be marked as inventoried by setting their 'Last Inventoried' field to a date. This also allows a user to see the exact date the item was last inventoried. Items with no entry in this field are assumed to have never been inventoried.

## Overview

* [Inventorying an Item](#inv)
* [Inventorying Multiple Items](#deccMult)
* [Inventorying Items by CSV](#csv)
* [Getting Inventory Statistics](#stats)

## Inventorying an Item <a name='inv'></a>

To inventory an item, click on the item in the table on the home page, hit the 'Edit' button, and set the 'Last Inventoried' field to any date. If you want to set the 'Last Inventoried' field to today, there is a shortcut for this. Instead, select the item you want to inventory (see [Searching, Sorting and Selecting](searching,%20sorting%20and%20selecting.md)) and click the 'Inventory' button on the home page. This will take you to a confirmation page. Making the 'Last Inventoried' field empty will set the item as never having been inventoried.

## Inventorying Multiple Items <a name='multiple'></a>

Inventorying multiple items works exactly as [updating multiple items](creating%20and%20updating%20items.md). Select all the items you want to inventory, and perform the same steps as explained in [Inventorying an Item](#inv) above.

## Inventorying Items by CSV <a name='csv'></a>

If a user has a list of inventory numbers of items they wish to inventory, they can upload a .csv file containing those inventory numbers and inventory them all together. This can be done by clicking on the 'Inventory' button and then clicking on the 'Inventory from CSV' button.

## Getting Inventory Statistics <a name='stats'></a>

If a user wanted to collect all items that are inventoried, not inventoried, inventoried before or after a specific date, etc., they can use the [Advanced Filter](filtering.md), scroll to the 'Last Inventoried' field, and adjust the settings accordingly. For example, to get all inventoried items, a user could simply set the search modifier for 'Last Inventoried' to 'Only Nonempty Fields'.

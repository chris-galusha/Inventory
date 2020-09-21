# Decommissioning
> Requires Normal User Authorization (see [User Management](user%20management.md))

Items can be marked as decommissioned by setting their 'Date Decommissioned' field to a date. This also allows a user to see the exact date the item was decommissioned. Items with no entry in this field are assumed to be in commission. Setting the item's status to 'Decommissioned' is also a good idea, though it is not used by the system to decide if an item is decommissioned.

## Overview

* [Decommissioning an Item](#decc)
* [Recommissioning an Item](#recomm)
* [Decommissioning Multiple Items](#deccMult)
* [Decommissioning Items by CSV](#csv)
* [Getting Decommission Statistics](#stats)

## Decommissioning an Item <a name='decc'></a>

To decommission an item, click on the item in the table on the home page, hit the 'Edit' button, and set the 'Date Decommissioned' field to any date. If you want to set the 'Date Decommissioned' field to today, there is a shortcut for this. Instead, select the item you want to decommission (see [Searching, Sorting and Selecting](searching,%20sorting%20and%20selecting.md)) and click the 'Decommission' button on the home page. This will take you to a confirmation page. Making the 'Date Decommissioned' field empty will set the item as never having been decommissioned.

## Recommissioning an Item <a name='recomm'></a>

If an item or group of items are decommissioned and the user wants to recommission them, there is a shortcut 'Recommission' button on the home page. Selecting the decommissioned items and hitting the button will empty the 'Date Decommissioned' field and set the status to 'Not Specified'.

## Decommissioning Multiple Items <a name='deccMult'></a>

Decommissioning multiple items works exactly as [updating multiple items](creating%20and%20updating%20items.md). Select all the items you want to decommission, and perform the same steps as explained in [Decommissioning an Item](#decc) above.

## Decommissioning Items by CSV <a name='csv'></a>

If a user has a list of decommission numbers of items they wish to decommission, they can upload a .csv file containing those decommission numbers and decommission them all together. This can be done by clicking on the 'Decommission' button and then clicking on the 'Decommission from CSV' button.

## Getting Decommission Statistics <a name='stats'></a>

If a user wanted to collect all items that are decommissioned, not decommissioned, decommissioned before or after a specific date, etc., they can use the [Advanced Filter](filtering.md), scroll to the 'Date Decommissioned' field, and adjust the settings accordingly. For example, to get all decommissioned items, a user could simply set the search modifier for 'Date Decommissioned' to 'Only Nonempty Fields'.

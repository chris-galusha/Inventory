# Searching, Sorting and Selecting

## Overview

* [Searching](#searching)
* [Sorting](#sorting)
* [Selecting](#selecting)

## Searching <a name='searching'></a>

On the home page, users have several options available to them to search for, sort, and select specific items. The most obvious and most basic is the search bar. Simply type a query like 'dell' and hit enter or the search button, and the page will refresh with all the items that match that query.

##### Filtering

Basic filtering can be done by hovering over the 'Filter' button to the right of the search bar, and selecting what column to search in, as well as some additional settings such as:

* Case Sensitive
  * Query will be case sensitive
* Include Decommissioned
  * Query will match decommissioned items
* Include Hidden Fields
  * Query will match fields that are hidden on the home page

For more advanced filtering, see [Filtering](filtering.md).

## Sorting <a name='sorting'></a>

After a query runs, and the page refreshes, all the matched items will appear in the table on the home page. The user has several options for sorting these items.

##### Sorting by Column

Each column header in the table has arrow icons to the right. Clicking anywhere on a column header will refresh the page, sorting the items in the table by the column clicked on alphabetically. It will toggle between A-Z (Up Arrow) and Z-A (Down Arrow).

##### Pages

The query will automatically split the results into pages. Flipping between pages can be done by clicking on a page number in the menu below the table.

###### Number of items per page

The drop-down button to the right of the advanced filter determines how many items are shown per page (default: 15). Changing this drop-down will refresh the page, changing the number of items per page appropriately.

## Selecting <a name='selecting'></a>

In order to perform any action on an item (like inventorying or decommissioning), the user must first select the item or items. This can be done several ways.

##### Searching and Filtering

When a user runs a search or uses the advanced filter, all items that match the search query are automatically selected. The number of items currently selected by the query can be seen in the "Items Fetched By Query" field below the search bar.

##### Checkboxes

On the home page, every item in the table has a checkbox on their left. Checking this will select the item, and override any other selection.

##### By CSV

On the home page, to the right of the items per page drop-down, there is a "Select Items by CSV" button that allows the user to upload a csv file with inventory numbers (one per line) of items in the database. Then the system will automatically select these items for the user.

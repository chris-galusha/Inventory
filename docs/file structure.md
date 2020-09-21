# File Structure Overview

If the code is ever needed to be changed or evaluated, here is a quick overview of the file structure. It notes where specific types of files are stored, for what purpose those files serve, and some various points of interest.

* ```App``` is the folder where most of the logic is stored.
  * ```Console``` contains all the commands and cron jobs.
  * ```Helpers``` contains a helper file filled with functions used by several controllers.
  * ```Http```
    * ```Controllers``` contains all the web app's controllers, which perform all the primary functions like inventorying, decommissioning, etc.
    * ```Middleware``` contains all middleware, logic that is performed every time a user tries to load a page (ex. authentication and verification).
    * ```Requests``` contains specialized validators that ensure certain user requests do not contain invalid data.
  * ```Mail``` contains logic related to creating and sending specific emails.
  * Files like ```User.php``` and ```Item.php``` inside ```App``` are database models representing users, items, etc.
* ```database``` contains all other database related code.
  * ```migrations``` contains files that represent tables in the database.
* ```resources``` contains all front-end related files, such as css/sass styling, javascript, html, etc.
* ```routes``` contains all registered routes a user can go to (ex. /items or /admin), and what the web app should do when a user goes to a route.
  * ```web.php``` is the file where all routes for this project are stored. They are grouped by the authentication level required to access them.
* ```storage``` is the app's storage directory. Backups, reports, exports, and logs can be found here.

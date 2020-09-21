# Reporting
> Requires Normal User Authorization (see [User Management](user%20management.md))

Users can create reports by saving an advanced filter query (see [Filtering](filtering.md)). The components of a report are as follows:

* Name
  * The *unique* name of the report
* Description
  * A description of the report
* Query
  * The query associated with the report (created by the advanced fitler)
* Frequency and Time
  * How frequent and at what time the report should run
* Email Address
  * The email address the report will be emailed to
* Active
  * If the report is active or not (inactive reports do not run automatically)

The app will automatically schedule [cron jobs](cron%20jobs.md) for each report. If the report is active, the report will be ran and compiled, and an email containing that report will be sent to the specified email address at the specified time and date.

Reports can be managed by clicking on the 'Reporting' button on the home page. Should a report fail to send, an error will be logged and if the report was ran manually, the user will receive a notification.

## Validation

Every field of the report is validated using the following rules:

* Name
  * Required
  * Maximum of 255 characters
  * Minimum of 3 characters
  * Unique
* Description
  * Required
  * Maximum of 255 characters
  * Minimum of 3 characters
* Active
 * Yes or No
* Frequency
  * Valid Frequency (yearly, daily, etc.)
  * Required
* Email Address
  * Required
  * Valid Email
* Time
  * Valid Time (hh:mm)
* Day of Week
  * Valid Day of Week
* Day of Month
  * Valid Day of Month
* Query
  * Required
  * Valid Query

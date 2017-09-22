* [33mb623fc5[m[33m ([m[1;36mHEAD -> [m[1;32mlogbook-single-hits[m[33m)[m Add tests for logbook form display
* [33m2d6d3e0[m Add test when submitting 0 with no entries in a given timeslot
* [33mb4b45bc[m Add boolean field recorded_live to logbook_entries
* [33m6d729a9[m Extract LogbookEntry::within() method
* [33m2872608[m Submitting a 0 deletes all visits within a timeslot
* [33macf4e6c[m Restore form field names compatible with previous version to avoid break
* [33mc156d93[m Unauthenticated users cannot create logbook entries
* [33mb075516[m Migrate further logbook form functionalities and tests
* [33m28fcad7[m Add logbook entries count test assertion
* [33m3e991bd[m Allow insertion of multiple logbook entries simultaneouosly
* [33m06d11ab[m Implement LiveCounterController::subtract()
* [33m303522f[m Add more precise check in test
* [33m6b3dd52[m Implement LiveCounterController::add()
* [33m9f1d0c7[m Edit LogbookUpdateForm request, add test against entries in the future
* [33me02ca67[m First couple of tests for logbook entries creation
* [33md507119[m Create LogbookEntry model, controller, migration and factory
* [33m3b162b2[m Disable logbook tests
*   [33mc2c759e[m[33m ([m[1;32mdevelop[m[33m)[m Merge branch 'import-timeslot' into develop
[32m|[m[33m\[m  
[32m|[m * [33ma4999f9[m Update further tests
[32m|[m * [33m224d87e[m Pull in gpaddis/timeslot, update references
[32m|[m[32m/[m  
*   [33m4941c68[m Merge branch 'laravel-55' into develop
[34m|[m[35m\[m  
[34m|[m * [33m29d8a6d[m Update composer.json, rename LogbookUpdateForm::filled() cause name conflict
[34m|[m[34m/[m  
* [33m2cda7ee[m Rename logbook.visits_count to logbook.visits
* [33m384485d[m[33m ([m[1;32mmaster[m[33m)[m Refactor livecounter view, minor edits
* [33m56e5b35[m Implement custom date on update logbook form
* [33m9999037[m Add better blade controls to the views
* [33meb607d9[m Older logbook entries can be deleted submitting 0
* [33m18ec084[m Refactor live counter queries w eager loading
* [33ma11e6ad[m Refactor livecounter cards display logic
* [33md2bc673[m Add warning to logbook.update if data is already stored
* [33m3856b60[m Refactor views logbook.create -> logbook.update w eager loading
* [33m664a4bf[m Add TimeslotCollection class and tests
* [33m143e428[m Pull in barryvdh/laravel-debugbar
* [33m49c48dc[m Add custom css for btn-xs (livecounter)
* [33m034b8ab[m Switch livecounter from GET to POST, clean Entry methods
* [33mb9055c1[m Add test to CreateLogbookEntryTest
* [33mb327b5d[m Refactor models and views, implement primary and secondary pc
* [33md469647[m Add helper file, fetch existing values in the logbook form
* [33m42ac8ae[m Add Entry::within() query scope to filter with timeslots
* [33m62ce549[m Update all views to BS4, edit livecounter view
* [33me7255e4[m Remove .DS_Store files
* [33m8a10a4a[m Upgrade to bs4 views: logbook.create, livecounter.index
* [33mf6b9cdb[m Minor edit to main layout file
* [33mdf9e9e2[m Apply bootstrap grid to livecounter view
* [33m652d29e[m Fix show logbook update form w no active patron categories
* [33m5196da4[m Implement live counter
* [33mfb5753e[m Create Entry::subtract() method and relative tests
* [33m677b487[m Split app view and navbar
* [33me2436e1[m Refactor views, move patron categories one level up
* [33md38fb81[m Refactor LiveCounterController::store(), Entry::add()
* [33m2d256a8[m Extract EntryController::store() logic to a dedicated Request class
* [33m389f977[m Add LiveCounter validation tests
* [33m5bc02a1[m Create Entry::add() method, add a few relative tests, create route and controller
* [33mc4c5bee[m Add jQuery to the public/js directory
* [33m7ea78aa[m Refactor CreateLogbookEntryTest
* [33m6973c2e[m Disallow saving logbook entry with start date in the future
* [33m4ea99ba[m Update entry.create view, custom error messages, fix Timeslot::toArray() method
* [33m85efd5b[m Disallow sending an empty logbook entry form
* [33m54fe7e5[m Refactor Entry class and input form
* [33m5539332[m Implement EntryController@store method, validation and relative tests
* [33m8b62a6c[m Fix logbook routes, minor edits to the relative views
* [33m3797db6[m Refactor Timeslot class
* [33meb00d60[m Radically restructure application structure
* [33m539c60a[m Add CreatePatronCategoryTest and do some edit to the categories page
* [33m204bd1d[m Edit both category migrations
* [33m7f8693d[m Add Patron Category settings page
* [33m75b7776[m Minor edits to the Timeslot class
* [33md104bf2[m Change timeslot method default() to now()
* [33mbb34967[m Work on the visitslog form, update models
* [33mbaf2a43[m Add factories and tests
* [33mfbf3af3[m Add counter models
* [33mbb7360e[m Install Laravel

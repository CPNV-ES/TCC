# Administration of the site
Middlewares:
  * `app/Http/Middleware/AdminMiddelware.php`  
  * `app/Http/Middleware/Authenticate.php`  
  * `app/Http/Middleware/ProfileIsValideMiddleware.php`

Layout: `resources/views/layouts/admin.blade.php`  

### Conditions to access the pages
  * The user needs to be connected.
  * The user needs to have the admin rights.
  * The users account needs to be activated.

## Dashboard
This is the first page that greats an administrator when they connect to the site.

Controllers:
  * `app/Http/Controllers/Admin/AdminController.php`  
  * `app/Http/Controllers/Admin/MemberController.php`  

View: `resources/views/admin/home.blade.php`

### Displayed
  * The number of member on the site that have an account.
  * A list of accounts that need validation.
    * Create a username to activate the account.

### Creating new account
An administrator enteres a new login into the field and clicks "Activer".  
This calls the `updateLogin()` function in the `MemberController` whose only purpose is to update the username in the `users` table. Before adding the username to the database there is a check to see if the username already exists.   
An e-mail is then sent to the member with the username and a link so that he can create a password.

## Configuration
This section allows an administrator to manage certain parts of the site like adding a court, a new season or a new subscription. There are also other options like the opening and closing times for the courts, the number of simultanious reservation a member can have, etc.

### Courts
Controller: `app/Http/Controllers/Admin/CourtController.php`  
View: `resources/views/admin/configuration/courts.blade.php`

#### Displayed
  * Table with all the courts
    * Option to edit court
    * Option to delete court
  * Form to add a new court
    * Also serves as edit form

#### Adding
Add a new court.  
Fields:
  * Name
  * Open or not
  * Window of reservation for a member

#### Editing
Edit a court. The data is populated into the form for adding a court.
Fields:
  * Name
  * Open or not
  * Window of reservation for a member

#### Deleting


#### Improvements
  * Move form into modal
  * Add pagination to the table

### Seasons
#### Improvements
  * Move form into modal
  * Add pagination to the table

### Subscriptions
#### Improvements
  * Move form into modal
  * Add pagination to the table

### Other

## Members

## Statistics
Not implemented. Would have different statistics about the members.

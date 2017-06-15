# Administration of the site
Middlewares:
  * `app/Http/Middleware/AdminMiddelware.php`  
  * `app/Http/Middleware/Authenticate.php`  
  * `app/Http/Middleware/ProfileIsValideMiddleware.php`

Layout: `resources/views/layouts/admin.blade.php`  

### Conditions to access the pages
  * The user needs to be connected.
  * The user needs to have admin rights.
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
An e-mail is then sent to the member with the username and a link so that they can create a password.

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
**Fields**:
  * Name
  * Open or not
  * Window of reservation for a member

#### Editing
Edit a court. The data is populated into the form for adding a court.  
**Fields**:
  * Name
  * Open or not
  * Window of reservation for a member

#### Deleting
When the delete button is clicked a confirmation dialoge is displayed. The court is deleted depending on the administrators choise.  
**Condition**:
  * The court needs to have 0 reservations on it or the delete button isn't displayed.

#### Improvements
  * Move form into modal
  * Add pagination to the table
  * Add possibilitie to delete a court that has reservations by doing a soft delete. (will need to remove all futur reservations on that court and inform users that their reservations have been deleted)

### Seasons
Controller: `app/Http/Controllers/Admin/SeasonController.php`  
View: `resources/views/admin/configuration/seasons.blade.php`

#### Displayed
* Table with all the seasons
  * Option to delete season
* Form to add a new season

#### Adding
Add a new season. The fields are prefilled depending on the when the last season finished.  
**Fields**:
  * The start date
  * The end date

**Prefill**:
  * Start date: The end date of the last season + 1 day.
  * End date: The new start date + 1 year, - 1 day.

#### Deleting
When the delete button is clicked a confirmation dialoge is displayed. The season is deleted depending on the administrators choise.  
**Condition**:
  * The season needs to not be linked to a subscription.

#### Improvements
  * Move form into modal
  * Add pagination to the table

### Subscription types
Controller: `app/Http/Controllers/Admin/SubscriptionController.php`  
View: `resources/views/admin/configuration/subscriptions.blade.php`

#### Displayed
  * Table with all the subscription types
    * Option to edit subscription type
    * Option to delete subscription type
  * Form to add a new subscription type
    * Also serves as edit form

#### Adding
Add a new subscription type.  
**Fields**:
  * Type
  * Amount

#### Editing
Edit a subscription type. The data is populated into the form for adding a subscription type.  
**Fields**:
  * Type
  * Amount

**Condition**:
  * The subscription type needs to not be linked to a subscription.

#### Deleting
When the delete button is clicked a confirmation dialoge is displayed. The subscription type is deleted depending on the administrators choise.  
**Condition**:
  * The subscription type needs to not be linked to a subscription.

#### Improvements
  * Move form into modal
  * Add pagination to the table

### Other
Controller: `app/Http/Controllers/Admin/OtherOptionController.php`  
View: `resources/views/admin/configuration/other_options.blade.php`

#### Displayed
  * Form with all the options that can be modified.

#### Editing
By clicking the `Modifier` button all fields will be unlocked and can be modified. When the `Sauvegarder` button is clicked all fields are checked and then saved into the database.
**Fields**:
  * Number of simultanious reservation a member can have
  * Window of reservation for a non-member
  * The grace period (Number of days a user has until he can't connect to the account for the date it was deactivated)
  * Opening time for the courts
  * Closing time for the courts
  * Amount a non-member needs to pay per reservation

## Members
Controller: `app/Http/Controllers/Admin/MemberController.php`  
Views:
  * `resources/views/admin/configuration/member.blade.php`
  * `resources/views/admin/configuration/memberEdit.blade.php`

The main page displays a table with all the members, non-members and the people that were invited. Different filters can be applyed to help find specific members.  
There is also a button that takes the administrator to the `register` page to create a new member.

### Edit member
By clicking a member in the table more information on that member will be displayed in a new page.  
By clicking `Modifier` the administrator can edit the members information, change their roles, etc.
**Fields**:
  * Lastname
  * Firstname
  * Address
  * Address number
  * City
  * E-mail
  * Phone number
  * The different roles
    * Administrator
    * Staff
    * Member
  * Some options
    * If the account is activated
    * If the user needs to check their information
    * If the user has the right to invite a person

**Conditions**:
  * There has to be at least one administrator activated on the site

## Statistics
Not implemented.

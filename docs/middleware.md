# Middleware
Middleware provide a convenient mechanism for filtering HTTP requests entering your application.  
For more information click [here][75e0c44f].

Location: `app/Http/Middleware/`

To user the middleware in the **routes.php** file the following lines need to be added to the **Kernel.php** file in the `protected $routeMiddleware = []` section.
```
'admin' => \App\Http\Middleware\AdminMiddleware::class,
'profileIsValide' => \App\Http\Middleware\ProfileIsValideMiddleware::class,
'userIsStaff' => \App\Http\Middleware\UserIsStaff::class,
```
Structure: `'<identifier> => \App\Http\Middleware\<middleware_class_name>::class'`

## Admin
Checks if the user is an administrator.
  * Is admin: continues on with the request
  * Is not admin: redirects to the `errors/access` page

## Profile is valid
Checks if the users profile needs to have it's information checked.
  * Needs check: redirects to the users profile
  * Doesn't need check: continues on with the request

## User is staff
Checks if the user is a staff member and has admin rights.
  * Is staff and admin: continues on with the request
  * Isn't staff and admin: redirects to the `home` page

[75e0c44f]: https://laravel.com/docs/5.4/middleware "Middleware Info"

# Helpers
Location: `app/Http/helpers.php`

To activate the **helpers.php** file the following needs to be added to the **composer.json** file in the `autoload` section.
```
"files": [
  "app/Http/helpers.php"
]
```

## Max Reservations
Counts the current number of reservations that the current user has.  
If the count is equal or greater then the amount specified in the config the methode returns `true` if not `false` will be returned.

# Reservation for member/non-member

Controller: `app/Http/Controllers/Booking/BookingController.php`

Middleware: `app/Http/Middleware/ProfileIsValideMiddleware.php`  in case the client is logged in.

Layout: `resources/views/layouts/app.blade.php` 

View: `resources/views/booking/home.blade.php` is main reservation view

Subview:
- `resources/views/booking/delete_modal.blade.php` is the modal display to delete a reservation 
- `resources/views/booking/own_reservs.blade.php` is the table displayed to see made reservations past and future
- `resources/views/booking/reserve_modal.blade.php` is the modal used to make a reservations (by a non-member or member)



## General
### Conditions to make a reservation

- The date of the reservation has to be in future
- The chosen court has to be up (not in maintenance)
- The hour for the chosen court has to be free




## Information needed to create a reservation
It's possible to make reservation as member (person with account) or as non-member (without log in). 
If the reservation is made by a member, the member has to give with who is going to play: member or non-member. If he want
to play with a member, the member who made the reservation has to give the id of the personal information of the member 
to play with in (in the view, it's a dropdown list). If he want to play with a non-member/visitor, he had to give first name
and last name of the user.

## Store method

![DA store@BookingController](img/reservations/Store-BookingController.png)



## As Member 
### Creation of a reservation
- the account of the member as to be activated (in database, `user.active`) or in grace period (grace period is 
a number of day during the account 
is still usable after it has been deactivated). The same condition are applied if the reservation are made with an 
other member (member vs member)
- the number of future reservations made by the creator has to be equal or less than the number max of reservations describes in the field 
`configs.nbReservations` in database. 

### Delete/cancel a reservation
- the reservations has to be his or at least to be the invited person. 

we use the destroy method of the controller. We pass the id of the reservation to delete it.



## As Non-member

### Creation of a reservation
- the information gived by the non-member has to be correct.
- When the reservation has been validated. an email is sent with a confirmation link. The non-member has to clicked on
the confirmation link before somebody else create a reservation with the same court and hour otherwise the reservation will be 
deleted. Here is a activity diagram to better understanding. 

![DA non-member reservation](img/reservations/nonMemberReservation.png)



### Delete/cancel a reservation

the cancellation of reservation by a non-member is quite different because as the creation of a reservation the system has
to send a confirmation by E-mail. Below you can see a diagram to explain how it works.

![DA non-member reservation](img/reservations/nonMemberReservationCancellation.png)

### askCancellation($request, $id)

#### Description

this method is used to ask a cancellation. it checks that the given email matches the used email for the reservation. 
Then it generate a link with a token which is stored in `reservations.remove_token`, sends a email with this link.
The link looks like that : 

####Parameters
$request is a object from Request class containing the email of the non-member(this email has to be the email used to make the
reservation) 

$id (int) is simply the id of the reservation to delete 

#### Route
The name of the route is : `booking.askcancellation` and the url is `booking/askcancellation/{id}`


 
### cancellation($request) 

#### Description
This method is used to delete the reservation when the non-member has clicked on the link in the cancellation email.
If a reservation exists with the given token

#### Route
The name of the route is `booking.cancellation` and the url is `booking/cancellation/{token}`

#### Parameter
$request is a instance from Request class containing the token generated and sent by email to the non-member








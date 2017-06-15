# Reservation
## Description
This part describes how works the reservation feature of the application, what is needed to create a reservation.
To Begin, there are multiple kind of reservation. First of all, there's reservation made by member and non-member. this type of reservation is used by "the client" of the site; it means
by the people who subscribe to the club and the people who want to play without belonging to the club. Then there's the staff booking it's used by the staff crew of the club to make a reservation for an event: for example, a birthday.
Staff booking is also used to create multiple reservations: daily or weekly reservations for example it could be used for reserve court for a training course .

#### General conditions to make a reservation

- The date of the reservation has to be in future
- The chosen court has to be up (not in maintenance)
- The hour for the chosen court has to be free

## Reservation for member/non-member

Controller: `app/Http/Controllers/Booking/BookingController.php`

Middleware: `app/Http/Middleware/ProfileIsValideMiddleware.php`  in case the client is logged in.

Layout: `resources/views/layouts/app.blade.php`

View: `resources/views/booking/home.blade.php` is main reservation view

Subview:
- `resources/views/booking/delete_modal.blade.php` is the modal display to delete a reservation
- `resources/views/booking/own_reservs.blade.php` is the table displayed to see made reservations past and future
- `resources/views/booking/reserve_modal.blade.php` is the modal used to make a reservations (by a non-member or member)


### Information needed to create a reservation
It's possible to make reservation as member (person with account) or as non-member (without log in).
If the reservation is made by a member, the member has to give with who is going to play: member or non-member. If he want
to play with a member, the member who made the reservation has to give the id of the personal information of the member
to play with in (in the view, it's a dropdown list). If he want to play with a non-member/visitor, he had to give first name
and last name of the user.

### Store method
here's a diagram to explain how the system will make the different between a reservation by a member or non-member and how it will know that
the member has invited another member or a invited.


![DA store@BookingController](img/reservations/Store-BookingController.png)



### As Member
#### Conditions to make a reservation
in addition to the general condition to create a reservation, there are also this rules:

- the account of the member as to be activated (in database, `user.active`) or in grace period (grace period is
a number of day during the account
is still usable after it has been deactivated). The same condition are applied if the reservation are made with an
other member (member vs member)
- the number of future reservations made by the creator has to be  less than the number max of reservations describes in the field
`configs.nbReservations` in database.
- if the user want to invite someone who isn't member, the user must have the invitRight (in database, `user.invitRight`)

#### Delete/cancel a reservation
- the reservations has to be his or at least to be the invited person.

we use the destroy method of the controller. We pass the id of the reservation to delete it.



### As Non-member

#### Conditions to make a reservation
- the information gived by the non-member has to be correct.
- When the reservation has been validated. an email is sent with a confirmation link. The non-member has to clicked on
the confirmation link before somebody else create a reservation with the same court and hour otherwise the reservation will be
deleted. Here is a activity diagram to better understanding.

![DA non-member reservation](img/reservations/nonMemberReservation.png)



#### Delete/cancel a reservation

the cancellation of reservation by a non-member is quite different because as the creation of a reservation the system has
to send a confirmation by E-mail. Below you can see a diagram to explain how it works.

![DA non-member reservation](img/reservations/nonMemberReservationCancellation.png)

#### askCancellation($request, $id)

##### Description

this method is used to ask a cancellation. it checks that the given email matches the used email for the reservation.
Then it generate a link with a token which is stored in `reservations.remove_token`, sends a email with this link.
The link looks like that :

####Parameters
$request is a object from Request class containing the email of the non-member(this email has to be the email used to make the
reservation)

$id (int) is simply the id of the reservation to delete

##### Route
The name of the route is : `booking.askcancellation` and the url is `booking/askcancellation/{id}`



#### cancellation($request)

##### Description
This method is used to delete the reservation when the non-member has clicked on the link in the cancellation email.
If a reservation exists with the given token

##### Route
The name of the route is `booking.cancellation` and the url is `booking/cancellation/{token}`

##### Parameter
$request is a instance from Request class containing the token generated and sent by email to the non-member


## Staff booking
Controller: `app/Http/Controllers/Booking/BookingController.php`

Middleware:
- `app/Http/Middleware/ProfileIsValideMiddleware.php`
- `app/Http/Middleware/userIsStaff.php`

Layout: `resources/views/layouts/app.blade.php`

View: `resources/views/staffBooking/home.blade.php`

## Things to know
- the staff has to put a title to the reservation for example: "Billy's birthday"
- the staff reservation belongs to the person who did it. it means that only him can delete the reservation
- if a staff is also a member and he has made staff reservations. They won't be counted when the system checks
if the user has reached the number of maximum future reservations that a member can do


## Condition to make a reservation
### General
- the user has to belongs to the crew; to be a staff member or an administrator.

### Simple reservation
similar to a "client" reservation but with a title.

### Multiple reservations
#### Type of reservations
The staff can choose the spacing between the reservation.
- weekly
- daily

### Store method
Here\'s a diagramme to explain how the system make the difference between simple reservation and multiple reservations. And how
the system proceed to space the reservation of a multiple reservations

![DA non-member reservation](img/reservations/Store-staffBookingController.png)

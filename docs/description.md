# Description
TCC is a WebApp developped for the Tennis Club of Chavornay to manage the reservations of the club and its members.

It has been developped under the MAW module.

## Actors
The different actors of the website are:
- Member - simple member of the club who pays cotisation
- Staff - is apart of the club crew. he can make mulitple reservations (For example for junior training)
- Administrator - mangage the users, seasons, courts and configuration of the site
- Non-member - is the person who doesn't pay cotisation
- guest - is non-member which are inivited by a member.





## Features
### Reservations
#### Type of reservations

The application mangages the reservations of the club. There are 2 type of reservations:
- simple reservations - can be made by member, non-member, club crew (staff member and admin).
- staff reservation - as its name let it know, only club crew users can used it


In the actual state of the project, the webApp handle this cases

| Features                                       | Description         | Route |
|------------------------------------------------|----------------------|--------|
| Simple reservation as member with a member     | A reservation made by a member                     |      |
| Simple reservation as member with a guest      | A reservation made by a member with a               |      |
| Simple reservation as non-member               | foreignKey movie.id |      |
| Simple / multiple reservation as staff         | relation to Movie   |      |
| Suppression of reservation as member           | datetime            |      |
| Suppression of reservation as non-member       |                
| Suppression of staff reservation as staff

## improvements
- Develop a system of pyramid
-

## Technologies

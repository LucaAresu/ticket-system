# Ticket System 
This project tries to code a ticketing system in hexagonal architecture


# Installation
```
make build
make start
make enter
composer install
```

# Requirements

## Ticket

- Every User can open a ticket describing their issues
- A ticket must have a category, title, description and a priority
- Only User of rank Manager can open an urgent ticket
- Both Assigned Operator, Super Operator and User can append messages to the support ticket
- Operators and Super Operators ask the system for the next ticket, and the system assigns the most urgent one in the following order
  - Urgent tickets are always the priority
  - The most urgent ticket already assigned to Operator
  - The most urgent ticket is based on remaining time without assignment
- Ticket can be closed 
  - by the User without specifying a reason
- When the ticket is closed, the copy will be sent to the user email

### Categories
- HR
- IT
- Marketing

### Priorities 
- low (time limit 1 week)
- medium (time limit 4 days)
- high (time limit 2 days)
- urgent (time limit 12 hours)


## Operators
- There are Operators and Super Operators
- An Operator can be assigned to only one category Super Operators are assigned to all categories

## Users
- Users can open only one ticket in each category
- Managers have no limits
- People can register and become Users 
  - Name, Lastname and Email and Password must be provided


# One day... maybe

## Ticket
- If the ticket is not answered within the time limit and has already been assigned to an Operator, the ticket must be assigned to a new Operator and the old Operator must receive a penalty
- A time limit for answering the ticket by an agent is based on the ticket priority
- Ticket can be escalated by both Users and Operators when it can't be resolved
- A ticket can only escalate after an Operator has answered (The User can't answer and then escalate)
- When a ticket is escalated, Super Operator must be notified
- Ticket can be closed
  - by the super Operator after it has been escalated
  - it closed automatically when the Users don't answer within a week
  - Operator can request to close a ticket, the User must agree
- A User must be notified to rate a ticket when the ticket is closed and every week
- New Operators ticket when closed must be notified to a Super Operator


## Escalation
- When a ticket is escalated
  - The ticket must be assigned to a User of Super Operator rank
  - The super Operator reviews the Operator actions and can assign a penalty
  - Super Operator must be notified

## Operators
- Operators and Super Operators can be rated
- New Operators are Operators having less than 10 tickets resolved
- A Operator can become a Controlled Operator
  - When its rank is less than 3 stars
  - The Operators have taken 3 penalties within a week
- An Operator stops being a Controlled Operator if no ticket in the last 10 has not been escalated and the rank is over 4 stars
- When a New Operator ticket is closed, a Super Operator can accept the rank given by the User or resolve the ticket
- When a ticket is closed User must give a rating(1/5 stars) with an optional comment
- The comment is required when the rating is 1
- Super Operator tickets can't be rated

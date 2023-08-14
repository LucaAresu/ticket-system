# Ticket System 
This project tries to code a ticketting system in hexagonal architecture


# Installation
```
make build
make start
make enter
composer install
```

# Requiremenents

## Ticket

- Every user can open a ticket describing their issues
- A ticket must have a category, title, description and a priority
- Only user of rank Manager can open an urgent ticket
- Both support and user can append messages to the support ticket
- Operators and Super Operators ask the system the next ticket and the system assigns the most urgent one in the following order
  - Urgent ticket have always the priority
  - The most urgent ticket already assigned to operator
  - The most urgent ticket is based on remaining time without assignement
- If the ticket is not answered within time limit and has already be assigned to an operator the ticket must be assigned to a new operator and the old operator must receive a penality
- A time limit for answering the ticket by an agent is based on the ticket priority
- A ticket can be only escalated after an operator has answered (The user can't answer and then escalate)
- Ticket can be escalated by both user and agents when it can't be resolved
- When a ticket is escalated Super Operator must be notified
- Ticket can be closed 
  - by the user without specifiying a reason
  - by the super operator after it has been escalated
  - it closed automatically when the users don't answer within a week
  - Operator can request to close a ticket, the user must agree
- When a ticket is closed user must give a rating(1/5 stars) with an optional comment
  - The comment is required when the rating is 1
  - Super Operator tickets can't be rated
- An user must be notified to rate a ticket when the ticket is closed and every week
- New Operators ticket when closed must be notified to a Super Agent

### Categories
- HR
- IT
- Marketing

### Priorities 
- low (time limit 1 week)
- medium (time limit 4 days)
- high (time limit 2 days)
- urgent (time limit 12 hours)

## Escalation
- When a ticket is escalated 
  - The ticket must be assigned to an user of Super Operator rank
  - The super operator reviews the operator actions and can assign a penality
  - Super Operator must be notified

## Operators
- There are Operators and Super Operators
- Operators and Super Operators can be rated
- New Operators are operators having less than 10 tickets resolved
- A operator can become a Controlled Operator 
  - When its rank is less than 3 stars
  - The Operators has taken 3 penalities within a week
- A operator stops being a Controller Operator if no ticket of its the last 10 has not been escalated and the rank is over 4 stars
- When a New operator ticket is closed a Super Operator can accept the rank given by the user or resolve the ticket
- An Operator or Super Operator can be assigned to only one category


## Users
- Users can open only one ticket in each category
- Managers have no limits
- People can register and become Users 
  - Name, Lastname and Email and Password must be provided
- Every User (including operator and Super Operator) receive a nickname based on name and numbers in the format Name-1234
- Users can Open an HR Ticket changing to change nickname, the new nickname must follow the same rule

Udacity-Message-API
===================

The Problem
-----------

Messaging system project

Udacity could use an internal messaging system for site-wide announcements, for instructors to send messages to their class, and for one-to-one communication between users.  Design and implement as many of the following features as you feel comfortable (and have time for), include any notes that document the design issues you discovered and how you chose to address them, and don’t forget some tests.  If you don’t have time to implement all the features but believe your design would support them, feel free to just explain how you would go about implementing them given more time.

1. Allow sending of messages between individual users, identified by the unique key of their record in the system.  Messages are in markdown format, and auto-complete of recipients would be a bonus.
2. Allow a user to view their inbox, read messages, (automatically) mark messages as read, and delete messages.
3. Allow sending a broadcast message to all users.  Keep in mind that there could be millions of users.
4. Allow sending a message to a group of users.  Groups can be large (over 100,000 users) and are stored by having each user record list all the groups it’s a member of.  Group membership varies over time and a message should be received only by the users who were members of the destination group at the time the message was sent.

The Solution
------------

A REST API written in Laravel, using MySQL and running on Apache. See the above source code for how I did it, and
make sure to check out [the Wiki](https://github.com/apotheos/udacity-message-api/wiki/_pages) for tons of
documentation, planning info, and technical details.
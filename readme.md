Kompiuterizuoti test
============

Test project for job application in kompiuterizuoti meant to test basics of php, html, css and js

Note: if using Windows you'll need to setup WSL.2 for docker to work properly.

Table of Contents
-----
- [Setup](#setup)
  * [Project setup](#project-setup)
- [Viewing project](#viewing-project)
- [Viewing db](#viewing-db)

Setup
======
Project setup
-------------
1. Clone repository.
2. `docker-compose up -d`
This command sets all dependencies needed for php project: db and phpmyadmin


Viewing project
======
To open project go to: http://localhost:8000

Viewing db
======
To view data base via browser:
  http://localhost:8080
  
  username: kompiuterizuoti
  
  password: insecuredevpassword

To view database in a dedicated app (e.g. TablePlus). Go to: 

mysql://kompiuterizuoti:insecuredevpassword@127.0.0.1:9906/kompiuterizuoti


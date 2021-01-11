# Chope PHP Test

Generally, two functions, user register and user login, are required (not limited) to be implemented in your test project. Specifically, the user behaviors of the web application should include the following items.

 

### 1. Register feature

a. Users can register on your web application by filling in, at least, the username and password. You can decide what other information is worth to collect, from your perspective and from the technology and business point of view.

b.       User’s profiles could be stored in MySQL or any other relational databases that you chose.

c.       User's actions like registering could be logged in Redis accordingly.

### 2. Login feature

a.       Registered users can login successfully when they have valid username and password. On the contrary, the user should be indicated with a proper message when failed to login.

b.       Logged in users can log out and this action should be recorded in Redis as well.

c.       Logged in users can see their register/login/logout history retrieved from Redis.

### 3. API

Register API

a.       Allow other apps to implement register feature by integrating with this API.

Login API

a.       Allow other apps to implement login feature by integrating with this API.

## Installation

Use Docker (https://www.docker.com/) to run the application

```bash
docker-compose up
```
The Application will start running on http://localhost/

## API Documentation

A postman (https://www.postman.com/) collection file (Chope.postman_collection.json) has been added to root directory of this project, the same can be imported in Postman to try API's


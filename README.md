# Group Chat API Server

![GitHub](https://img.shields.io/github/license/your-username/group-chat-api)
![PHP](https://img.shields.io/badge/PHP-%3E%207.4-blue)
![Slim](https://img.shields.io/badge/Slim%204-red)
![SQLite](https://img.shields.io/badge/SQLite3-green)

## Overview

This is a simple API server for a group chat application where users are authenticated using a "username" header for simplicity. Users can send messages in group chats, and they have the capability to create their chatrooms. Additionally, users can be designated as admins of a group chat and have the authority to delete their chatrooms or appoint other users as admins. All messages and data are stored in an SQLite3 database.

## Prerequisites

Before getting started with this API server, ensure that you have the following prerequisites installed on your system:

- **PHP**: Version > 7.4
- **Slim 4 Framework**
- **SQLite3**

## Installation

### 1. Clone the Repository

```shell
git clone https://github.com/parhamcz/Slim-simple-group-chat.git
```
### 2. Change Direction to the cloned repo

```shell
cd /Slim-simple-group-chat
```
### 3. Install Dependencies

```shell
composer install
```
### 4. Configure Database
***I made the DB manually and it is included in the Repository. You can use the pattern and make it for your own or use the test DB that i put in the repository**
```shell
slim-chatroom.db
```
### 5. Start the server

```shell
php -S localhost:8000 -t public public/index.php
```
## Installation

### Usage
To test the APIs, use any tool you want (like postman), you can create user and then for other routes authentication is needed.
Users must include a "username" heaeder in requests in order to use the routes and the username must be present in DB.
### Endpoints
Endpoints can be found in 'routes' folder.
### Acknowledgments
**Slim Framework**
**SQLite**

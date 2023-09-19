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
git clone https://github.com/your-username/group-chat-api.git

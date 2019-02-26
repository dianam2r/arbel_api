<p align="center">
    <h1 align="center">Arbel API</h1>
    <br>
</p>

this is a small api to provide endpoints to arbel application

# INSTALLATION

You have to download this source code and place it within your web root directory, same as the arbel project.

~~~
git clone git@github.com:dianam2r/arbel_api.git
~~~

# ENDPOINTS for ARBEL API

You can test this endpoints by downloading the Postman application from the official web page: <a href="https://www.getpostman.com/">Postman</a>

FOR TASKS
-------
1. Read
GET http://localhost/arbel/config/api/task/read.php
2. Read One
GET http://localhost/arbel/config/api/task/read_one.php?id=2
3. Create
POST http://localhost/arbel/config/api/task/create.php
```json
{
	"title" : "Task 5",
	"description" : "This is a test from the endpoint connecton to the API",
	"estimated_points" : 3,
	"status_id" : 1,
	"created_by" : 1,
	"updated_by" : 1
}
```
4. Update
POST http://localhost/arbel/config/api/task/update.php
```json
{
	"id" : 1,
	"title" : "Task Edited",
	"description" : "Task edited using endpoint",
	"estimated_points" : 2,
	"attached_file" : null,
	"assigned_to" : 1,
	"status_id" : 2,
	"updated_by" : 1
}
```
5. Delete
POST http://localhost/arbel/config/api/task/delete.php
```json
{
	"id" : 2
}
```
6. Search
GET http://localhost/arbel/config/api/task/search.php?keyword=Open
Can look for title, description, comment, user assigned or status of the task

USERS
-------
1. Read
GET http://localhost/arbel/config/api/user/read.php
2. Read One
GET http://localhost/arbel/config/api/user/read_one.php?id=2
3. Create
POST http://localhost/arbel/config/api/user/create.php
```json
{
	"name" : "Adam",
	"last_name" : "Levine",
	"group_id" : 3,
	"username" : "alevine",
	"password" : "myotherpassword"
}
```
4. Update
POST http://localhost/arbel/config/api/user/update.php
```json
{
	"id" : 3,
	"name" : "Jhon",
	"last_name" : "Doe",
	"group_id" : 2,
	"username" : "jhonny",
	"password" : "mynewpassword"
}
```
5. Delete
POST http://localhost/arbel/config/api/user/delete.php
```json
{
	"id" : 3
}
```
6. Search
GET http://localhost/arbel/config/api/user/search.php?keyword=Jenni
Can look for name, last name, group name or username

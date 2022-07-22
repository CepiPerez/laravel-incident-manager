# Incident Manager

![alt text](https://raw.githubusercontent.com/CepiPerez/laravel-incident-manager/master/l-image.svg)

Incident Manager made with Laravel 9.

#### Features

- Incidents can be created by internal users (like Help Desk area) or external users (i.e.: clients)
- Create, assign, pause, resolve, close or cancel actions are available
- Attachments can be included, also when adding incident progress 
- Incidents can be assigned to a group or specific user
- User notes and private notes available
- Different inbox for each user depending on their roles
- Advanced filters, which are stored in sessions
- Dashboard for incidents statistics (total incidents, unassigned, in progress, paused, resolved, closed, canceled) and SLA for opened incidents
- Manage users, groups, clients, service types, areas, modules, problems, priorities, auto-assignment and more
- SLA management also available to check incidents times

#### Available languages

- English
- Spanish

#### Requirements

- PHP 8  + all Laravel 9 requirements
- MySQL

### Docker

The project includes a docker-compose file for local testing, wich uses `php:8.1.4-apache` and `mysql:5.7`. 
You can build and test the app running the following commands on linux:
```sh
docker-compose build .
docker-compose up -d
```
Then just type `localhost` in your browser's address.
Default Administrator user: `admin`, password: `admin`

#### License

MIT

#### Screenshots

<img src="https://raw.githubusercontent.com/CepiPerez/laravel-incident-manager/master/screenshot_1.png" alt="" width="240"/>
<img src="https://raw.githubusercontent.com/CepiPerez/laravel-incident-manager/master/screenshot_2.png" alt="" width="240"/>
<img src="https://raw.githubusercontent.com/CepiPerez/laravel-incident-manager/master/screenshot_3.png" alt="" width="240"/>
<img src="https://raw.githubusercontent.com/CepiPerez/laravel-incident-manager/master/screenshot_4.png" alt="" width="240"/>



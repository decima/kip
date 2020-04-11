# KIP

## Purpose 

We passed many hours to search a Knowledge base to fill all our needs, and after maybe 15 or more KB solutions tested, paid or free, open source or not even wiki
. We never found something that really rocks for us. That's why @decima took the lead to create from scratch a KB that will kill every kb out there.

### What is the perfect KB?

It must have the following features:

- A Good search engine
- A nice and modern interface for reading articles (you don't want to read something ugly, and you don't want people avoid reading your kb because it is ugly or unreadable)
- A good markdown editor
- A good markdown extensions for making diagram, styling, links and more
- A nice homepage for making thing accessible easily
- An easy way to import / export data from it

## Installation

This project is a symfony project and requires PHP >7.3 to develop with.

You should run these commands :
```bash
composer install
npm install
```

## Getting started

To run the project in dev environment, just run ```php -S 0.0.0.0:8000 -t public```
It will directly serve a `storage` folder at the root of the project.

Feel free to change the folder by creating your own .env.local and set the env variable to `FILE_STORAGE=$PWD/../storage`

You should also run ```npm run dev-server``` in parallel to build scss files to the `public` folder.


### Using Docker
For those who don't want to install php on their computer, you can use a pre-built docker-image for development.

Using docker-compose, it will start the project on port 8010 by default and use the var/storage folder for markdown files.

## How to contribute
Every contributions, even small are welcome, feel free to make this project awesome. 
The project is full of issues you can add but you can also fix ones if you want to.
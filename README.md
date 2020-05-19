# KIP

> KIP (standing as Knowledge Is Power) is a dead simple yet beautiful knowledge base management based on markdown files :fire:

> @TODO put a screenshot / live demo link when the new style will be implemented

- [Purpose](#purpose)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Getting started](#getting-started)
- [Contribute](#contribute)
- [Licence](#licence)

## Purpose 

We spent many hours searching for a Knowledge Base ('KB') to fill all our needs, and after maybe 15 or more KB solutions tested, 
paid or free, open source or not, we never found something that really rocks for us. 
That's why [@decima](https://github.com/decima) took the lead to create a killer KB from scratch ! :boom:

## Features

- [x] :eyes: A nice and modern interface to read articles (who reads ugly articles, right ?)
- [x] :open_file_folder: An easy way to import / export data from it : it simply reads a folder containing all your markdown files, CAN'T BEAT THAT
- [x] :pencil2: A good markdown editor
- [x] :no_good: Access management : being able to edit articles only when logged in
- [x] :mag: A powerful search engine
- [ ] :lock: Database / LDAP authentication

## Requirements

- This project is a symfony project and requires PHP >= 7.4
- node and npm or yarn to build asset files

## Installation

You should run these commands in a terminal :
```bash
git clone https://github.com/AboutGoods/kip.git
cd kip
composer install
yarn # or `npm install` if you are using npm 
```

Feel free to change environment variables by copying the `.env` file to a `.env.local` file.

And you're good to go ! :tada:

## Getting started

Your markdown files should be placed in the folder specified in the `FILE_STORAGE` environment variable (by default in `./var/storage`).
Make sure to create this folder if it doesn't exist.

### Using a local stack

To run the project in development, you should run these commands in parallel :
```bash
php -S 0.0.0.0:8000 public/index.php # this serves the specified folder as the root of the KB
yarn dev-server # or `npm run dev-server` - builds style and script files to the `public` folder
```

### Using Docker

For those who don't want to install php on their computer, you can use a pre-built docker-image for development.

Using docker-compose, it will start the project on port 8010 by default and use the var/storage folder for markdown files :
```bash
docker-compose up
```

## Contribute

:raised_hands: Every contributions, even small are welcome, feel free to make this project awesome :dizzy:

Make sure to follow the instructions in [CONTRIBUTING.md](./CONTRIBUTING.md)

## Licence

Defining it soon (@TODO)

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
- A reliable way  way to store data by using a database and not just files

## Getting started

This project is a symfony project and requires PHP >7.3 to develop with.

To run the project in dev environment, just run ```php bin/console server:run 0.0.0.0:8000```
It will directly serve a `storage` folder at the root of the project.

Fill free to change the folder by creating your own .env.local and set the env variable to `FILE_STORAGE=$PWD/../storage`

This is all what the project needs to work.


## How to contribute
Every contributions, even small are welcome, feel free to make this project awesome
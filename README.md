Knowledge is Power (KIP)
=================

KIP is a project for knowledge db editor. 

# Getting started (FINALLY!)

- Create a copy of this project
  - how?
    - download a tarball of it ([let me do it for you](https://github.com/decima/svelte-go-kip/archive/refs/heads/main.zip))
    - or...
    - use the "templating" feature from github ([let me do it for you](https://github.com/decima/svelte-go-kip/generate))
- create a config.yaml file empty at root of the folder (it will be populated at first launch with defaults)
- There is a script named `rename.sh` **not working**, it is used to replace "kip" in every strings by the name you decide. Be careful to name it well (there's no turning back)
  - run `chmod a+x rename.sh && ./rename.sh`
  - you can either search and replace while the script is not working.

So you have a fresh project how to work with it ? 

First, run (and don't close it):
```
go run .
```
which will start the backend application.

Then go to app folder and launch the app.

```bash
cd app
yarn #or npm install, i'm not racist
yarn dev # or npm run dev, your problems :) 
```
The second command will print you an url. 
If everything's cool, you should have a beautiful example page.

the API is available on /api. 

## Build for production

I recommend docker, because it's a 3-stage build : 
- first for the frontend
- second for the backend
- and then a grouping build on an alpine instance.

you can try at any moment the app with : 
```
docker compose up --build
```
which exposes port 9000. 

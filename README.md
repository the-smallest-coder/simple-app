This a simple example on how to use docker compose to run a simple web application with MySQL and MongoDB databases. The application leverages the environment variables to connect to the databases. Docker compose will create a network and will connect the containers to it. The application will be available on port 80.

## Requirements
- Docker (~>20.10.21)
- Docker Compose (~2.13.0)
- basic CLI knowledge

## How to run
- git clone with project url
- if you want XDebug enabled, you should add `XDEBUG_ENABLED=1` to your `.env` file
- please copy .env.exmpale to .env and fill the values
- compose up
- wait untile the containers are up and running(MySQL and MongoDB can take time for provision)

## How to test
- open your browser and go to http://localhost/
- you will inserted data
- refresh page, this will generate more data and you will see it on the page

## Notes
- Application use text content to avoid HTML/CSS complications in the code
- composer wasn't used to avoid additional steps in installations
- all containers used for project have arm64 support, this does meant that you can run them with m1 macs (howet).
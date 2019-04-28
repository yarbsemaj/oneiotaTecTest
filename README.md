# One iota WebApps PHP Task

### Set up

This project comes with a Dockerfile and a docker-compose.yml for using Docker compose to build the PHP environment. Please find information [here](https://docs.docker.com/compose/install/) for installing Docker Compose if you have not already.

The image we use has both nginx & php-fpm on the following versions:

| Docker Tag | Git Release | Nginx Version | PHP Version | Alpine Version |
|-----|-------|-----|--------|--------|
| latest | Master Branch |1.14.0 | 7.2.7 | 3.7 |
| 1.5.4 | 7.2 Branch |1.14.0 | 7.2.7 | 3.7 |



### Run 

A Makefile has been included for convinience, run the following command to start the environment:

```
make up
```

This will start the container and listen on the default IP (usually 0.0.0.0) on port 80. 



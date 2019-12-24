FROM mysql:5.7.28

# set maintainer
MAINTAINER Aleksandar Rakić <info@aleksandarrakic.rs>

# add the content of the database/ directory to your image
# all scripts in docker-entrypoint-initdb.d/ are automatically
# executed during container startup
COPY ./docker/database/dump/ /docker-entrypoint-initdb.d/

# update and install required os packages
RUN apt-get update \
    && apt-get install -y

# make port 3306 available to the world outside this container
EXPOSE 3306
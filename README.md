sudo how to set-up & run the project
The following will guide you through setting up the project on your local PC. If you already have the project on your PC, check the #Other setup section below
# pre-requisites
+ php version 7.0+
+ Mysql version 8.0+
+ composer. (https://getcomposer.org/download/)
+ Yarn. (Yarn requires nodejs, so ensure that you have it first. Follow the steps below to install both)
```sh
curl -sL https://deb.nodesource.com/setup_10.x | sudo -E bash -
sudo apt-get install -y nodejs
curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -
echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list
sudo apt-get update && sudo apt-get install yarn
```
+ An empty mysql database. Make sure that the mysql user configured has access to it

## Perform the following in your terminal
```sh
# clone from online repo.
git clone https://gitlab.com/competamillman/adgg-backend.git

# cd into the folder created. Defaults to adgg
cd adgg

# Create db.php file by copying the contents of db.sample.php into db.php
cd src/common/config/
sudo cp db.sample.php db.php

# verify your database credentials, by editing the db config file
vi src/common/config/db.php

# import the mysql database dump. Ensure that you have the database named adgg first
mysql -u root adgg -p < src/data/adgg.sql

# install composer dependencies
composer install

# install yarn dependencies
yarn

# If composer or yarn throws some strange permission denied errors please run the following command to fix it.

sudo chown -R $USER:$GROUP ~/.npm
sudo chown -R $USER:$GROUP ~/.config

# change permission of these folders
sudo chmod -R 777 uploads/
sudo chmod -R 777 assets/
sudo chmod -R 777 src/backend/runtime/
sudo chmod -R 777 src/console/runtime/
sudo chmod -R 777 src/api/runtime/

# Create env.php by copying the contents of env.sample.php into env.php
sudo cp env.sample.php env.php

# Edit the contents of env.php to match your environment

# you're done
```

visit http://localhost/gelf, to view the system. login using the following:
> username => admin | password => Admin12345

# Other setup
Assumes that you already have a copy of this project in your pc, and you just want to get code updates
## pull all branches
```sh
git pull
```
## checkout to develop branch (for development)
```sh
git checkout develop
```

### install/update dependencies
```sh
composer update
yarn install
```

### update the database data
Run the Yii migrations, so that you get the latest modifications
```sh
cd src
./yii migrate
```

### your done!!!
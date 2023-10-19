
# steps to install

* pull or clone code
* setup the links using the soft link instructions below
* set up the .env file, see [.env.example](.env.example), see the env section below
* does composer work? if you are running cpanel then most likely the installed composer will be using the wrong php version. That will mess things up
 * if not follow https://getcomposer.org/download/ to install it.	
 * cd to the project root
 	php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
	php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
	php composer-setup.php
	php -r "unlink('composer-setup.php');"
 * the php command here may be needing the path to the php, see the instructions for cpanel section
 * The hash_file may be outdated, it changes when they release a new composer so can get new command via their download page
 * The gitignore will ignore the phar at the install folder root, so that is a great location to put it if not global install
* run `composer install` or however you run composer for example `/usr/local/bin/ea-php81 composer.phar install`
  * if there are missing php extensions, or php version issues, it will tell you then. Need to fix any 
* run db migrations
  * before running on a non empty database see the section below if migration crashes to prevent issues
  * these migrations, if never run, can run on an empty database or assume the db to have the struture in the [scmpowered_database.sql](database/archives/scmpowered_database.sql)
  * then run the migrations
    * `php artisan migrate:status`
    * `php artisan migrate`
    * or if want a dry run do `php artisan migrate --pretend`
* change the document root for the site, see https://www.techalyst.com/posts/change-hosting-domain-document-root-for-laravel-project
  * Can change the document root in other ways too

After that, the site should run, if there are any permission issues though, the php needs to write at the following directories
 *  storage
 *  public/build
 *  public/hot
 *  public/storage
 *  bootstrap/cache

## if migration crashes because of issue with already existing users table that has data
* put a unique email address in each column
* put a value in each created_at colum

# debugging

* the debugging for laravel can be turned on or off via the .env setting of APP_DEBUG (set to true or false)
* the site can be made to run on a sketchy https cert by forcing all the links generated to be https. There is a an optional .env setting called APP_FORCE_HTTPS set to true


# Site settings in the .env

* make or copy over an .env from a file or from the .env.example at the root
* put the site url in APP_URL
* put the name of the site in the APP_NAME
* adjust the db settings, if not running in the docker do not need to adjust any SCM_DATABASE_ or any SCM_PHP_
  * DB_HOST=localhost for most servers
  * DB_PORT usually stays the same
  * DB_DATABASE for the database name
  * DB_USERNAME and DB_USERNAME for the db user login
    
* set logging level and channel see laravel docs, right now it's fine for development
* the APP_DEBUG=true means you get detailed error messages when an exception is not caught 
* the errors are all logged at the folder storage/logs the log settings means there is a new log for each day, these are rotated after some days

# laravel docs

* this was written with laravel version 10, see https://laravel.com/docs/10.x
* commands such as migration and clearing the cache uses https://laravel.com/docs/10.x/artisan commands in the terminal


# starting up the web server and db

* install docker
* run `docker-compose up` at the repo root
* there are settings in the .env file for the ports the web server and mysql are used from the outside of the docker network. Inside the docker network the ports are the default ones.
  * the only db settings that must change are the SCM_DATABASE_HOST=db_scm and the DB_HOST=db_scm. This is because inside the docker network, the mysql ip has that alias.
  * there is a phpmyadmin in the docker file
  * can change the ports the phpmyadmin and the mysql can be reached at via the SCM_PHP_ADMIN_PORT and the SCM_DATABASE_PORT. These can be any ports
  * the root password for the mysql is the SCM_DATABASE_ROOT_PW and that can be set for anything
  * the db logins here each have two sets of keys, because one is for the composer build system and one is for the laravel. The keys starting with SCM_DATABASE_ and DB_ need to be filled out


# instructions for cpanel

often when running commands the cpanel php will be a different version, than the one the website runs,
and the installed composer will use that different version of php.

To run the commands, need to use the same version of php , and need to download the composer.phar to the repo root folder

so for example
* `/usr/local/bin/ea-php81 composer.phar install`
* `/usr/local/bin/ea-php81 composer.phar artisan`
* `/usr/local/bin/ea-php81 artisan migrate:status`
* `/usr/local/bin/ea-php81 artisan migrate`

# specific soft link instructions
if the bash script does not work can do the following commands (change to correct path)
ln -s /home/scmpowered/demo2.scmpowered.com/storage/app/uploads /home/scmpowered/demo2.scmpowered.com/public
`ln -s /home/scmpowered/demo2.scmpowered.com/storage/app/customization /home/scmpowered/demo2.scmpowered.com/public`


# Creating and updating the docs for the plugins
    
We currently use https://www.phpdoc.org/ to make the documents from both the docblocks in the code and the rest files in the `docs` folders

There is a config file [phpdoc.dist.xml](phpdoc.dist.xml) for the phpdocumenation tool.
see the [config instructions](https://docs.phpdoc.org/3.0/guide/getting-started/configuration.html#configuration)

This config file will tell the phpdoc tool to look at the plugin code only and the example plugin

When the tool is run at the root folder of this project it will update the docs. These docs are written to
.phpdoc/build folder and the docs can be opened at .phpdoc/build/index.html 

The finished html file is at [index.html](.phpdoc/build/index.html) but there can be hundreds of support files there too.
A softlink can be made at the index.html to make it show up where needed in a public folder.

There are several ways to download or use the tool, as discussed at https://docs.phpdoc.org/3.0/guide/getting-started/installing.html#installation

If using docker , then an alias that works for me is (put in the .bashrc if linux)

    alias phpdoc="docker run --rm -v $(pwd):/data phpdoc/phpdoc:3"

Then I just call phpdoc at the project folder

# The sample theme plugin 

The sample theme plugin can be installed as a submodule. For a tutorial on submodules see 
https://www.atlassian.com/git/tutorials/git-submodule

When cloning

    git clone --recursive [URL to Git repo]

If already cloned, then this command will pull in the subrepos

    git submodule update --init

When later just pulling , after one of the above subrepo initialization done 

    # pull all changes in the repo including changes in the submodules
    git pull --recurse-submodules

    # pull all changes for the submodules
    git submodule update --remote





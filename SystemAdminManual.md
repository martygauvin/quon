# Introduction #

This page includes step-by-step installation instructions for setting up and running the Quon software.


# Details #

Quon is based on the [CakePHP framework](http://cakephp.org), and installation essentially follows the [requirements of CakePHP](http://book.cakephp.org/2.0/en/installation.html).
These instructions specify how to set up Quon from a fresh install of [Linux Mint Debian Edition](http://www.linuxmint.com/download_lmde.php), so slight changes may be required for other operating systems or distributions.

## Installing Apache and PHP ##
  1. Install the software: Run `sudo apt-get install apache2 php5 libapache2-mod-php5 php5-curl`
  1. Enable mod\_rewrite for Apache: Run `sudo a2enmod rewrite`
  1. Enable AllowOverride for Apache: Edit `/etc/apache2/sites-available/default` and replace `"AllowOverride None"` with `"AllowOverride All"` for directory `/var/www/`
  1. Restart Apache: `sudo /etc/init.d/apache2 restart`
  1. Test PHP: Run `cat "<?php phpInfo(); ?>" > /var/www/test.php`. Then visit [http://localhost/test.php](http://localhost/test.php).

## Installing a Database ##
  1. Install the software: Run `sudo apt-get install mysql-server libapache2-mod-auth-mysql php5-mysql phpmyadmin`
  1. Enter in a database password when required, and choose apache2 at the next screen.
  1. Enter in the database password entered previously for phpmyadmin, and then choose a password for phpmyadmin.
  1. Restart Apache: `sudo /etc/init.d/apache2 restart`
  1. Visit [http://localhost/phpmyadmin](http://localhost/phpmyadmin), entering username "root", and the password you entered above.
  1. Click on Databases tab and create a new database. For the purposes of this document, it is assumed that the database is named "ands".
  1. In the list of databases, find the database created above and choose "Check Privileges".
  1. Choose "Add a new User".
  1. Enter a user name and password, and grant all privileges on the database. Press "Go" to perform the action.
  1. Select the newly-created database from the list of databases on the left of the page.
  1. Go to the "Import" tab
  1. Click "Browse..." and choose [schema.sql](http://code.google.com/p/quon/source/browse/trunk/sql/schema.sql).
  1. Press "Go" and exit out of PhpAdmin.

## Installing Quon ##
  1. Untar quon file to your Web server's directory (/var/www/ in this example)
  1. Edit quon/app/Config/core.php and alter Security.salt and Security.cipherSeed to be random values.
  1. Edit quon/app/Config/database.php and make configuration match the database settings created above.
  1. Visit http://localhost/quon and log in with username `admin` and password `admin`

## Installing Image Manager (optional) ##

QuON supports the commercial ImageManager plugin available from http://www.tinymce.com. Without this plugin you won't be able to upload images for insertion into the survey pages without having manual file upload access to the server.

  1. Purchase and download the ImageManager plugin
  1. Expand the download archive into quon/app/webroot/js/tiny\_mce/plugins/ (i.e. you will end up with quon/app/webroot/js/tiny\_mce/plugins/imagemanager/)
  1. Ensure that your quon/app/webroot/files/ folder is writable
  1. Login to QuON as an administrator, choose 'System Setup' and set the 'Tiny MCE ImageManager' value to 'true'
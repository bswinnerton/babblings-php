
babblin.gs
--------

[Download][dl] (524 KB) -- 28 Aug 2012

[dl]: https://github.com/bswinnerton/babblings/tarball/master


### Introduction ###

Babblin.gs is a tumblr-like blogging platform that can incorporate various types of media in a masonry format. Acceptable content is:

1. Image (ending in .jpg, .png, .gif, etc):

		http://farm7.staticflickr.com/6119/6278651452_15a629cbe7.jpg

2. Youtube:

        http://www.youtube.com/watch?v=yRvJylbSg7o&feature=g-all-lik

3. Vimeo:

        http://vimeo.com/6671508

4. Spotify URI:

        spotify:track:5xioIP2HexKl3QsI8JDlG8

5. Text:

        ಠ_ಠ

### Installation ###

babblings runs on a preconfigured [CodeIgniter] stack. The only requirements are a working PHP & database instance.

Various parameters are required to get a working environment. Be sure to define the following parameters:

__application/config/config.php:__

1. `$config['storage'] = 's3';` Available options are s3 and local (options are configured in application/config/s3.php. Locally stored images are stored in public/images/ and thumbnails are stored in public/images/thumbnails/.
2. `$config['contentBox_width'] = '280';` The content box is the div that the jquery masonry formats. If you want a larger formatted width for your content boxes, increase this value.

  [codeigniter]: https://github.com/EllisLab/CodeIgniter
  
__application/config/database.php:__

1. `$db['default']['hostname'] = 'localhost';` The hostname of your database server.
2. `$db['default']['username'] = 'babblings';` The username that has access to your database server and babblings database.
3. `$db['default']['password'] = '';` The password to your babblings-accessible user.
4. `$db['default']['database'] = 'babblings';` The name of the database that the schema.sql script will be stored on.
5. `$db['default']['dbdriver'] = 'mysql';` The type of database you plan to use.
  
__application/config/s3.php:__

1. `$config['accessKey'] = '';` The access key provided by Amazon's S3 IAM.
2. `$config['secretKey'] = '';` The secret key provided by Amazon's S3 IAM.
3. `$config['useSSL'] = FALSE;` This options controls whether or not traffic to s3 is done over SSL.
4. `$config['bucket'] = 's3.babblin.gs';` The name of your s3 bucket.

__Database:__

The database can be originally created with the schema.sql file. It contains the framework for which babblings runs on.

__Document Root:__

The document root should be set to the path in which the source code is located, following by public/. In apache, this would look like this:

        DocumentRoot /var/www/babblin.gs/public/

### Questions / Comments ###

Please feel free to direct any questions or comments to the [issue tracker], located on GitHub.

  [issue tracker]: https://github.com/bswinnerton/babblings/issues
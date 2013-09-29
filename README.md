NavBar
======

Symfony2 application to display a nav bar


Installation
============

1) Clone the project from github
--------------------------------

```sh
git clone https://github.com/IFE-ENSL/NavBar.git
```

2) Installing the Standard Edition
----------------------------------

Use Composer (*recommended*)

As Symfony uses Composer to manage its dependencies, the recommended way
to create a new project is to use it.

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

```sh
curl -s http://getcomposer.org/installer | php
```

Then, go inside the cloned folder and use Composer to install all the application dependencies

```sh
php composer.phar update
```

3) Checking your System Configuration
-------------------------------------

Make sure that your local system is properly configured for Symfony.

Execute the `check.php` script from the command line:

```sh
php app/check.php
```

The script returns a status code of `0` if all mandatory requirements are met,
`1` otherwise.

Access the `config.php` script from a browser:

    http://localhost/path/to/symfony/app/web/config.php

If you get any warnings or recommendations, fix them before moving on.

4) Apache2 vhost configuration
------------------------------

Here is a sample for a Apache virtual host to serve this application:

```xml
<VirtualHost *:80>
        ServerName navbar.ens-lyon.fr
        DocumentRoot /var/www/NavBar/web
        DirectoryIndex app.php

        <Directory /var/www/NavBar/web>
                Options All -Indexes
                AllowOverride None
        </Directory>

        <IfModule mod_rewrite.c>
            RewriteEngine On

            # Determine the RewriteBase automatically and set it as environment variable.
            # If you are using Apache aliases to do mass virtual hosting or installed the
            # project in a subdirectory, the base path will be prepended to allow proper
            # resolution of the app.php file and to redirect to the correct URI. It will
            # work in environments without path prefix as well, providing a safe, one-size
            # fits all solution. But as you do not need it in this case, you can comment
            # the following 2 lines to eliminate the overhead.
            RewriteCond %{REQUEST_URI}::$1 ^(/.+)/(.*)::\2$
            RewriteRule ^(.*) - [E=BASE:%1]

            # Redirect to URI without front controller to prevent duplicate content
            # (with and without `/app.php`). Only do this redirect on the initial
            # rewrite by Apache and not on subsequent cycles. Otherwise we would get an
            # endless redirect loop (request -> rewrite to front controller ->
            # redirect -> request -> ...).
            # So in case you get a "too many redirects" error or you always get redirected
            # to the startpage because your Apache does not expose the REDIRECT_STATUS
            # environment variable, you have 2 choices:
            # - disable this feature by commenting the following 2 lines or
            # - use Apache >= 2.3.9 and replace all L flags by END flags and remove the
            #   following RewriteCond (best solution)
            RewriteCond %{ENV:REDIRECT_STATUS} ^$
            RewriteRule ^app\.php(/(.*)|$) %{ENV:BASE}/$2 [R=301,L]

            # If the requested filename exists, simply serve it.
            # We only want to let Apache serve files and not directories.
            RewriteCond %{REQUEST_FILENAME} -f
            RewriteRule .? - [L]

            # Rewrite all other queries to the front controller.
            RewriteRule .? %{ENV:BASE}/app.php [L]
        </IfModule>

        <IfModule !mod_rewrite.c>
            <IfModule mod_alias.c>
                # When mod_rewrite is not available, we instruct a temporary redirect of
                # the startpage to the front controller explicitly so that the website
                # and the generated links can still be used.
                RedirectMatch 302 ^/$ /app.php/
                # RedirectTemp cannot be used instead
            </IfModule>
        </IfModule>

        # Possible values include: debug, info, notice, warn, error, crit, alert, emerg.
        LogLevel warn
        ErrorLog /var/log/apache2/navbar_error.log
        CustomLog /var/log/apache2/navbar_access.log combined
</VirtualHost>
```

5) Cache and permissions
------------------------

Clear the cache
```sh
$ php app/console cache:clear (--env=prod --no-debug)
```

Set the rights permissions:
```sh
$ chown www-data -R app/cache/ app/logs/ && chmod 775 -R app/cache/ app/logs/
```


Application configuration
=========================

This application provide a simple corporate navbar easily customizable.
To configure it simply change or add items in the `app/config/config.yml`:

```yml
# Ifensl configuration
ifensl_nav_bar:
    brand_title: "// ECONOMIE DU WEB"
    brand_url: http://www.ens-lyon.fr
    items:
        home:
            title: Accueil
            url: http://www.ens-lyon.fr
            color: "#AF1CE0"
        courses_map:
            title: Plan de cours
            url: http://www.ens-lyon.fr
            color: "#ED4A2F"
        ressources:
            title: Ressources
            url: http://www.ens-lyon.fr
            color: "#FFED51"
        forum:
            title: Forum
            url: http://www.ens-lyon.fr
            color: "#71BF4F"
        notebook:
            title: Cahier
            url: http://www.ens-lyon.fr
            color: "#2986DC"
```


Application routes
==================

To see the routes, run this given command:

```sh
$ php app/console router:debug --env=prod
```

You will get this output:

    [router] Current routes
    Name                   Method Scheme Host Path
    ifensl_navbar_menu_nav ANY    ANY    ANY  /nav.{_format}

The configurated menu is now available via the HTTP protocol in diffrent formats.
To see it in action simply open a web browser at the previous configurated
virtual host (navbar.ens-lyon.fr) and add the following path: /nav.{_format}

ex: http://navbar.ens-lyon.fr/nav.html

Here is a list of available formats:

    * html => This will display the menu
    * css  => This is the generated css
    * json => This is the menu data in Json format
    * js   => This is the generated js to inject the menu in web page


How to use
==========

Diffrent way to use this application exist, here is some methods but some others
are posible according to your imagination.

Javascript:
-----------

Simply add a javascript source to automatically inject html and css data in order
to display this menu in an existing website.

```html
<!DOCTYPE html>
<html>
    <head>
        ...
        <script type="text/javascript" src="http://navbar.ens-lyon.fr/nav.js"></script>
    </head>
    <body>
        ...
    </body>
</html>
```


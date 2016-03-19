# Parsemd
php script to display [Markdown Extra](https://michelf.ca/projects/php-markdown/extra/) files from the web.[^1]

Prettifies code with [google prettify](https://github.com/google/code-prettify) and uses [parsedown](https://github.com/erusev/parsedown)-[extra](https://github.com/erusev/parsedown-extra) to convert markdown into html. It also parses inline [LaTeX](https://www.latex-project.org) not in code-blocks using [mathjax](https://www.mathjax.org).

See it in [action](http://unethische.org/misc/parsemd/parsemd.php?file=README.md).

## Usage

Open `parsemd.php` in your browser. The markdown-file is specified with the GET variable `file`, e.g.

    https://www.my-domain.com/parsemd.php?file=README.md&skin=sons-of-obsidian&linenums

The `linenums` argument (at the end of the url) activates linenumbers for code blocks.
The `skin` argument specifies the code hightlighting skin to use with code-prettify.

Here is an example of code, that `parsemd` prettifies automatically:

    <?php
       error_reporting(E_ALL);
       ini_set("display_errors", 1);

      $var = "";
      for ($x=0; $x<=10; $x++) {
          $var .= "Some text<br>";
      }
    ?>
    <html><head>
        <title>A Title</title>
    </head><body>
        <p><?php echo $var; ?></p>
    </body></html>

It honors `<?prettify ?>` commands (see prettify's [Getting Started](https://github.com/google/code-prettify/blob/master/docs/getting_started.md#language-hints)).

Markdown meta-data can be included at the very top of the markdown file.
An example would looke like this:

    ####################################################
    Title:  Readme for parsemd
    Date:   Sat, Mar 19 - 01:53 PM
    Author: con-f-use
    E-Mail: con-f-use (at) gmx (dot) net
    Description: This is some text describing the file,
        it continues here and goes on and on and on and
        further on. The indent for contionuation must
        be at least 4 spaces. Keywords can only contain
        letters, numbers, a dash (-) or an underscore
        (_).
    ####################################################

### Updating

You can update the script with a simple `git pull` when in the cloning directory. If you're feeling cutting-edge also do a `git submodule update --remote`.

## Requires

This scripts needs [php](https://secure.php.net), a web-browser, a web-server and a working internet connection.

### PHP Installation (Ubuntu)

To install a webserver on your local machine (for testing purposes), use:

<?prettify lang=bsh?>

    sudo apt-get install -y lamp-server^

Mind the `^`[^2] and choose a strong MySQL password, when asked.
Access your shiny new local webserver from [within your browser](http://localhost).
If all went well, you should see the "Apache2 Ubuntu Default Page"
Files on the server are normally only visible to the local network.

After installation, `/var/www/html` contains all files served out by your webserver.
Settings are stored in `/etc/php5/apache2/php.ini`, if you change them you might have to run `sudo service apache2 restart`.
For security reasons all files in there should belong to the user `www-data`.
To make sure they do, that your normal user has access and that new files are created with the right permissions run:

<?prettify lang=bsh?>

    sudo chgrp -R www-data /var/www                           # set group
    sudo chmod -R g+rwX /var/www                              # give group read+write
    sudo usermod -a -G www-data $USER                         # add yourself
    find /var/www -type d -print0 | sudo xargs -0 chmod g+s   # inherit groups within dir

You will have to logout completely or restart your computer before you have permissions in `/var/www`.
php-files you tested on your local webserver can later be uploaded to to a remote webserver.

Configure your router to forward port `80` in order for outside users to see your webserver via your IP.[^3]

[^1]: Markdown Extra is a super-set of git-flavored [markdown](https://en.wikipedia.org/wiki/Markdown).
[^2]: The circonfles `^` effectivly invokes `tasksel` from within `apt-get`. `tasksel` is a sort of meta-package or meta-task application grouping packages together.
[^3]: Find out your: [web IP](http://www.getip.com/) address `dig +short myip.opendns.com @resolver1.opendns.com` and configure your router for [port-forwarding](http://www.wikihow.com/Set-Up-Port-Forwarding-on-a-Router) if you want your webserver to accessible over the internet, e.g. with `http://138.232.146.60/myfile.php`.

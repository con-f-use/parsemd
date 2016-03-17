# Parsemd
php script to display [Markdown Extra](https://michelf.ca/projects/php-markdown/extra/) files from the web.[^1]

Prettifies code with [google prettify](https://github.com/google/code-prettify) and uses [parsedown](https://github.com/erusev/parsedown)-[extra](https://github.com/erusev/parsedown-extra) to convert markdown into html. It also parses inline [LaTeX](https://www.latex-project.org) not in code-blocks using [mathjax](https://www.mathjax.org).

See it in [action](http://unethische.org/misc/parsemd/parsemd.php?file=README.md).

## Usage

Open `parsemd.php` in your browser. The markdown-file is specified with the GET variable `file`, e.g.

    https://www.my-domain.com/parsemd.php?file=README.md&linenums

The `linenums` argument (at the end of the url) activates linenumbers for code blocks.

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

### Updating

You can update the script with a simple `git pull` when in the cloning directory. If you're feeling cutting-edge also do a `git submodule update --remote`.

## Requires

This scripts needs [php](https://secure.php.net), a web-browser, a web-server and a working internet connection.

### PHP Installation (Ubuntu)

To install a webserver for testing purposes use:

<?prettify lang=bsh?>

    sudo apt-get install -y lamp-server^

Mind the `^`.[^2] Choose a strong MySQL password. Settings are stored in `/etc/php5/apache2/php.ini`.

After installation, served files are stored in `/var/www/html`.
For security reasons all files in there should belong to the user `www-data`.
To make sure they do, that your normal user has write permissions and that new files are created with the right permissions run:

<?prettify lang=bsh?>

    sudo chgrp -R www-data /var/www
    sudo chmod -R g+rwX /var/www
    sudo usermod -a -G www-data $USER
    find /var/www -type d -print0 | sudo xargs -0 chmod g+s

php-files you tested on your local webserver can later be uploaded to `astro-staff.uibk.ac.at`.

[^1]: Markdown Extra is a super-set of git-flavored [markdown](https://en.wikipedia.org/wiki/Markdown).

[^2]: The circonfles `^` effectivly invokes `tasksel` from within `apt-get`. `tasksel` is a sort of meta-package or meta-task application grouping packages together.

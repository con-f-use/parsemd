<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
    date_default_timezone_set("UTC");

    $mdfl = urldecode($_GET["file"]);
    $ln = isset($_GET['linenums']) ? ' linenums=true' : '';
    $nocpb = isset($_GET['nocpb']) === true;
    $nocfl = isset($_GET['nocfl']) === true;

    $metare    = '~^[ ]{0,3}([A-Za-z0-9_-]+):\s*(.*?)$~';
    $metamore  = '~^[ ]{4,}(.*?)$~';
    $metastart = '~^[\-#=_/\.]{3,}(\s.*?)$~';
    $delim     = '';
    $meta      = array();
    $text      = '';

    try {
        $meta['author'] = posix_getpwuid( fileowner($mdfl) )['name'];
    } catch (Exception $e) {
        $meta['author'] = '';
    }
    try {
        $meta['date'] = date("r", filemtime($mdfl));
    } catch (Exception $e) {
        $meta['date'] = '';
    }

    //ToDo: Improve test below to propperly deal with remote locations, i.e. https://bla.com/foo/bar.md
    //if( !file_exists($mdfl) || !is_readable($mdfl)) die("Error reading '$mdfl'.");
    $fh = fopen($mdfl, "r");

    if(!feof($fh)) {
        $line = fgets($fh);
        if( preg_match($metastart, $line, $fst) ) $delim = $fst[0];
    }

    while ( !feof($fh) ) {
        if( $delim === '' && !isset($otherline) ) {
            $otherline = true;
        } else {
            $line = fgets($fh);
        }
        if( preg_match($metare, $line, $match) ) {
            $key = strtolower( $match[1] );
            $meta[$key] = $match[2];
        }
        elseif( isset($key) && preg_match($metamore, $line, $match) ) {
            $meta[$key] .= $match[1];
        }
        elseif( preg_match($metastart, $line) || !trim($line) ) {
            break;
        }
        else {
            $text .= $line;
            break;
        }
    }

    if( !isset($key)  ) $text .= $delim;
    while( !feof($fh) ) $text .= fgets($fh);

    fclose($fh);

?>
<!DOCTYPE html>
<html><head>
    <title><?php echo isset($meta['title']) ? $meta['title'] : htmlspecialchars($mdfl); ?></title>

    <meta charset="UTF-8">
    <meta class="anchor" name="author"  content="<?php echo $meta['author'];?>">
    <meta class="anchor" name="date"    content="<?php echo $meta['date']; ?>">
<?php if(isset($meta['e-mail'])) { ?>
    <meta class="anchor" name="contact" content="<?php echo $meta['e-mail']; ?>">
<?php } ?>

<?php if(!$nocpb) { ?>
    <script src="https://cdn.rawgit.com/zenorocha/clipboard.js/v1.5.8/dist/clipboard.min.js"></script>
<?php } ?>
    <script type="text/x-mathjax-config">
      MathJax.Hub.Config({
        tex2jax: {
          inlineMath: [ ['$','$'], ["\\(","\\)"] ],
          processEscapes: true,
          skipTags: ["script","noscript","style","textarea","pre","code"]
        }
      });
    </script>
    <script type="text/javascript" async src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-MML-AM_CHTML"></script>
    <script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js?skin=<?php echo isset($_GET['skin']) ? $_GET['skin'] : "sunburst"; ?>"></script><!-- sunburst, doxy, desert, sons-of-obsidian -->

<?php if(!$nocfl) { ?>
    <script type="text/javascript">
        // Hack for converting HTML special characters to regular text, i.e. '&nbsp;' --> ' '
        function decodeHtml(html) {
            var txt = document.createElement("textarea");
            txt.innerHTML = html;
            return txt.value;
        }

        // Save the code in a codeblock to a blob for user download.
        function codesave(nbr) {
            data = document.getElementById('code_'+nbr).innerHTML;
            data = data.replace(/<img class="saveimg".*?>/,'');
            data = data.replace(/<img class="copyclipimg".*?>/,'');
            data = data.replace(/<\/code><\/li>/g, "</code></li><br>\n"); // To work with code numbering
            data = data.replace(/<[^>]*>/g, '');
            data = decodeHtml(data);
            data = [data];
            properties = {type: 'plain/text'};
            try {
               file = new File(data, "codeblock.txt", properties);
            } catch (e) {
               file = new Blob(data, properties);
            }
            url = URL.createObjectURL(file);
            window.open(url);
        }
    </script>
<?php } ?>

    <style type="text/css">

    a {
        border-bottom: 1px dashed #999;
        padding-bottom: 1px;
    }

    a:hover {
        background-color: #aaa;
        color: #378 !important;
    }

    body {
        background: #222;
        color: #fff;
    }

    div.output {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        -webkit-font-smoothing: antialiased;
        font-family: sans-serif;
        text-align: justify;
        width: 35%;
        min-width: 600px;
        margin: auto;
    }

    .output h1, h2, h3, h4, h5, h6 {
        margin-bottom: 0;
        margin-top: 1em;
    }
    .output p, ul, ol, dl {
        margin-top: 0;
        margin-bottom: 0.75em;
    }
    .output a {
        color: #7bd;
    }
    .output a code {
        color: #7bd;
    }
    .output blockquote {
        border-left: 3px solid #999;
        color: #999;
    }
    .output code {
        background: #444;
        border: none;
        border-radius: 2px;
        color: #fff;
    }
    .output div.footnotes {
        font-size: 15px;
    }
    .output pre {
        font-family: monospace;
        text-align: left;
        background: #444;
        padding-left: 2em;
        border-radius: 3px;
        border: none;
        color: #fff;
        overflow: auto;
    }
    .output table {
        border-width: 1px 0 0 1px;
        border-color: #444;
        border-style: solid;
    }
    .output table td, .output table th {
        border-width: 0 1px 1px 0;
        border-color: #444;
        border-style: solid;
        padding: 10px;
    }
    .output li {
        margin-bottom: 0.2em;
    }

    .footnotes {
        font-style: italic;
        font-size: 75% !important;
    }
    .footnotes li {
        margin-bottom: -0.5em;
    }

    #footer {
        font-size: 70%;
    }
    #leftfoot {
        color: #444 !important;
        margin-right: auto;
        float: left;
    }
    #leftfoot a {
        color: #256;
    }
    #rightfoot {
        float: right;
    }

<?php if($ln !== '') { ?>
    li.L0, li.L1, li.L2, li.L3, li.L5, li.L6, li.L7, li.L8 {
        list-style-type: decimal !important;
    }
<?php } ?>
    .copyclipimg {
        height: 1.2em;
        -webkit-filter: invert(1);
        filter: invert(1);
        float: right;
        cursor: pointer;
    }
    .saveimg {
        height: 1.2em;
        -webkit-filter: invert(1);
        filter: invert(1);
        float: right;
        cursor: pointer;
    }

</style>
</head><body>

<div class="output"><a href="<?php echo $mdfl; ?>"><img class="saveimg" alt="Link to this Markdown file" src="http://www.fileformat.info/info/unicode/char/1f4be/floppy_disk.png"></a>

<?php
    require_once "include/parsedown/Parsedown.php";
    require_once "include/parsedown-extra/ParsedownExtra.php";
    $parsedown = new ParsedownExtra();

    $text = $parsedown->text($text);
    $id = 0;
    $codestart = '~<pre><code>~';
    while( preg_match($codestart, $text) ) {
        $cpbstr = '<pre class="prettyprint'.$ln.'" id="code_'.$id.'">';
        if( !$nocfl ) {
            $cpbstr .=
            '<img class="saveimg" '.
            //'alt="save code to file" '.
            'onclick="codesave('.$id.');" '.
            'src="http://www.fileformat.info/info/unicode/char/1f4be/floppy_disk.png">';
        }
        if( !$nocpb ) {
            $cpbstr .=
            '<img class="copyclipimg" '.
            //'alt="copy code to clipboard" '.
            'data-clipboard-target="#code_'.$id.'" '.
            'src="http://www.fileformat.info/info/unicode/char/1f4cb/clipboard.png">';
        }
        $cpbstr .= '<code>';
        $text = preg_replace( $codestart, $cpbstr, $text, 1 );
        ++$id;
    }
    $text = preg_replace('~<p>&lt;\?prettify ?(.*?)\?&gt;</p>~', '<?prettify \1?>', $text);

    echo $text;
    echo ''
?>

<div id="footer">
    <span id="leftfoot">
        Markdown rendered with
        <a href="https://github.com/con-f-use/parsemd">
            parsemd
            <img src="https://raw.githubusercontent.com/github-archive/media/master/octocats/blacktocat-16.png" style="height: 1em;" alt="github-logo">
        </a>
    </span>
    <span id="rightfoot">
        Back to <a href="<?php echo "http://".$_SERVER['SERVER_NAME']; ?>">main page</a>
    </span>
    <div style="clear: both;"></div>
</div>

</div>

<?php if(!$nocpb) { // Conditionally include clipboard ?>
<script type="text/javascript">
    var clipboard = new Clipboard('.copyclipimg');
    clipboard.on('success', function(e) {
        console.log(e);
    });
    clipboard.on('error', function(e) {
        console.log(e);
    });
</script>'
<?php } // ToDo: Figure out how to preprocess clipboard text so the "<img alt="-text of copy to clipboard or open as file doesn't appear. ?>

</body></html>

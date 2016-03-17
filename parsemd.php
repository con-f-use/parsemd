<?php
    error_reporting(-1);
    ini_set('display_errors', 'On');
?>

<html><head><title>Parse Markdown</title>
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
    <script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js?skin=sunburst"></script><!-- sunburst, doxy, desert, sons-of-obsidian -->
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

    button {
        background: #357;
        border: none;
        color: #fff;
        cursor: pointer;
        line-height: 24px;
        text-align: left;
        padding: 20px;
        width: 100%;
    }

    textarea {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        background: #333;
        border: none;
        box-sizing: border-box;
        color: #fff;
        font-family: monospace;
        font-size: 16px;
        height: 100%;
        line-height: 28px;
        margin: 0;
        padding: 20px;
        resize: none;
        vertical-align: top;
        width: 100%;
    }

    pre.prettyprint {
    }
    pre.prettyprint, code.prettyprint {
    }

    div.meta {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        -webkit-font-smoothing: antialiased;
        background: #111;
        box-sizing: border-box;
        bottom: 190px;
        left: 30%;
        padding: 20px;
        position: absolute;
        width: 35%;
    }

    div.td {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        background: #eee;
        box-sizing: border-box;
        color: #222;
        float: left;
        padding: 20px;
        width: 35%;;
    }

    div.output {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        -webkit-font-smoothing: antialiased;
        font-family: sans-serif;
        text-align: justify;
        //bottom: 254px;
        //box-sizing: border-box;
        //overflow: scroll;
        //padding: 20px;
        // position: absolute;
        //top: 64px;
        width: 35%;
        min-width: 600px;
        margin: auto;
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
        font-family: monospace !important;
        text-align: left !important;
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
        margin-top: 0.1em;
        margin-bottom: 0.1em;
    }

    .footnotes {
        font-style: italic;
        font-size: 75% !important;
    }


//    pre .str, code .str { color: #65B042; } /* string  - green */
//    pre .kwd, code .kwd { color: #E28964; } /* keyword - dark pink */
//    pre .com, code .com { color: #AEAEAE; font-style: italic; } /* comment - gray */
//    pre .typ, code .typ { color: #89bdff; } /* type - light blue */
//    pre .lit, code .lit { color: #3387CC; } /* literal - blue */
//    pre .pun, code .pun { color: #fff; } /* punctuation - white */
//    pre .pln, code .pln { color: #fff; } /* plaintext - white */
//    pre .tag, code .tag { color: #89bdff; } /* html/xml tag    - light blue */
//    pre .atn, code .atn { color: #bdb76b; } /* html/xml attribute name  - khaki */
//    pre .atv, code .atv { color: #65B042; } /* html/xml attribute value - green */
//    pre .dec, code .dec { color: #3387CC; } /* decimal - blue */

//    ol.linenums { margin-top: 0; margin-bottom: 0; color: #AEAEAE; }
    li.L0, li.L1, li.L2, li.L3, li.L5, li.L6, li.L7, li.L8 {
        list-style-type: decimal !important;
    }

//    @media print {
//        pre .str, code .str { color: #060; }
//        pre .kwd, code .kwd { color: #006; font-weight: bold; }
//        pre .com, code .com { color: #600; font-style: italic; }
//        pre .typ, code .typ { color: #404; font-weight: bold; }
//        pre .lit, code .lit { color: #044; }
//        pre .pun, code .pun { color: #440; }
//        pre .pln, code .pln { color: #000; }
//        pre .tag, code .tag { color: #006; font-weight: bold; }
//        pre .atn, code .atn { color: #404; }
//        pre .atv, code .atv { color: #060; }
//    }
</style>
</head><body> <!--onload="prettyPrint()"-->

<div class="output">
<?php
    require_once "include/parsedown/Parsedown.php";
    require_once "include/parsedown-extra/ParsedownExtra.php";
    $parsedown = new ParsedownExtra();

    $mdfl = urldecode($_GET["file"]);
    $text = file_get_contents($mdfl);
    $text = $parsedown->text($text);
    $ln = isset($_GET['linenums']) ? ' linenums=true' : '';
    $text = str_replace('<pre>', '<pre class="prettyprint'.$ln.'">', $text); // linenums
    $text = preg_replace('~<p>&lt;\?prettify ?(.*?)\?&gt;</p>~', '<?prettify \1?>', $text);

    echo $text;
    echo ''
?>
<p style="font-size: 70%; text-align: right;">Back to <a href="./">main page</a>.</p>

</body></html>

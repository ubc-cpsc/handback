<?php

/**
 * Recursive function returns directory tree listing.
 *
 * @param $path
 * @param $allowed_filenames
 * @param int $level
 *
 * @return string
 */
function getDirectory($path, $allowed_filenames, $level = 0)
{
    // Directories/files to ignore when listing output.
    $ignore = array( 'cgi-bin', '.', '..' );

    // Need to know the base URL...
    $pathsplit = explode('/', $path);
    $pathcount = count($pathsplit);
    $base = implode('/', array_slice($pathsplit, $pathcount-$level));
    if ($base !== '') {
        $base .= '/';
    }

    $retval = '';

    $dh = @opendir($path);
    while (false !== ( $file = readdir($dh) )) {
        if (!in_array($file, $ignore)) {
            if (is_dir("$path/$file")) {
                $retval .= str_repeat(' ', ( $level * 4 )); // indenting
                $retval .= "<strong>$file</strong>\n";
                $retval .= getDirectory("$path/$file", $allowed_filenames, ($level+1));
            } else {
                if (preg_match($allowed_filenames, $file, $matches)) {
                    $retval .= str_repeat(' ', ( $level * 4 )); // indenting
                    $retval .= "<a href=\"?file=$base$file\">$file</a>\n";
                }
            }
        }
    }
    closedir($dh);

    return $retval;
}

$course = 'cs333';
$dir = '/home/c/cs333/public_html/handback/scans';
$ruser = $_SERVER['REMOTE_USER'];
# don't allow periods in directory names. Prevents hacking using ../
$allowed_filenames = "/^[a-zA-Z0-9]+[-\/_a-zA-Z0-9]*[-_\.a-zA-Z0-9]*".$ruser."\.pdf$/";

if (!$ruser) {
    exit;
}

$errors = array();
$htmlout = '';
if (is_dir($dir) && is_readable($dir)) {
    if (isset($_GET['file'])) {
        if (preg_match($allowed_filenames, $_GET['file'], $matches)) {
            $file = htmlspecialchars($matches[0], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $htmlout .= "<p>File specified: " . $file . "</p>\n";
            $dir = $dir . '/' . $file;
            if (is_file($dir)) {
                $htmlout .= "File exists: " . $dir;
                if ($fd = fopen($dir, "r")) {
                    $fsize = filesize($dir);
                    $path_parts = pathinfo($dir);
                    header("Content-type: application/octet-stream");
                    header("Content-disposition: filename=\"".$path_parts["basename"]."\"");
                    header("Content-length: $fsize");
                    header("Cache-control: private");
                    while (!feof($fd)) {
                        $buffer = fread($fd, 2048);
                        echo $buffer;
                    }
                    fclose($fd);
                    exit;
                } else {
                    $htmlout .= "Couldn't open file: " . $dir;
                }
            } else {
                $htmlout .= "No such file: " . $dir;
            }
        } else {
            $htmlout .= "Bad file parameter\n";
        }
    } else {
        $htmlout .= "Exams you've written:<br>\n";
        $htmlout .= "<blockquote><pre>\n";
        $htmlout .= getDirectory($dir, $allowed_filenames);
        $htmlout .= "</pre></blockquote>\n";
    }
} else {
    $htmlout .= "There is a problem with the scans dir... ";
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Handback</title>

</head>
<body>
<h2>Download $course exams handed in by <?php print $ruser; ?></h2>
<?php
print $htmlout;

#print_r("<br>$dir<br>\n");
#print_r("<br><pre>");
#var_dump($GLOBALS);
#var_dump($_SERVER);
#print_r("</pre>");
?>
</body>
</html>

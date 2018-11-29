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

$ruser = $_SERVER['REMOTE_USER'];
if (!$ruser) {
    echo "No user id!";
    exit;
}

$course      = 'csNNN';
$handbackDir = '/home/c/csNNN/public_html/handback/deliverThis';
$heading     = "<h2>Download $course files for $ruser</h2>";
$subheading  = "Files for you:<br>";

# don't allow periods in directory names. Prevents hacking using ../
$allowed_filenames = "/^[a-zA-Z0-9]+[-\/_a-zA-Z0-9]*[-_\.a-zA-Z0-9]*".$ruser."\.pdf$/";

# Put overrides of above parameters in a separate file.
if (file_exists('handback.cfg')) {
    include 'handback.cfg';
}

$errors = array();
$htmlout = '';
if (is_dir($handbackDir) && is_readable($handbackDir)) {
    if (isset($_GET['file'])) {
        if (preg_match($allowed_filenames, $_GET['file'], $matches)) {
            $file = htmlspecialchars($matches[0], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $htmlout .= "<p>File specified: " . $file . "</p>\n";
            $handbackDir = $handbackDir . '/' . $file;
            if (is_file($handbackDir)) {
                $htmlout .= "File exists: " . $handbackDir;
                if ($fd = fopen($handbackDir, "r")) {
                    $fsize = filesize($handbackDir);
                    $path_parts = pathinfo($handbackDir);
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
                    $htmlout .= "Couldn't open file: " . $handbackDir;
                }
            } else {
                $htmlout .= "No such file: " . $handbackDir;
            }
        } else {
            $htmlout .= "Bad file parameter\n";
        }
    } else {
        $htmlout .= "$subheading\n";
        $htmlout .= "<blockquote><pre>\n";
        $htmlout .= getDirectory($handbackDir, $allowed_filenames);
        $htmlout .= "</pre></blockquote>\n";
    }
} else {
    $htmlout .= "There is a problem with the handback dir... ";
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Handback</title>

</head>
<body>
<?php
print $heading;
print $htmlout;

#print_r("<br>$handbackDir<br>\n");
#print_r("<br><pre>");
#var_dump($GLOBALS);
#var_dump($_SERVER);
#print_r("</pre>");
?>
</body>
</html>

<?php
// converts new and updated puml files to png in the dirctory of this script


function encodep($text)
{
    $data = utf8_encode($text);
    $compressed = gzdeflate($data, 9);
    return encode64($compressed);
}

function encode6bit($b)
{
    if ($b < 10) {
        return chr(48 + $b);
    }
    $b -= 10;
    if ($b < 26) {
        return chr(65 + $b);
    }
    $b -= 26;
    if ($b < 26) {
        return chr(97 + $b);
    }
    $b -= 26;
    if ($b == 0) {
        return '-';
    }
    if ($b == 1) {
        return '_';
    }
    return '?';
}

function append3bytes($b1, $b2, $b3)
{
    $c1 = $b1 >> 2;
    $c2 = (($b1 & 0x3) << 4) | ($b2 >> 4);
    $c3 = (($b2 & 0xF) << 2) | ($b3 >> 6);
    $c4 = $b3 & 0x3F;
    $r = "";
    $r .= encode6bit($c1 & 0x3F);
    $r .= encode6bit($c2 & 0x3F);
    $r .= encode6bit($c3 & 0x3F);
    $r .= encode6bit($c4 & 0x3F);
    return $r;
}

function encode64($c)
{
    $str = "";
    $len = strlen($c);
    for ($i = 0; $i < $len; $i+=3) {
        if ($i+2==$len) {
            $str .= append3bytes(ord(substr($c, $i, 1)), ord(substr($c, $i+1, 1)), 0);
        } elseif ($i+1==$len) {
            $str .= append3bytes(ord(substr($c, $i, 1)), 0, 0);
        } else {
            $str .= append3bytes(
                ord(substr($c, $i, 1)),
                ord(substr($c, $i+1, 1)),
                ord(substr($c, $i+2, 1))
            );
        }
    }
    return $str;
}

$dir = dirname(__FILE__);
echo "dir $dir\n\n";
$iterator = new DirectoryIterator($dir);
foreach ($iterator as $fileinfo) {
    if ($fileinfo->isFile() && $fileinfo->getExtension()=='puml') {
        $pumlFile = $fileinfo->getPathName();
        echo "PUML: $pumlFile \n";

        $pngFile = $dir . DIRECTORY_SEPARATOR . $fileinfo->getBaseName('.puml') . '.png';
        echo "  png: $pngFile \n";
        $stat = @stat($pngFile);
        if (!file_exists($pngFile) || $stat['mtime'] < $fileinfo->getMTime()) {
            $puml = file_get_contents($pumlFile);
            $encode = encodep($puml);
            $url = "http://www.plantuml.com/plantuml/png/{$encode}";
            echo "  $url";
            file_put_contents($pngFile, file_get_contents($url));
            echo "  saved\n\n";
        } else {
            echo "  PNG up to date \n\n";
        }
    }
}

<?php
function checkFileSize($file)
{
    // var_dump($_SERVER['HTTP_RANGE']);
    //First, see if the file exists
    if (!is_file($file))
    {
        die("<b>404 File not found!</b>");
    }
    header('Total-Content-Length: '. filesize($file));
}

checkFileSize('test.mp4');
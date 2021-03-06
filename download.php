<?php
function dl_file_resumable($file, $is_resume=TRUE)
{
    // var_dump($_SERVER['HTTP_RANGE']);
    //First, see if the file exists
    if (!is_file($file))
    {
        die("<b>404 File not found!</b>");
    }

    //Gather relevent info about file
    $size = filesize($file);
    $fileinfo = pathinfo($file);
   
    //workaround for IE filename bug with multiple periods / multiple dots in filename
    //that adds square brackets to filename - eg. setup.abc.exe becomes setup[1].abc.exe
    $filename = (strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) ?
                  preg_replace('/\./', '%2e', $fileinfo['basename'], substr_count($fileinfo['basename'], '.') - 1) :
                  $fileinfo['basename'];
   
    $file_extension = 'mp4';//strtolower($path_info['extension']); // i want to stream the data

    //This will set the Content-Type to the appropriate setting for the file
    switch($file_extension)
    {
        case 'exe': $ctype='application/octet-stream'; break;
        case 'txt': $ctype='application/txt'; break;
        case 'png': $ctype='application/png'; break;
        case 'zip': $ctype='application/zip'; break;
        case 'mp3': $ctype='audio/mpeg'; break;
        case 'mp4': $ctype='video/mp4'; break;
        case 'avi': $ctype='video/x-msvideo'; break;
        default:    $ctype='application/force-download';
    }

    //check if http_range is sent by browser (or download manager)
    if ($is_resume && isset($_SERVER['HTTP_RANGE'])) {
        list($size_unit, $range_orig) = explode('=', $_SERVER['HTTP_RANGE'], 2);

        if ($size_unit == 'bytes') {
            //figure out download piece from range (if set)
            list($seek_start, $seek_end) = explode('-', $range_orig, 2);
        } else {
            $range = '';
        }
    } else {
        var_dump("HTTP_RANGE not set in server global");
        exit;
        $range = '';
    }
   
    //set start and end based on range (if set), else set defaults
    //also check for invalid ranges.
    $seek_end = (empty($seek_end)) ? ($size - 1) : min(abs(intval($seek_end)), ($size - 1));
    $seek_start = (empty($seek_start) || $seek_end < abs(intval($seek_start))) ? 0 : max(abs(intval($seek_start)), 0);

    //add headers if resumable
    if ($is_resume) {
        //Only send partial content header if downloading a piece of the file (IE workaround)
        if ($seek_start > 0 || $seek_end < ($size - 1)) {
            header('HTTP/1.1 206 Partial Content');
        }

        header('Accept-Ranges: bytes');
        header('Content-Range: bytes '.$seek_start.'-'.$seek_end.'/'.$size);
    }

    // headers for IE Bugs (is this necessary?);
    // header("Cache-Control: cache, must-revalidate");
    // header("Pragma: public");

    header('Content-Type: ' . $ctype);
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: '.($seek_end - $seek_start + 1));
    header('Total-Content-Length: '. filesize($file));


    //open the file
    $fp = fopen($file, 'rb');
    //seek to start of missing part
    fseek($fp, $seek_start);

    //start buffered download
    while (!feof($fp)) {
        //reset time limit for big files
        set_time_limit(0);
        print(fread($fp, 1024*8));
        flush();
        ob_flush();
    }

    fclose($fp);
}

dl_file_resumable('test.mp4');

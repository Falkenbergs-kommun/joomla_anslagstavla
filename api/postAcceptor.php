<?php

require_once('/home/httpd/fbg-intranet/dev-intra.falkenberg.se/fbg_apps/include/include1.php');

$output = [];
$output['data'] = [];
$output['status'] = false;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

jsonHeader();

$baseurl = 'https://kommun.falkenberg.se/';
$basedir = '/home/httpd/fbg-intranet/kommun.falkenberg.se/';

$uploads_dir = "images/anslagstavla";
$tmp_name = $_FILES['files']['tmp_name'][0];
$image_name = $_FILES['files']['name'][0];

$fileSize = filesize($tmp_name);
$fileinfo = finfo_open(FILEINFO_MIME_TYPE);
$filetype = finfo_file($fileinfo, $tmp_name);

if ($fileSize === 0) {
    $output['status'] = false;
    $output['message'] = 'Filen saknar data';
    echo json_encode($output);
    exit;
}

$allowedTypes = [
    'application/pdf' => 'pdf'
];

if (!in_array($filetype, array_keys($allowedTypes))) {
    $output['status'] = false;
    $output['message'] = 'Otillåten filtyp';
    echo json_encode($output);
    exit;
}

$extension = $allowedTypes[$filetype];

$new_name = "notice" . strval(time()) . '.' . $extension;


$new_place = $basedir . $uploads_dir . "/" . $new_name;
$new_url = $baseurl . $uploads_dir . "/" . $new_name;

if (move_uploaded_file($tmp_name, $new_place)) {
    
    $output['url'] = $new_url;
    $output['status'] = true;
} else {
    $output['status'] = false;
    unlink($tmp_name); // Delete the temp file
}

echo json_encode($output);
exit;

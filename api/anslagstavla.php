<?php

// Hantera anslag

require_once('/home/httpd/fbg-intranet/dev-intra.falkenberg.se/fbg_apps/include/include1.php');
require_once('/home/httpd/fbg-intranet/dev-intra.falkenberg.se/fbg_apps/include/migrera.php');


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$method = $_SERVER['REQUEST_METHOD'];

$output = [];
$output['data'] = [];

switch ($method) {
    case 'POST':
        $output['result'] = nyttAnslag();
        break;

    case 'PATCH':
        $output['result'] = uppdateraAnslag();
        break;

    case 'DELETE':
        //
        break;

    case 'GET':
        $output['data'] = getAnslag();
        break;

    default:
        header('HTTP/1.1 405 Method Not Allowed');
        header('Allow: GET, PATCH, POST, DELETE');
        break;
}


jsonHeader();
echo json_encode($output);

exit;

function nyttAnslag()
{
    $result = [];
    $payload = json_decode(file_get_contents('php://input'), true);
    $apiresult = json_decode(createNotice($payload), true);

    if (!isset($apiresult['errors'])) {
        $result['id'] = $apiresult['data']['attributes']['id'];
        $result['version'] = $apiresult['data']['attributes']['version'];
        $result['state'] = $apiresult['data']['attributes']['state'];
    } else {
        $result['errors'] = $apiresult['errors'];
    }



    return $result;
}

function uppdateraAnslag()
{
    $result = [];
    $payload = json_decode(file_get_contents('php://input'), true);
    $apiresult = json_decode(createNotice($payload), true);

    if (!isset($apiresult['errors'])) {
        $result['id'] = $apiresult['data']['attributes']['id'];
        $result['version'] = $apiresult['data']['attributes']['version'];
        $result['state'] = $apiresult['data']['attributes']['state'];
    } else {
        $result['errors'] = $apiresult['errors'];
    }

    return $result;
}

function getAnslag()
{
    global $pdo;

    $pdo->exec('USE fbg_servicewebb');

    $sql = "SELECT
`a70hd_content`.`id`,
`a70hd_content`.`title`,
`a70hd_content`.`created`,
`a70hd_content`.`created_by`,
`a70hd_content`.state,
`a70hd_content`.publish_up,
`a70hd_content`.publish_down,
`a70hd_content`.modified,
DATE(t_noticedatemeeting.value) AS noticedatemeeting,
DATE(t_noticedateadjusted.value) AS noticedateadjusted,
DATE(t_noticedateposted.value) AS noticedateposted,
t_noticedocumentlocation.value AS noticedocumentlocation,
t_noticecontactperson.value AS noticecontactperson,
t_noticecontactemail.value AS noticecontactemail,
DATE(t_noticedateremoval.value) AS noticedateremoval,
t_noticetype.value AS noticetype,
t_noticeattachment.value AS noticeattachment,
t_noticelink.value AS noticelink
FROM
`a70hd_content`
LEFT JOIN a70hd_fields_values AS t_noticedatemeeting
ON
t_noticedatemeeting.item_id = a70hd_content.id AND t_noticedatemeeting.field_id = 8
LEFT JOIN a70hd_fields_values AS t_noticedateadjusted
ON
t_noticedateadjusted.item_id = a70hd_content.id AND t_noticedateadjusted.field_id = 9
LEFT JOIN a70hd_fields_values AS t_noticedateposted
ON
t_noticedateposted.item_id = a70hd_content.id AND t_noticedateposted.field_id = 10
LEFT JOIN a70hd_fields_values AS t_noticedocumentlocation
ON
t_noticedocumentlocation.item_id = a70hd_content.id AND t_noticedocumentlocation.field_id = 12
LEFT JOIN a70hd_fields_values AS t_noticecontactperson
ON
t_noticecontactperson.item_id = a70hd_content.id AND t_noticecontactperson.field_id = 13
LEFT JOIN a70hd_fields_values AS t_noticecontactemail
ON
t_noticecontactemail.item_id = a70hd_content.id AND t_noticecontactemail.field_id = 14
LEFT JOIN a70hd_fields_values AS t_noticedateremoval
ON
t_noticedateremoval.item_id = a70hd_content.id AND t_noticedateremoval.field_id = 15
LEFT JOIN a70hd_fields_values AS t_noticetype
ON
t_noticetype.item_id = a70hd_content.id AND t_noticetype.field_id = 25
LEFT JOIN a70hd_fields_values AS t_noticeattachment
ON
t_noticeattachment.item_id = a70hd_content.id AND t_noticeattachment.field_id = 26
LEFT JOIN a70hd_fields_values AS t_noticelink
ON
t_noticelink.item_id = a70hd_content.id AND t_noticelink.field_id = 33
WHERE
`catid` = 14 AND `a70hd_content`.`created` > NOW() - INTERVAL 1 YEAR AND `a70hd_content`.state >= 0
ORDER BY
`state` ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createNotice($row)
{

    $payload = [];
    $tags = [];

    $articletext = 'Anslag';

    // $payload['alias'] = 'test';

    $typ = intval($row['form-type']);

    $anslagtyp[2] = 2; // Kungörelse
    $anslagtyp[1] = 4; // Protokoll
    $anslagtyp[3] = 35; // Underrättelse

    if (isset($anslagtyp[$typ])) {
        $tags[] = $anslagtyp[$typ];
        $payload['noticetype'] =  $typ;
    }

    $payload['noticecontactperson'] = $row['form-noticecontactperson'];

    $payload['articletext'] =  $articletext;
    $payload['catid'] = 14;
    $payload['tags'] = $tags;

    // $payload['created'] = $row['creation_date'];
    // $payload['modified'] = $row['last_modified_date	'];
    // $payload['publish_up'] = $row['publish_date'];

    if (isset($row['form-noticedatemeeting'])) {
        $payload['noticedatemeeting'] = $row['form-noticedatemeeting'];
    }

    if (isset($row['form-noticedateadjusted'])) {
        $payload['noticedateadjusted'] = $row['form-noticedateadjusted'];
    }

    if (isset($row['form-noticedateposted'])) {
        $payload['noticedateposted'] = $row['form-noticedateposted']; // Sätt både det synliga datumet och publicering. Publiceringsdatum kräver fullständig timestamp för att det inte ska bli 500-fel
        $payload['publish_up'] = substr($row['form-noticedateposted'], 0, 10) . ' 00:30:00';
    }

    if (isset($row['form-noticedateremoval'])) { // Sätt både det synliga datumet och avpublicering. Publiceringsdatum kräver fullständig timestamp för att det inte ska bli 500-fel
        $payload['noticedateremoval'] = $row['form-noticedateremoval'];
        $payload['publish_down'] = substr($row['form-noticedateremoval'], 0, 10) . ' 23:59:59';
    }


    $payload['noticedocumentlocation'] = $row['form-noticedocumentlocation'];

    if (isset($row['form-published'])) {
        $payload['state'] = intval($row['form-published']);
    } else {
        $payload['state'] = 1;
    }



    $payload['noticecontactperson'] = $row['form-noticecontactperson'];
    $payload['noticecontactemail'] = $row['form-noticecontactemail'];
    $payload['noticeattachment'] = $row['form-noticeattachment'];
    $payload['noticelink'] = $row['form-noticelink'];
    // $payload['publishedby'] = $page_object['properties']['publishedBy']['properties']['mail'];
    $payload['language'] = '*';
    $payload['metadesc'] = '*';
    $payload['metakey'] = '*';
    $payload['title'] = $row['form-title'];
    // $payload['condition'] = 2;

    if (isset($row['noticeID']) && $row['noticeID'] > 0) {
        $id = strval($row['noticeID']);
        return JoomlaApiPatch('/content/articles/' . $id, $payload);
    } else {
        return JoomlaApiPost('/content/articles', $payload);
    }
}

<?php

//header('Content-Type: text/plain; charset=utf-8');

ini_set('max_execution_time', 0);

try {

    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (
        !isset($_FILES['upfile']['error']) || is_array($_FILES['upfile']['error'])
    ) {
        throw new RuntimeException('Invalid parameters.');
    }

    // Check $_FILES['upfile']['error'] value.
    switch ($_FILES['upfile']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    // You should also check filesize here.
    if ($_FILES['upfile']['size'] > 1000000) {
        throw new RuntimeException('Exceeded filesize limit.');
    }

    // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
    // Check MIME Type by yourself.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
            $finfo->file($_FILES['upfile']['tmp_name']),
            array(
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ),
            true
        )) {
        throw new RuntimeException('Invalid file format.');
    }

    // You should name it uniquely.
    // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.
    $filename = sha1_file($_FILES['upfile']['tmp_name']);
    if (!move_uploaded_file(
        $_FILES['upfile']['tmp_name'],
        sprintf('./uploads/%s.%s',
            $filename,
            $ext
        )
    )) {
        throw new RuntimeException('Failed to move uploaded file.');
    }

    $filename = $filename . '.' . $ext;

} catch (RuntimeException $e) {

    echo $e->getMessage();

}


include("vendor/autoload.php");
include("MessiObject.php");

use PhpOffice\PhpSpreadsheet;


$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
$reader->setReadDataOnly(TRUE);
$spreadsheet = $reader->load("uploads/{$filename}");

$worksheet = $spreadsheet->getActiveSheet();
$rows = [];
$lastSection = '';
$counter = 0;
$sections = Array('Gauteng:', 'KZN', 'CPT');

foreach ($worksheet->getRowIterator() AS $row) {
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
    $cells = [];
    $cellcount = 0;
    foreach ($cellIterator as $cell) {
        $cells[] = $cell->getValue();
        $cellcount++;
        if ($cellcount > 6) {
            break;
        }
    }
    if ($cells[0] == null) {
        continue;
    } elseif ($cells[0] == 'Name') {
        continue;
    } elseif (in_array($cells[0], $sections)) {
        $lastSection = $cells[0];
        continue;
    } else {
        array_unshift($cells, $lastSection);
    }

    $lineObject[] = new MessiObject($cells);

}

$colour1 = "#ffffff";
$colour2 = "#eeeeee";


echo "<table><tr bgcolor='#adff2f'><td>Section</td><td>Name</td><td>Publication</td><td>Publication Data Issues</td><td>Phone Number</td><td>Phone Number Issues</td><td>Email Addresses</td><td>Email Address Test Results</td><td>Join Date</td><td>Join Date Issues</td></tr>";
$counter = 0;
foreach ($lineObject as $object) {
    $counter++;
    if ($counter % 2 == 0) {
        $defColour = $colour1;
    } else {
        $defColour = $colour2;
    }
    echo "<tr bgcolor='{$defColour}'><td>" . $object->getSection() . "</td>";
    echo "<td>" . $object->getName() . "</td>";
    echo "<td>" . $object->getPublication() . "</td>";
    echo "<td>" . $object->getTPublication() . "</td>";
    echo "<td>";

    foreach ($object->getContactNr() as $nr) {
        echo $nr . "<br/>";
    }

    echo "</td>";
    echo "<td>" . $object->getTContactNr() . "</td>";
    echo "<td>";
    foreach ($object->getEmail() as $email) {
        echo $email . "<br/>";
    }
    echo "</td>";
    echo "<td>" . $object->getTEmail() . "</td>";
    echo "<td>" . $object->getJoinDate() . "</td>";
    echo "<td>" . $object->getTJoinDate() . "</td></tr>";


}
echo "</table>";


<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 31.05.2019
 * Time: 18:35
 */

namespace App\Handlers;

include $_SERVER['DOCUMENT_ROOT']."/App/Classes/CodeImport.php";

use App\Classes\CodeImport;
use Exception;


if(!isset($_SERVER["REQUEST_METHOD"]) && empty($_SERVER["REQUEST_METHOD"]) && strtolower($_SERVER["REQUEST_METHOD"]) != 'post') {
    echo json_encode(["code" => 404, "msg" => 'Use Post Request']);
    exit;
}

$uploads_dir = $_SERVER['DOCUMENT_ROOT'].'/uploads/csv/';
if ($_FILES["csv_code"]["error"] == UPLOAD_ERR_OK) {
    $tmp_name = $_FILES["csv_code"]["tmp_name"];
    $name = $_FILES["csv_code"]["name"];
    $filename = "$uploads_dir$name";
    move_uploaded_file($tmp_name, $filename);
    try {
        $importObj = new CodeImport($filename);
    } catch (Exception $e) {
       echo json_encode(["code" => 500, "msg" => "Ошибка: ". $e->getMessage()]);
       exit;
    }
    try {
        echo json_encode(["code" => 200, "msg" =>  $importObj->processing()]);
    } catch (Exception $e) {
        echo json_encode(["code" =>  500, "msg" => "Ошибка: ". $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(["code" => 500, "msg" => "Ошибка передачи файла"]);
    exit;
}

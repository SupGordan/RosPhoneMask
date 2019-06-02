<?php
/**
 * Created by PhpStorm.
 * User: vadim
 * Date: 31.05.2019
 * Time: 18:34
 */


namespace App\Classes;

use Exception;

error_reporting(0);

class CodeImport
{
    private $file;

    public function __construct($filename) {
        if (file_exists($filename)) {
            if($this->file = fopen($filename, "rt")) {
            } else throw new Exception("Не удается открыть файл");
        } else {
            throw new Exception('Файл не найден.');
        }
    }

    private function validate($phoneData) {
        if (count($phoneData) < 4) return true; else return false;
    }

    /**
     *  Create json file from csv file
     */
    public function processing() {
        $resultData = array();
        for ($i=0; ($data=fgetcsv($this->file,1000,";"))!==false; $i++) {
            if (self::validate($data)) continue;
            $phone = $data[0] . $data[1];
            $mask = substr($phone,0, strlen($phone) - (strlen($data[3]) - 1));
            $operator = iconv('windows-1251', 'UTF-8', $data[4]);
            $resultData[$operator][] =  $mask;
        }
        $fileNameJson = $_SERVER['DOCUMENT_ROOT']."/uploads/json/".date('m_d_Y_H_i').".json";
        if (file_put_contents($fileNameJson, json_encode($resultData))) {
            return str_replace($_SERVER['DOCUMENT_ROOT'],"",$fileNameJson);
        } else throw new Exception('Не удается сохранить файл');
    }
}
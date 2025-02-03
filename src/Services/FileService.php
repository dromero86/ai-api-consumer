<?php

namespace Tero\Services;

class FileService {
    public static function readFile($path) {
        $result = '';
        if (file_exists($path)) {
            $handle = fopen($path, "r");
            while (!feof($handle)) {
                $result .= fgets($handle);
            }
            fclose($handle);
        } else {
            echo 'File not found';
        }
        return $result;
    }

    public static function writeFile($path, $text) {
        if (file_exists($path)) {
            file_put_contents($path, $text);
        } else {
            echo 'Cannot write to non-existing file';
        }
    }
}
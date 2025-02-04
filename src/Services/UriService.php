<?php

namespace Tero\Services;

class UriService
{
    public function redirect($url)
    {
        header("Location: $url");
        exit();
    }

    public function baseurl()
    {
        return rtrim((isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]", '/') . '/';
    }
}
<?php

namespace Tero\Services;

class FtpService
{
    private $connection;
    private $login;

    public function connect($host, $port = 21, $username, $password)
    {
        $this->connection = ftp_connect($host, $port);
        $this->login = ftp_login($this->connection, $username, $password);
        return $this->connection && $this->login;
    }

    public function disconnect()
    {
        if ($this->connection) {
            ftp_close($this->connection);
        }
    }

    public function listFiles($directory)
    {
        return ftp_nlist($this->connection, $directory);
    }

    public function uploadFile($localFile, $remoteFile)
    {
        return ftp_put($this->connection, $remoteFile, $localFile, FTP_BINARY);
    }

    public function downloadFile($remoteFile, $localFile)
    {
        return ftp_get($this->connection, $localFile, $remoteFile, FTP_BINARY);
    }
}
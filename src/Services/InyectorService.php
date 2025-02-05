<?php 

namespace Tero\Services;

class InyectorService
{
    private $services = [];

    public function __construct(string $namespace, string $pattern)
    {
        $this->loadServices($namespace, $pattern);
    }

    private function loadServices(string $namespace, string $pattern)
    {
        $directory = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
        $files = glob($directory . '/' . $pattern . '.php');
        foreach ($files as $file) {
            $className = $namespace . '\\' . basename($file, '.php');
            if (class_exists($className)) {
                $this->services[] = new $className();
            }
        }
    }

    public function getServices()
    {
        return $this->services;
    }
}
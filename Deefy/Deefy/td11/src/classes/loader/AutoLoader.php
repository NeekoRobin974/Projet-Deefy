<?php

declare(strict_types=1);

namespace iutnc\deefy\loader;

class AutoLoader{
    private $prefix;
    private $baseDir;   //Répertoire où se trouvent les classes

    /**
     * Constructeur
     * @param $prefix
     * @param $baseDir
     */
    public function __construct($prefix, $baseDir){
        $this->prefix = $prefix;
        $this->baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * Méthode pour charger une classe
     * @param $className
     * @return void
     */
    public function loadClass($className){
        $classPath = str_replace($this->prefix, $this->baseDir, $className);
        $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $classPath) . '.php';
        if(is_file($classPath)){
            require_once $classPath;
        }
    }

    /**
     * Méthode pour enregistrer l'autoloader
     * @return void
     */
    public function register(){
        spl_autoload_register([$this, 'loadClass']);
    }
}
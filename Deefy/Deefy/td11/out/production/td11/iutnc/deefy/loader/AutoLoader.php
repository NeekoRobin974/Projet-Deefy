<?php
namespace iutnc\deefy\loader;

class AutoLoader {
    private $prefix;
    private $baseDir;

    public function __construct($prefix, $baseDir) {
        $this->prefix = $prefix;
        $this->baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    public function loadClass($className) {
        $classPath = str_replace($this->prefix, $this->baseDir, $className);

        // Remplacer les backslashes par des slashes et ajouter ".php" pour obtenir le chemin du fichier
        $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $classPath) . '.php';

        // Vérifier si le fichier existe, puis le charger
        if (is_file($classPath)) {
            require_once $classPath;
        }
    }

    public function register() {
        spl_autoload_register([$this, 'loadClass']);
    }
}
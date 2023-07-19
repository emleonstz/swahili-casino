<?php 
namespace Emleons\Games;
class Envs {
    function getEnv()
    {
        $currentDirectory = __DIR__;
        $parentDirectory = dirname($currentDirectory);
        $filePath = $parentDirectory . '/.env';
        $variables = array();

        // Read the contents of the .env file
        $envVariables = file_get_contents($filePath);

        // Parse the variables into an associative array
        $lines = explode("\n", $envVariables);
        foreach ($lines as $line) {
            $line = trim($line);

            // Ignore comments and empty lines
            if (!empty($line) && strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                list($key, $value) = explode('=', $line, 2);
                $variables[$key] = $value;
            }
        }

        return $variables;
    }


}

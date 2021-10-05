<?php

/**
 * Zip file is used to zip and extract the folders. The main usage of this file is in back-recovery.php file
 *
 * @package Template
 */
 
 class ExtendedZip extends ZipArchive {
    // Member function to add a whole file system subtree to the archive
    public function addTree($dirname, $localname = '') {
        $this->addEmptyDir($localname);
        $this->_addTree($dirname, $localname);
        //$this->_addTree($dirname, $localname);
    }
    protected  function _addTree($dirname, $directory) {
        $dir = opendir($dirname);
        while ($filename = readdir($dir)) {
            // Discard . and ..
            if ($filename == '.' || $filename == '..')
                continue;
            // Proceed according to type
            $path = $dirname . '/' . $filename;
            $localpath = $directory ? ($directory . '/' . $filename) : $filename;
            if (is_dir($path)) {
                // Directory: add & recurse
                $this->addEmptyDir($localpath);
                $this->_addTree($path, $localpath);
            }
            else if (is_file($path)) {
                // File: just add
                $this->addFile($path, $localpath);
            }
        }
        closedir($dir);
    }
   
    // Helper function
    public static function zipTree($dirname, $zipFilename, $flags = 0, $localname = '') {
        $zip = new self();
        $zip->open($zipFilename, $flags);
        $zip->addTree($dirname, $localname);
       // $zip->zip_extract('C:/xampp/htdocs/wordpress/wp.zip','C:');
        $zip->close();
    }
}

?>
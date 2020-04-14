<?php


namespace OticTools\Mw;


use OticTools\Core\AbstractOticMiddleware;
use Phore\FileSystem\PhoreTempDir;

class UnzipMiddleware extends AbstractOticMiddleware
{


    /**
     *
     * @param array|null $data
     * @return mixed
     */
    public function message(array $data)
    {
        if ( ! isset($data["file_in"]))
            throw new \InvalidArgumentException("Expected array element 'file_in'");

        $inFile = $origInFile = phore_file($data["file_in"]);

        if ($data["file_in"] == "php://input") {
            $inFile = phore_tempfile();
            $origInFile->streamCopyTo($inFile);
        }


        $tmpDir = new PhoreTempDir();

        phore_exec("unzip -d :outdir :infile", ["outdir" => $tmpDir, "infile" => $inFile]);


        $this->next->message(["file_in"=>(string)$tmpDir]);
        $tmpDir->rmDir(true);
    }
}

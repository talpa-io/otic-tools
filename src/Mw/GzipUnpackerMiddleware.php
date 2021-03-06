<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 12.03.20
 * Time: 19:26
 */

namespace OticTools\Mw;


use OticTools\Core\AbstractOticMiddleware;

class GzipUnpackerMiddleware extends AbstractOticMiddleware
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


        $tmp = phore_tempfile();
        $tmpWriter = $tmp->fopen("w+");
        $inFileStream = $inFile->gzopen("r");
        
        while ( ! $inFileStream->feof()) {
            $tmpWriter->fwrite($inFileStream->fread(8012));
        }
        $tmpWriter->fclose();
        $inFileStream->fclose();
        
        $this->stats->statsVal("gzipunpacker.size.uncompressed", $tmp->fileSize());
        $this->next->message(["file_in"=>(string)$tmp]);
    }
}

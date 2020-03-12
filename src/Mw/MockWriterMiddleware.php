<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 12.03.20
 * Time: 18:00
 */

namespace OticTools\Mw;


use OticTools\Core\AbstractOticMiddleware;

class MockWriterMiddleware extends AbstractOticMiddleware
{

    public $data = [];
    
    public function message(array $data) 
    {
        $this->data[] = $data;
        if ($this->next !== null)
            $this->next->message($data);
    }
    
    
    public function getCount()
    {
        return $this->data;
    }
    
}

<?php
namespace Components;
class Retorno
{
    public $ok;
    public $data;
    public $error;

    function __construct($ok,$data,$error)
    {
        $this->ok = $ok;
        $this->data = $data;
        $this->$error = $error;
    }
}
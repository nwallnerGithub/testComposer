<?php

namespace classes\SHARED\Mailing;


use PHPMailer\PHPMailer\PHPMailer;

class EmailRaw extends PHPMailer
{
    public function __construct($exceptions = null)
    {
        parent::__construct($exceptions);

        $this->isSMTP();
        $this->Host = 'HOST';
        $this->Port = 587;
        $this->SMTPAuth = true;
        $this->Username = 'USERNAME';
        $this->Password = 'PASSWORD';
        $this->isHTML(true);
        $this->CharSet = 'UTF-8';
    }

}
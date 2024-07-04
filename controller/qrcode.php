<?php

require_once('../plugins/phpqrcode/qrlib.php');

class QRgenerator
{
    private $ruta;
    private $type;
    private $id;

    public function __construct($id, $type)
    {
        $this->id = $id;

        if ($type == "user") {
            $this->ruta = '../view/img/users/qr-' . $this->id . '.png';
        } else {
            $this->ruta = '../view/img/devices/qr-' . $this->id . '.png';
        }
    }

    public function createQR()
    {
        QRcode::png($this->id, $this->ruta, 'H', 40, 2);
    }
}
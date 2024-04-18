<?php
// ImgKit por Cristian David <-> cristiandavid.com.co

// Instancia para usar ImgProcessor:
//$imgProc = new ImgProcessor($IMAGEN, $NOMBREIMAGEN, $RUTA, "CONDICION");
//$imgProc->processImage();
//$respuesta = $imgProc->getResponse();

class ImgProcessor
{
    private $imgOrigin;
    private $imgOriginName;
    private $imgOriginType;
    private $imgSrc;
    private $nWidth;
    private $nHeight;
    private $resp = "";
    private $respKey;

    public function __construct($imgOrigin, $imgOriginName, $imgSrc, $type)
    {
        $this->imgOrigin = $imgOrigin;
        $this->imgOriginName = $imgOriginName;
        $this->imgOriginType = strtolower(pathinfo($this->imgOriginName, PATHINFO_EXTENSION));
        $this->imgSrc = $imgSrc;

        if ($type == "user") {
            $this->nWidth = 300;
            $this->nHeight = 300;
        } else {
            $this->nWidth = 800;
            $this->nHeight = 800;
        }
    }

    public function processImage()
    {
        if ($this->imgOriginType == "jpg") {
            $this->processJpg();
        } elseif ($this->imgOriginType == "png") {
            $this->processPng();
        } else {
            $this->resp = "Formato no permitido";
            $this->respKey = false;
        }
    }

    private function processJpg()
    {
        $imgOriginBckp = imagecreatefromjpeg($this->imgOrigin);
        $oWidth = imagesx($imgOriginBckp);
        $oHeight = imagesy($imgOriginBckp);

        if ($oWidth <= $this->nWidth && $oHeight <= $this->nHeight) {
            imagejpeg($imgOriginBckp, "." . $this->imgSrc, 50);
            $this->resp = "Diseño agregado correctamente";
            $this->respKey = true;
        } else {
            $scale = min($this->nWidth / $oWidth, $this->nHeight / $oHeight);
            $newWidth = round($oWidth * $scale);
            $newHeight = round($oHeight * $scale);

            $tmp = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($tmp, $imgOriginBckp, 0, 0, 0, 0, $newWidth, $newHeight, $oWidth, $oHeight);

            imagejpeg($tmp, "." . $this->imgSrc, 50);
            imagedestroy($tmp);

            $this->resp = "Diseño agregado correctamente";
            $this->respKey = true;
        }
    }

    private function processPng()
    {
        $imgOriginBckp = imagecreatefrompng($this->imgOrigin);
        $oWidth = imagesx($imgOriginBckp);
        $oHeight = imagesy($imgOriginBckp);

        if ($oWidth <= $this->nWidth && $oHeight <= $this->nHeight) {
            imagepng($imgOriginBckp, "." . $this->imgSrc, 9);
            $this->resp = "Diseño agregado correctamente";
            $this->respKey = true;
        } else {
            $scale = min($this->nWidth / $oWidth, $this->nHeight / $oHeight);
            $newWidth = round($oWidth * $scale);
            $newHeight = round($oHeight * $scale);

            $tmp = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($tmp, $imgOriginBckp, 0, 0, 0, 0, $newWidth, $newHeight, $oWidth, $oHeight);

            imagepng($tmp, "." . $this->imgSrc, 9);
            imagedestroy($tmp);

            $this->resp = "Diseño agregado correctamente";
            $this->respKey = true;
        }
    }

    public function getResponse()
    {
        return array('resp' => $this->resp, 'respKey' => $this->respKey);
    }
}

// ImgKit por Cristian David <-> cristiandavid.com.co

// Instancia para usar ImgRenamer:
// $imgRen = new ImgRenamer($RUTA);
// $nuevoNombre = $imgRen->processSrc();
// $respuesta = $imgRen->getResponse();

class ImgRenamer
{
    private $imgSrc;
    private $imgName;
    private $resp = "";
    private $respKey;

    public function __construct($imgSrc)
    {
        $this->imgSrc = $imgSrc;
    }

    public function processSrc()
    {
        if (file_exists("." . $this->imgSrc)) {
            $this->imgName = basename($this->imgSrc);
            return $this->processImgSrc();
        } else {
            $this->resp = "La imagen no existe";
            $this->respKey = false;
        }
    }

    private function processImgSrc()
    {
        preg_match('/(\d+)-(\d+)\.(jpg|png)/', $this->imgName, $matches);
        $prefix = $matches[1];
        $currNum = intval($matches[2]);
        $exten = $matches[3];

        $newNum = $currNum + 1;
        $newName = $prefix . '-' . str_pad($newNum, 2, '0', STR_PAD_LEFT) . '.' . $exten;

        $this->resp = "Nombre creado correctamente";
        $this->respKey = true;

        return $newName;
    }

    public function getResponse()
    {
        return array('resp' => $this->resp, 'respKey' => $this->respKey);
    }
}
?>
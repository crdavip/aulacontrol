<?php
class AddAlert
{
    private $transmitter;
    private $group;
    private $icon;
    private $description;
    private $link;

    public function __construct($transmitter, $group, $icon, $description, $link)
    {
        $this->transmitter = $transmitter;
        $this->group = $group;
        $this->icon = $icon;
        $this->description = $description;
        $this->link = $link;
    }

    public function insertAlert()
    {
        $connPDO = new ConnPDO();
        $pdo = $connPDO->getConn();

        $sqlGroup = "SELECT u.USUARIO_ID
                    FROM usuario AS u
                    INNER JOIN rol AS r ON r.ROL_ID = u.ROL
                    WHERE u.ROL = ?";
        $stmtGroup = $pdo->prepare($sqlGroup);
        $stmtGroup->execute([$this->group]);
        $groupRows = $stmtGroup->fetchAll(PDO::FETCH_ASSOC);

        foreach ($groupRows as $groupRow) {
            $receiver = $groupRow['USUARIO_ID'];
            $sql = "INSERT INTO alertas (EMISOR, GRUPO, RECEPTOR, ICONO, DESCRIPCION, LINK, ESTADO, FECHA) VALUES (?,?,?,?,?,?,0,current_timestamp())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$this->transmitter, $this->group, $receiver, $this->icon, $this->description, $this->link]);
        }
    }
}
?>
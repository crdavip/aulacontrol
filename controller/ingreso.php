<?php
session_start();
require('../model/db.php');

$connPDO = new ConnPDO;
$pdo = $connPDO->getConn();

if (!isset($_SERVER['HTTP_REFERER'])) {
  header("Location: ../");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $data = json_decode(file_get_contents("php://input"), true);

  if (isset($data['doc'])) {
    $doc = $data['doc'];
    $pass = $data['pass'];

    try {
      $sql = "SELECT u.idUsuario, u.documento, u.contrasena, u.estado, u.nuevo, ud.*, ce.detalle AS centro, c.idCargo, c.detalle AS cargo
      FROM usuario AS u
      INNER JOIN usuario_detalle AS ud ON u.idUsuario = ud.idUsuario
      INNER JOIN centro AS ce ON ce.idCentro = ud.idCentro
      INNER JOIN cargo AS c ON u.idCargo = c.idCargo
      WHERE documento=?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$doc]);

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($row) {
        $passBd = $row['contrasena'];
        $passEncode = sha1($pass);
        $status = $row['estado'];

        if ($passBd == $passEncode) {
          if ($status == "Activo") {
            $_SESSION['userId'] = $row['idUsuario'];
            $_SESSION['document'] = $row['documento'];
            $_SESSION['firstTime'] = $row['nuevo'];
            $_SESSION['email'] = $row['correo'];
            $_SESSION['name'] = $row['nombre'];
            $_SESSION['idRole'] = $row['idCargo'];
            $_SESSION['role'] = $row['cargo'];
            $_SESSION['img'] = $row['imagen'];
            $_SESSION['imgQr'] = $row['imagenQr'];
            $_SESSION['status'] = $row['estado'];
            $_SESSION['birth'] = $row['nacimiento'];
            $_SESSION['center'] = $row['centro'];

            echo json_encode(['successUser' => true, 'image' => $row['imagen']]);
          } else {
            echo json_encode(['successUser' => false, 'message' => '¡Usuario Desactivado!']);
          }
        } else {
          echo json_encode(['successPass' => false, 'message' => '¡Contraseña Incorrecta!']);
        }
      } else {
        echo json_encode(['successUser' => false, 'message' => '¡Usuario Incorrecto!']);
      }
    } catch (PDOException $e) {
      error_log("PDO Exception: " . $e->getMessage(), 0);
      echo "Se produjo un error, inténtelo nuevamente más tarde.";
    }
  } elseif (isset($data['lostPassDoc']) && $data['lostPassDoc'] !== "") {

    $doc = $data['lostPassDoc'];
    $sql = "SELECT u.idUsuario, u.estado, ud.correo, ud.nombre
            FROM usuario AS u
            INNER JOIN usuario_detalle AS ud ON u.idUsuario = ud.idUsuario
            WHERE u.documento=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$doc]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
      $id = $row['idUsuario'];
      $email = $row['correo'];
      $name = $row['nombre'];
      $status = $row['estado'];

      if ($status == "Activo") {

        require('../model/mailer.php');
        $mailer = new Mailer();

        $token = md5(uniqid(mt_rand(), false));
        $sql = "UPDATE usuario SET token=?, olvideContra=1 WHERE idUsuario=?";
        $stmt = $pdo->prepare($sql);
        $stmt = $stmt->execute([$token, $id]);

        $url = 'http://localhost/ucloth/resetpass?id=' . $id . '&token=' . $token;
        $subject = 'Recupera tu contraseña';
        $content            = "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700;900&display=swap' rel='stylesheet'>
            <style>
                .container-f {width: 100%;font-family: 'Poppins', sans-serif !important;background-color: #04bf9d!important;background-image: url('https://cristiandavid.com.co/ucloth/bg_pattern.png');background-repeat: repeat-x;background-size: 25%;padding: 30px 0;text-align: center;}
                .container-c {max-width: 340px;width: 90%;margin: 0 auto;padding: 20px;background-color: #ffffff;border-radius: 10px;text-align: center;margin-bottom: 10px;}
                h2 {font-size: 24px;font-weight: 500;color: #04bf9d;border-bottom: 2px solid #04bf9d;}
                p {font-size: 16px;line-height: 20px;color: #17181c;margin-bottom: 10px;}
                .btn {display: inline-block;padding: 10px 20px;background-color: #0388a6;color: #ffffff!important;text-decoration: none;border-radius: 5px;}
                .btn:hover {background-color: #346377;}
                .note {font-size: 12px;line-height: 14px;color: #346377;}
                span {font-size: 12px;color: #ffffff;}
                span a {font-size: 12px;font-weight: 700;text-decoration: none;color: #ffffff!important;}
            </style>
        </head>
        <body>
            <div class='container-f'>
                <div class='container-c'>
                    <div>
                        <img src='https://cristiandavid.com.co/ucloth/logo_full.png' alt='logo' style='width: 124px; margin: 0 auto;'>
                    </div>
                    <h2>¡Hola, $name!</h2>
                    <p>Parece que has olvidado tu contraseña. Si es así, haz clic en el siguiente botón para restablecer tu contraseña:</p>
                    <a href='$url' class='btn'>Cambiar Contraseña</a>
                    <p class='note'>Si no has solicitado este cambio, simplemente ignora este correo electrónico.</p>
                </div>
                <span>Copyright © 2024 <a href='https://cristiandavid.com.co'>Cristian David.</a> Todos los derechos reservados.</span>
            </div>
        </body>
        </html>
                    ";

        if ($mailer->sendEmail($email, $subject, $content)) {
          echo json_encode(['successUser' => true, 'message' => '¡Enlace enviado con exito a <span>' . $email . '</span>!']);
        } else {
          echo json_encode(['successUser' => false, 'message' => 'Error al enviar el correo.']);
        }
      } else {
        echo json_encode(['successUser' => false, 'message' => '¡Usuario Desactivado!']);
      }
    } else {
      echo json_encode(['successUser' => false, 'message' => '¡Usuario Incorrecto!']);
    }
  }
}

$connPDO->closeConn();
?>
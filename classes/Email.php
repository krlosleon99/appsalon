<?php

    namespace Classes;

    use PHPMailer\PHPMailer\PHPMailer;

    class Email {

        public $nombre;
        public $email;
        public $token;

        public function __construct($nombre, $email, $token)
        {
            $this->nombre = $nombre;
            $this->email = $email;
            $this->token = $token;
        }

        public function enviarConfirmacion() {
            $mail = new PHPMailer();

            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = 'a08d9f608d6435';
            $mail->Password = '18eece3ba29ceb';

            $mail->setFrom('cuentas@appsalon.com');
            $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Confirma tu cuenta';

            $contenido = "<html>";
            $contenido .= "<p>Hola, <strong>" . $this->nombre . "</strong></p>";
            $contenido .= "<p>Para poder continuar con tu registro, confirma tu email mediante el siguiente enlace:</p>";
            $contenido .= "<p>Confirmar cuenta: <a href='http://localhost:3000/confirmar-cuenta?token=" . $this->token . "'>Confirmar cuenta</a></p>";
            $contenido .= "Si no fuiste tú, puedes ignorar el mensaje";

            $mail->Body = $contenido;

            $mail->send();
        }

        public function enviarInstrucciones() {
            $mail = new PHPMailer();

            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = 'a08d9f608d6435';
            $mail->Password = '18eece3ba29ceb';

            $mail->setFrom('cuentas@appsalon.com');
            $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Reestablece tu password';

            $contenido = "<html>";
            $contenido .= "<p>Hola, <strong>" . $this->nombre . "</strong></p>";
            $contenido .= "<p>Para poder reestablecer tu contraseña, entra al siguiente enlace:</p>";
            $contenido .= "<p>Reestablecer contraseña: <a href='http://localhost:3000/recuperar?token=" . $this->token . "'>Presiona aquí</a></p>";
            $contenido .= "Si no fuiste tú, puedes ignorar el mensaje";

            $mail->Body = $contenido;

            $mail->send();
        }
    }

?>
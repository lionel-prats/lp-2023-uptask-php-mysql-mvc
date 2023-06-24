<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    protected $email;
    protected $nombre;
    protected $token;
    public function __construct($email, $nombre, $token){
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }    
    public function enviarConfirmacion() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV["SERVER_EMAIL_HOST"];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV["SERVER_EMAIL_PORT"];
        $mail->Username = $_ENV["SERVER_EMAIL_USER"];
        $mail->Password = $_ENV["SERVER_EMAIL_PASS"];

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = 'confirma tu cuenta';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $nombre = $this->nombre;
        $token = $this->token;
        $contenido = "
            <html>
                <p><strong>Hola $nombre</strong>. Has creado tu cuenta en UpTask, solo debes confirmarla en el siguiente enlace.</p>
                <p>Presiona aquí: <a href='http://localhost:3000/confirmar?token=$token'>Confirmar</a></p>
                <p>Si tu no creaste esta cuenta, puedes ignorar este mensaje.</p>
            </html>
        ";
        $mail->Body = $contenido;
        $mail->send();
    }
}

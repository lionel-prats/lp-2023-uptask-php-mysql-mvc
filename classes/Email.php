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
        
        $contenido = "
            <html>
                <p><strong>Hola $this->nombre</strong>. Has creado tu cuenta en UpTask, solo debes confirmarla en el siguiente enlace.</p>
                <p>Presiona <a href='http://localhost:3000/confirmar?email=$this->email&token=$this->token'>aquí</a> para confirmar.</p>
                <p>Si tu no creaste esta cuenta, puedes ignorar este mensaje.</p>
            </html>
        ";
        $mail->Body = $contenido;
        $mail->send();
    }
    public function enviarInstrucciones() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV["SERVER_EMAIL_HOST"];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV["SERVER_EMAIL_PORT"];
        $mail->Username = $_ENV["SERVER_EMAIL_USER"];
        $mail->Password = $_ENV["SERVER_EMAIL_PASS"];

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = 'Reestablece tu password';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $email = $this->email;
        $nombre = $this->nombre;
        $token = $this->token;
        $contenido = "
            <html>
                <p><strong>Hola $this->nombre</strong>. Parece que has olvidado tu contraseña.</p>
                <p>Presiona <a href='http://localhost:3000/reestablecer?email=$email&token=$token'>aquí</a> para generar una nueva.</p>
                <p>Si tu no solicitaste este cambio, puedes ignorar este mensaje.</p>
            </html>
        ";
        $mail->Body = $contenido;
        $mail->send();
    }
}

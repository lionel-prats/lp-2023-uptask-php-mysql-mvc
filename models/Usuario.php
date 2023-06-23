<?php

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','apellido','email','password','telefono','admin','confirmado','token'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $token;
    public $confirmado;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? '0';
    }

    // validador formulario de creacion de cuenta
    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas["error"][] = "El nombre es obligatorio"; 
        }
        if(!$this->email) {
            self::$alertas["error"][] = "El email es obligatorio";
        } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas["error"][] = "El email ingresado no es válido";
        }
        /* if(!$this->password || strlen($this->password) < 6) {
            self::$alertas["error"][] = "El password es obligatorio y debe contener al menos 6 caracteres";
        } */
        return self::$alertas;
    }

}
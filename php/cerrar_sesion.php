<?php
    require "config.php";

    $objeto_cerrar_sesion = new CerrarSesion(DIRECCION,USUARIO,CONTRASEÑA,BD);
    $token = $_POST["token"];

    $objeto_cerrar_sesion->modificar_estado_sesion($token);

    class CerrarSesion{
        private $direccion;
        private $usuario;
        private $contraseña;
        private $bd;

        public function __construct($direccion,$usuario,$contraseña,$bd){
            $this->direccion = $direccion;
            $this->usuario = $usuario;
            $this->contraseña = $contraseña;
            $this->bd= $bd;
        }

        public function modificar_estado_sesion($token){
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $consulta = "UPDATE sesiones SET estado = 1 WHERE token = '$token' AND estado = 0";
            $conexion->query($consulta);
        }

    }

?>
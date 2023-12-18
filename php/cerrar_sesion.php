<?php
    require "config.php";

    // Creo objeto y recojo las variables necesarias.
    $objeto_cerrar_sesion = new CerrarSesion(DIRECCION,USUARIO,CONTRASEÑA,BD);
    $token = $_POST["token"];

    // Lógica del PHP.
    $objeto_cerrar_sesion->modificar_estado_sesion($token);

    // Defino la clase.
    class CerrarSesion{

        // Variables de Clase.
        private $direccion;
        private $usuario;
        private $contraseña;
        private $bd;

        // Defino Constructor.
        public function __construct($direccion,$usuario,$contraseña,$bd){
            $this->direccion = $direccion;
            $this->usuario = $usuario;
            $this->contraseña = $contraseña;
            $this->bd= $bd;
        }

        // Funcion que modifica el estado de la sesion a 1.
        public function modificar_estado_sesion($token){
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $consulta = "UPDATE sesiones SET estado = 1 WHERE token = '$token' AND estado = 0";
            $conexion->query($consulta);
        }
    }

?>
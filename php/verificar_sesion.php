<?php
    require "config.php";

    // Creo objeto y recojo las variables necesarias.
    $objeto_sesion = new Sesion(DIRECCION,USUARIO,CONTRASEÑA,BD);
    $token = $_POST["token"];

    // Lógica del PHP.
    if ($objeto_sesion->verificar_sesion($token)){
        $objeto_sesion->extender_sesion($token);
        $array = ["status" => "OK"];
    }else{
        $objeto_sesion->modificar_estado_sesion($token);
        $array = ["status" => "ERROR"];
    }

    // Devuelvo el JSON con lo que corresponda.
    echo json_encode($array);

    // Defino la clase.
    class Sesion{

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

        // Función que verifica si una sesión está caducada o no.
        public function verificar_sesion($token){
            $resultado = false;
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $consulta = "SELECT fecha_final FROM sesiones WHERE token = '$token' AND estado = 0";
            $resultset = $conexion->query($consulta);
            if ($resultset->num_rows > 0){
                $fila_sesiones = $resultset->fetch_assoc();
                $fecha_sistema_segundos = strtotime(date("Y/m/d H:i:s"));
                $fecha_final_segundos = strtotime($fila_sesiones["fecha_final"]);
                if ($fecha_sistema_segundos < $fecha_final_segundos){
                    $resultado = true;
                }
            }
            return $resultado;
        }

        // Función que extiende una sesión si la sesión no está caducada.
        public function extender_sesion($token){
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $fecha_final = date("Y/m/d H:i:s",strtotime(date("Y/m/d H:i:s"))+3600);
            $consulta = "UPDATE sesiones SET fecha_final = '$fecha_final' WHERE token = '$token' AND estado = 0";
            $conexion->query($consulta);
        }

        // Función que modifica en 1 el estado de una sesión.
        public function modificar_estado_sesion($token){
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $consulta = "UPDATE sesiones SET estado = 1 WHERE token = '$token' AND estado = 0";
            $conexion->query($consulta);
        }

    }
    
?>
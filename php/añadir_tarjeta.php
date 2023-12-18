<?php
    require "config.php";

    // Creo objeto y recojo las variables necesarias.
    $objeto_añadir_tarjeta = new AñadirTarjeta(DIRECCION,USUARIO,CONTRASEÑA,BD);
    $token = $_POST["token"];
    $numero_tarjeta = $_POST["numero_tarjeta"];
    $titular = $_POST["titular"];
    $fecha_caducidad = $_POST["fecha_caducidad"];
    $cvv = $_POST["cvv"];
    $numero_tarjeta_formateada = $_POST["numero_tarjeta_formateada"];

    // Lógica del PHP.
    $id_usuario = $objeto_añadir_tarjeta->rescatar_id_usuario($token);

    if ($objeto_añadir_tarjeta->comprobar_tarjeta_alta($id_usuario,$numero_tarjeta,$titular,$fecha_caducidad,$cvv)){
        if ($objeto_añadir_tarjeta->comprobar_tarjeta_mis_tarjetas($id_usuario,$numero_tarjeta,$titular,$fecha_caducidad,$cvv)){
            $array = ["status" => "ERROR2"];
        }else{
            $objeto_añadir_tarjeta->insertar_tarjeta_mis_tarjetas($id_usuario,$numero_tarjeta,$titular,$fecha_caducidad,$cvv,$numero_tarjeta_formateada);
            $array = ["status" => "OK"];
        }
    }else{
        $array = ["status" => "ERROR1"];
    }

    // Devuelvo el JSON con lo que corresponda.
    echo json_encode($array);

    // Defino la clase.
    class AñadirTarjeta{

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

        // Funcion que comprueba si existe una tarjeta en la tabla tarjetas_alta.
        public function comprobar_tarjeta_alta($id_usuario,$numero_tarjeta,$titular,$fecha_caducidad,$cvv){
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $resultado = false;
            $consulta = "SELECT * FROM tarjetas_alta WHERE id_usuarios = $id_usuario AND numero_tarjeta = '$numero_tarjeta' AND titular = BINARY '$titular' AND fecha_caducidad = '$fecha_caducidad' AND cvv = $cvv";
            $resultset = $conexion->query($consulta);
            if ($resultset->num_rows > 0){
                $resultado = true;
            }
            return $resultado;
        }

        // Funcion que comprueba si existe una tarjeta en la tabla mis_tarjetas.
        public function comprobar_tarjeta_mis_tarjetas($id_usuario,$numero_tarjeta,$titular,$fecha_caducidad,$cvv){
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $resultado = false;
            $consulta = "SELECT * FROM mis_tarjetas WHERE id_usuarios = $id_usuario AND numero_tarjeta = '$numero_tarjeta' AND titular = BINARY '$titular' AND fecha_caducidad = '$fecha_caducidad' AND cvv = $cvv";
            $resultset = $conexion->query($consulta);
            if ($resultset->num_rows > 0){
                $resultado = true;
            }
            return $resultado;
        }

        // Función que inserta una tarjeta en la tabla mis_tarjetas.
        public function insertar_tarjeta_mis_tarjetas($id_usuario,$numero_tarjeta,$titular,$fecha_caducidad,$cvv,$numero_tarjeta_formateada){
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $consulta = "INSERT INTO mis_tarjetas(id_usuarios,numero_tarjeta,titular,fecha_caducidad,cvv,numero_tarjeta_formateada) VALUES ('$id_usuario','$numero_tarjeta','$titular','$fecha_caducidad','$cvv','$numero_tarjeta_formateada')";
            $conexion->query($consulta);
        }

        // Función que rescata el id de usuario para usarlo en otras funciones.
        public function rescatar_id_usuario($token){
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $consulta = "SELECT id_usuarios FROM sesiones WHERE token = '$token'";
            $resultset = $conexion->query($consulta);
            $fila = $resultset->fetch_assoc();
            $id_usuario = $fila["id_usuarios"];
            return $id_usuario;
        }
        
    }

?>
<?php
    require "config.php";

    $objeto_actualizar_usuario = new ActualizarUsuario(DIRECCION,USUARIO,CONTRASEÑA,BD);
    $token = $_POST["token"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $dni = $_POST["dni"];
    $contraseña = $_POST["contraseña"];

    $id_usuario = $objeto_actualizar_usuario->rescatar_id_usuario($token);
    if ($objeto_actualizar_usuario->actualizar_usuario($nombre,$apellido,$dni,$contraseña,$id_usuario)){
        $array = ["status" => "OK"];
    }else{
        $array = ["status" => "ERROR"];
    }

    echo json_encode($array);

    class ActualizarUsuario{
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

        public function actualizar_usuario($nombre,$apellido,$dni,$contraseña,$id_usuario){
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $resultado = false;
            $consulta = "UPDATE usuarios SET nombre = '$nombre', apellido = '$apellido', dni = '$dni', contraseña = '$contraseña' WHERE id = $id_usuario";
            $resultset = $conexion->query($consulta);
            $numero_filas_afectadas = $conexion->affected_rows;
            if ($resultset == TRUE){
                $resultado = true;
            }
            return $resultado;
        }

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
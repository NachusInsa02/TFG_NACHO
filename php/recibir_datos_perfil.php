<?php
    require "config.php";

    // Creo objeto y recojo las variables necesarias.
    $objeto_datos_perfil = new DatosPerfil(DIRECCION,USUARIO,CONTRASEÑA,BD);
    $token = $_POST["token"];

    // Lógica del PHP.
    $id_usuario = $objeto_datos_perfil->rescatar_id_usuario($token);
    $array_datos_usuario = $objeto_datos_perfil->recibir_datos_perfil($id_usuario);

    // Devuelvo el JSON con lo que corresponda.
    echo json_encode($array_datos_usuario);

    // Defino la clase.
    class DatosPerfil{

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

        // Función que recibe los datos de un usuario.
        public function recibir_datos_perfil($id){
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $consulta = "SELECT * FROM usuarios WHERE id = $id";
            $resultset = $conexion->query($consulta);
            $fila = $resultset->fetch_assoc();
            $array_datos_usuario = ["nombre" => $fila["nombre"],"apellido" => $fila["apellido"],"dni" => $fila["dni"]];
            return $array_datos_usuario;
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
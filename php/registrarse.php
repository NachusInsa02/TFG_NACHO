<?php
    require "config.php";

    // Creo objeto y recojo las variables necesarias.
    $objeto_registrarse = new Registrarse(DIRECCION,USUARIO,CONTRASEÑA,BD);
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $dni = $_POST["dni"];
    $email = $_POST["email"];
    $contraseña = $_POST["password"];

    // Lógica del PHP.
    if ($objeto_registrarse->comprobar_usuario($email) !== true){
        $objeto_registrarse->insertar_usuario($nombre,$apellidos,$dni,$email,$contraseña);
        $array = ['status' => "OK"];
    }else{
        $array = ['status' => "ERROR"];
    }
    
    // Devuelvo el JSON con lo que corresponda.
    echo json_encode($array);

    // Defino la clase.
    class Registrarse{

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

        // Funcion que comprueba si existe un usuario con cierto email.
        public function comprobar_usuario($email){
            $resultado = false;
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $consulta = "SELECT * FROM usuarios WHERE email = '$email'";
            $resultset = $conexion->query($consulta);
            if ($resultset->num_rows > 0){
                $resultado = true;
            }
            return $resultado;
        }

        // Función que inserta un nuevo usuario.
        public function insertar_usuario($nombre,$apellidos,$dni,$email,$contraseña){
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $contraseña_encriptada = md5($contraseña);
            $consulta_usuario = "INSERT INTO usuarios(nombre,apellido,dni,email,contraseña) VALUES ('$nombre','$apellidos','$dni','$email','$contraseña_encriptada')";
            $conexion->query($consulta_usuario);
        }

    }
    
?>
<?php
    require "config.php";

    // Creo objeto y recojo las variables necesarias.
    $objeto_login = new Login(DIRECCION,USUARIO,CONTRASEÑA,BD);
    $email = $_POST["email"];
    $contraseña = $_POST["password"];

    // Lógica del PHP.
    if ($objeto_login->comprobar_usuario($email,$contraseña)){
        $token = $objeto_login->generar_token();
        $objeto_login->insertar_sesion($email,$contraseña,$token);
        $array = ['status' => "OK",'token' => $token];
    }else{
        $array = ['status' => "ERROR"];
    }
    
    // Devuelvo el JSON con lo que corresponda.
    echo json_encode($array);

    // Defino la clase.
    class Login{

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

        // Función que comprueba si existe un usuario en la BD.
        public function comprobar_usuario($email,$contraseña){
            $resultado = false;
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $contraseña_encriptada = md5($contraseña);
            $consulta = "SELECT * FROM usuarios WHERE email = '$email' AND contraseña = BINARY '$contraseña_encriptada'";
            $resultset = $conexion->query($consulta);
            if ($resultset->num_rows > 0){
                $resultado = true;
            }
            return $resultado;
        }

        // Función que inserta una sesión para un usuario en específico.
        public function insertar_sesion($email,$contraseña,$token){
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $contraseña_encriptada = md5($contraseña);
            $consulta_usuarios = "SELECT id FROM usuarios WHERE email = '$email' AND contraseña = BINARY '$contraseña_encriptada'";
            $resultset_usuarios = $conexion->query($consulta_usuarios);
            $fila_usuarios = $resultset_usuarios->fetch_assoc();
            $id_usuario = $fila_usuarios["id"];
            $fecha_actual = date("Y/m/d H:i:s");
            $fecha_final = date("Y/m/d H:i:s",strtotime($fecha_actual)+3600);
            $consulta_sesiones = "INSERT INTO sesiones(id_usuarios,fecha_inicial,fecha_final,token) VALUES ('$id_usuario','$fecha_actual','$fecha_final','$token')";
            $conexion->query($consulta_sesiones);
        }

        // Función que genera un token aleatorio.
        public function generar_token($length = 32){
            return bin2hex(random_bytes($length));
        }
        
    }
?>
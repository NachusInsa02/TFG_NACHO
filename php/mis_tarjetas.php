<?php
    require "config.php";

    // Creo objeto y recojo las variables necesarias.
    $objeto_mostrar_tarjetas = new MostrarTarjetas(DIRECCION,USUARIO,CONTRASEÑA,BD);
    $token = $_POST["token"];

    // Lógica del PHP.
    $id_usuario = $objeto_mostrar_tarjetas->rescatar_id_usuario($token);
    $array_tarjetas = $objeto_mostrar_tarjetas->rescatar_tarjetas($id_usuario);

    // Devuelvo el JSON con lo que corresponda.
    echo json_encode($array_tarjetas);

    // Defino la clase.
    class MostrarTarjetas{

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

        // Funcion que recoge las tarjetas de un usuario en la tabla mis_tarjetas.
        public function rescatar_tarjetas($id_usuario){
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $array_tarjetas = [];
            $contador = 0;
            $consulta = "SELECT titular,fecha_caducidad,cvv,numero_tarjeta_formateada FROM mis_tarjetas WHERE id_usuarios = $id_usuario";
            $resultset = $conexion->query($consulta);
            while ($fila = $resultset->fetch_assoc()){
                $array_tarjetas[$contador] = ["titular" => $fila["titular"],"fecha_caducidad" => $fila["fecha_caducidad"],"cvv" => $fila["cvv"],"numero_tarjeta_formateada" => $fila["numero_tarjeta_formateada"]];
                $contador++;
            }
            return $array_tarjetas;
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
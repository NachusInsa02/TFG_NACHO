<?php
    require "config.php";

    // Creo objeto y recojo las variables necesarias.
    $objeto_mostrar_movimientos = new MostrarMovimientos(DIRECCION,USUARIO,CONTRASEÑA,BD);
    $token = $_POST["token"];
    $id_tarjeta = $_POST["id_tarjeta"];

    // Lógica del PHP.
    $id_usuario = $objeto_mostrar_movimientos->rescatar_id_usuario($token);
    $array_movimientos = $objeto_mostrar_movimientos->rescatar_movimientos($id_usuario,$id_tarjeta);

    // Devuelvo el JSON con lo que corresponda.
    echo json_encode($array_movimientos);

    // Defino la clase.
    class MostrarMovimientos{

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

        // Función que rescata el id de usuario para usarlo en otras funciones.
        public function rescatar_id_usuario($token){
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $consulta = "SELECT id_usuarios FROM sesiones WHERE token = '$token'";
            $resultset = $conexion->query($consulta);
            $fila = $resultset->fetch_assoc();
            $id_usuario = $fila["id_usuarios"];
            return $id_usuario;
        }

        // Función que rescata los movimientos de una tarjeta específica y usuario específico.
        public function rescatar_movimientos($id_usuario,$id_tarjeta){
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $array_movimientos = [];
            $contador = 0;
            $consulta = "SELECT * FROM movimientos WHERE id_usuarios = $id_usuario AND id_tarjeta = $id_tarjeta";
            $resultset = $conexion->query($consulta);
            while ($fila = $resultset->fetch_assoc()){
                $array_movimientos[$contador] = ["fecha" => $fila["fecha"],"descripcion" => $fila["descripcion"],"transaccion" => $fila["transaccion"],"importe" => $fila["importe"]];
                $contador++;
            }
            return $array_movimientos;
        }

    }
    
?>
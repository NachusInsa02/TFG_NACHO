<?php
    require "config.php";   

    $objeto_tarjetas_movimientos = new TarjetasMovimientos(DIRECCION,USUARIO,CONTRASEÑA,BD);
    $token = $_POST["token"];

    $id_usuario = $objeto_tarjetas_movimientos->rescatar_id_usuario($token);
    $array_numero_tarjetas = $objeto_tarjetas_movimientos->mostrar_tarjetas_select($id_usuario);

    echo json_encode($array_numero_tarjetas);

    class TarjetasMovimientos{
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

        public function rescatar_id_usuario($token){
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $consulta = "SELECT id_usuarios FROM sesiones WHERE token = '$token'";
            $resultset = $conexion->query($consulta);
            $fila = $resultset->fetch_assoc();
            $id_usuario = $fila["id_usuarios"];
            return $id_usuario;
        }

        public function mostrar_tarjetas_select($id_usuario){
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $array = [];
            $contador = 0;
            $consulta = "SELECT id,numero_tarjeta_formateada FROM mis_tarjetas WHERE id_usuarios = $id_usuario";
            $resultset = $conexion->query($consulta);
            while ($fila = $resultset->fetch_assoc()){
                $array[$contador] = ["id" =>$fila["id"],"numero_tarjeta_formateada" => $fila["numero_tarjeta_formateada"]];
                $contador++;
            }
            return $array;
        }
    }
?>
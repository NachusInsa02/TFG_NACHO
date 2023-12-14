<?php
    require "config.php";

    $objeto_mostrar_movimientos = new MostrarMovimientos(DIRECCION,USUARIO,CONTRASEÑA,BD);
    $token = $_POST["token"];
    $id_tarjeta = $_POST["id_tarjeta"];

    $id_usuario = $objeto_mostrar_movimientos->rescatar_id_usuario($token);
    $array_movimientos = $objeto_mostrar_movimientos->rescatar_movimientos($id_usuario,$id_tarjeta);

    echo json_encode($array_movimientos);

    class MostrarMovimientos{
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
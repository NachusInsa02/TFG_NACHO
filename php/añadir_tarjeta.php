<?php
    require "config.php";

    $objeto_añadir_tarjeta = new AñadirTarjeta(DIRECCION,USUARIO,CONTRASEÑA,BD);
    

    class AñadirTarjeta{
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

        public function comprobar_tarjeta_alta($id_usuario,$numero_tarjeta,$titular,$fecha_caducidad,$cvv){
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $resultado = false;
            $consulta = "SELECT * FROM tarjetas_alta WHERE id_usuarios = $id_usuario AND numero_tarjeta = '$numero_tarjeta' AND titular = '$titular' AND fecha_caducidad = '$fecha_caducidad' AND cvv = $cvv";
            $resultset = $conexion->query($consulta);
            if ($resultset->num_rows > 0){
                $resultado = true;
            }
            return $resultado;
        }

        public function comprobar_tarjeta_mis_tarjetas($id_usuario,$numero_tarjeta,$titular,$fecha_caducidad,$cvv){
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $resultado = false;
            $consulta = "SELECT * FROM mis_tarjetas WHERE id_usuarios = $id_usuario AND numero_tarjeta = '$numero_tarjeta' AND titular = '$titular' AND fecha_caducidad = '$fecha_caducidad' AND cvv = $cvv";
            $resultset = $conexion->query($consulta);
            if ($resultset->num_rows > 0){
                $resultado = true;
            }
            return $resultado;
        }

        public function insertar_tarjeta_mis_tarjetas($id_usuario,$numero_tarjeta,$titular,$fecha_caducidad,$cvv){
            @ $conexion = new mysqli($this->direccion,$this->usuario,$this->contraseña,$this->bd);
            $consulta = "INSERT INTO mis_tarjetas(id_usuarios,numero_tarjeta,titular,fecha_caducidad,cvv) VALUES ('$id_usuario','$numero_tarjeta','$titular','$fecha_caducidad','$cvv')";
            $conexion->query($consulta);
        }
        
    }

?>
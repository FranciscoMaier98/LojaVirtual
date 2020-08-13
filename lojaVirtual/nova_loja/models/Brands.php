<?php
    class Brands extends model{

        public function getList() {
            $array = array();
            
            $sql = "SELECT * FROM brands";
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0) {
                $array = $sql->fetchAll();
            }

            return $array;
        }

        public function getElementById($id){
            $data = array(); //dados

            $sql = "SELECT name FROM BRANDS WHERE id=:id";
            
            $sql = $this->db->prepare($sql);
            $sql->bindValue(":id", $id);
            $sql->execute();

            if($sql->rowCount() > 0) {
                $data = $sql->fetch();
                return $data['name'];
            } else {
                return '';
            }


        }

    }

?>
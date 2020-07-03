<?php
    class Brands extends model{

        public function getElementById($id){
            $dados = array();

            $sql = "SELECT name FROM BRANDS WHERE id=:id";
            $sql = $this->query->prepare();
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
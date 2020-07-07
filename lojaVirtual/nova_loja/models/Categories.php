<?php

    class Categories extends model {

        public function getlist() {

            $array = array();

            $sql = "SELECT * FROM categories ORDER BY sub DESC"; // Está pegando a tabela na ordem contrária, para poder saber primeiro qual são os subs do menus antes de saber sobre os menus pais
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0) {
                foreach($sql->fetchAll() as $item) {
                    $item['subs'] = array();
                    $array[$item['id']] = $item;
                }

                while($this->stillNeed($array)){
                    $this->organizeCategory($array);
                }
            }

            //echo '<pre>';
            //print_r($array);
            //exit;
            
            return $array;


        }

        public function getCategoryTree($id) {
            $array = array();

            $haveChild = true;

            while($haveChild) {
                $sql = "SELECT * FROM categories WHERE  id = :id";
                $sql = $this->db->prepare($sql);
                $sql->bindValue(":id", $id);
                $sql->execute();

                if($sql->rowCount() > 0) {
                    $sql = $sql->fetch();
                    $array[] = $sql;

                    if(!empty($sql['sub'])){
                        $id = $sql['sub'];
                    } else {
                        $haveChild = false;
                    }
                }
            }

            $array = array_reverse($array); // Inverte o array

            return $array;

        }


        public function getCategoryName($id) {
            $sql = "SELECT name FROM categories WHERE id = :id";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(":id", $id);
            $sql->execute();

            if($sql->rowCount() > 0){
                $sql = $sql->fetch();
                return $sql['name'];
            } else {
                return '';
            }

        }




        //Funções privadas
        private function organizeCategory(&$array) { //& Indica q o mesmo valor enviado para essa função terá seus valores alterados tmbm
            foreach($array as $id => $item) { // $id é o valor q indica a chave do array e o $item são os valores dentro do array
                if(isset($array[$item['sub']])) {
                    $array[$item['sub']]['subs'][$item['id']] = $item;// $array[id do pai][Array da posição][id dentro do array] // atribuindo array para um valor no array, organizando-o
                    unset($array[$id]); //Apagando o valor que foi atribuído ao array
                    break; // Depois que encontrou o id pai do valor sub, deve-se quebrar o looping, pois um dos arrays acaba sendo excluído, o q pode causar problemas dentro do looping, resultando em um erro 
                }

            }
        }

        private function stillNeed($array) {
            foreach($array as $item) {
                if(!empty($item['sub'])) {
                    return true;
                }
            }
            return false;
        }

    }

?>
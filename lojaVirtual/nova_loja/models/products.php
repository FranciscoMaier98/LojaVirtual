<?php

    class products extends model {

        public function getList($offset = 0, $limit = 3){
            
            $array = array();

            //Primeira forma de fazer, utilizando subquerys. Esse modo é utilizando quando as tabelas q serão trabalhadas com
            //subquerys são pequenas, que chamam apenas um item por vez:

            //Selecione tudo E o nome da tabela brand onde o id 
            //da tabela brand é igual ao id_brand da tabela products. Também ocorre o mesmo com o nome da tabela categorias,
            //onde o id da tabela categorias deve ser igual ao id_category de products

            $sql = "SELECT *, (select brands.name from brands where brands.id = products.id_brand)
            as brand_name, 
            (select categories.name from categories where categories.id = products.id_category) as category_name 
            FROM products LIMIT $offset, $limit"; 

            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0) {
                $array = $sql->fetchAll();

                foreach($array as $key => $item) {
                    $array[$key]['images'] = $this->getImagesByProductId($item['id']); //Pode haver mais de uma imagem registrada no nome.
                }

            }
            return $array;
            

            //Segunda forma de fazer, separando as querys e subquerys. Utilizado quando as querys são muito grandes,
            //que podem chamar mais de um item por vez:
            /*
            $sql = "SELECT * FROM products";
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0) {
                $array = $sql->fetchAll();
                $brands = new Brands();
                
                foreach($array as $key => $item) {
                    $array[$key]['brand_name'] = $brands->getElementById(
                        $item['id_brand']
                    );
                }

            }

            return $array;
            */
        }

        public function getTotal() {
            $sql = "SELECT COUNT(*) as c FROM products";
            $sql = $this->db->query($sql);
            $sql = $sql->fetch();

            return $sql['c'];
        }


        public function getImagesByProductId($id) {

            $array = array();

            $sql = "SELECT url FROM products_images WHERE id_product = :id";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(":id",$id);
            $sql->execute();

            if($sql->rowCount() > 0) {
                $array = $sql->fetchAll();
            }

            return $array;
        }

    }

?>
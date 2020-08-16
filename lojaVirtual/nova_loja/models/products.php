<?php

    class Products extends model {

        public function getList($offset = 0, $limit = 3, $filters = array(),$random=false){
            
            $array = array();

            //Primeira forma de fazer, utilizando subquerys. Esse modo é utilizando quando as tabelas q serão trabalhadas com
            //subquerys são pequenas, que chamam apenas um item por vez:

            //Selecione tudo E o nome da tabela brand onde o id 
            //da tabela brand é igual ao id_brand da tabela products. Também ocorre o mesmo com o nome da tabela categorias,
            //onde o id da tabela categorias deve ser igual ao id_category de products

            $orderBySQL = "";
            if ($random == true) {
                $orderBySQL = "ORDER BY RAND()";
            }

            if (!empty($filters['toprated'])) {
                $orderBySQL = "ORDER BY rating DESC";
            }

            $where = $this->buildWhere($filters);

            $sql = "SELECT *, (select brands.name from brands where brands.id = products.id_brand)
            as brand_name, 
            (select categories.name from categories where categories.id = products.id_category) as category_name 
            FROM products
            WHERE ".implode(' AND ', $where)."
            ".$orderBySQL."
            LIMIT $offset, $limit"; 

            $sql = $this->db->prepare($sql);

            $this->bindWhere($filters, $sql); 

            $sql->execute();

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


        

        public function getTotal($filters = array()) {

            $where = $this->buildWhere($filters);

            $sql = "SELECT COUNT(*) as c FROM products WHERE ".implode(' AND ', $where);
            
            $sql = $this->db->prepare($sql);

            $this->bindWhere($filters, $sql); 

            $sql->execute();

            if($sql->rowCount() > 0) {
                $sql = $sql->fetch();
            }
            
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


        public function getAvailableOptions($filters = array()) {
            $groups = array();
            $ids = array();

            $where = $this->buildWhere($filters);

            $sql = "SELECT id, options 
            FROM products
            WHERE ".implode(' AND ', $where);
            
            //echo $sql;
            //echo "<br><br>";

            $sql = $this->db->prepare($sql);

            $this->bindWhere($filters, $sql); 

            $sql->execute();

            if($sql->rowCount() > 0) {
                foreach($sql->fetchAll() as $product){
                    $ops = explode(',', $product['options']);
                    $ids[] = $product['id'];
                    foreach($ops as $op) {
                        if(!in_array($op, $groups)) {
                            $groups[] = $op;
                        }
                    }
                }
            }


            //print_r($groups);
            //echo "<br><br>";

            $options = $this->getAvailableValuesFromOptions($groups, $ids);

            //print_r($options);


            return $options;

        }


        public function getSaleCount($filters = array()) {
            
            
            $where = $this->buildWhere($filters);

            $where[] = 'sale="1"';

            /*$sql = "SELECT sale, COUNT(id) as c FROM products
            WHERE ".implode(' AND ', $where)." GROUP BY sale"; //implode verifica cada valor no array e adiciona AND. Dessa forma, pode haver mais de um WHERE (um filtro) no sql
            */

            $sql = "SELECT COUNT(*) as c FROM products WHERE ".implode(' AND ', $where);
            
            $sql = $this->db->prepare($sql);

            $this->bindWhere($filters, $sql);   
        
            $sql->execute();

            if($sql->rowCount() > 0 ) {
                $sql = $sql->fetch();

                return $sql['c'];
            } 

            return $sql;
        }

        public function getListOfStars($filters = array()) {
            $array = array();
            
            $where = $this->buildWhere($filters);

            $sql = "SELECT rating, COUNT(id) as c FROM products
            WHERE ".implode(' AND ', $where)." GROUP BY rating";
            
            $sql = $this->db->prepare($sql);

            $this->bindWhere($filters, $sql);   
        
            $sql->execute();

            if($sql->rowCount() > 0 ) {
                $array = $sql->fetchAll();
            } else {
                $array = " ";
            }          


            return $array;
        }

        public function getListOfBrands($filters = array()) {

            $array = array();
            
            $where = $this->buildWhere($filters);

            $sql = "SELECT id_brand, COUNT(id) as c FROM products
            WHERE ".implode(' AND ', $where)." GROUP BY id_brand";
            
            $sql = $this->db->prepare($sql);

            $this->bindWhere($filters, $sql);   
        
            $sql->execute();

            if($sql->rowCount() > 0 ) {
                $array = $sql->fetchAll();
            } else {
                $array = " ";
            }          

            
           
            
            return $array;

            


        }


        public function getMaxPrice($filters = array()) {

            //$where = $this->buildWhere($filters);

            $sql = "SELECT 
            price 
            FROM products
            ORDER BY price DESC 
            LIMIT 1";
            
            //WHERE ".implode(' AND ', $where)."

            $sql = $this->db->prepare($sql);

            //$this->bindWhere($filters, $sql);   
        
            $sql->execute();

            if($sql->rowCount() > 0 ) {
                $sql = $sql->fetch();

                return $sql['price'];
            } else {
                return '0';
            } 
        }


        public function getAvailableValuesFromOptions($groups, $ids) {
            $array = array();
            $options = new Options();
            foreach($groups as $op) {
                $array[$op] = array(
                    'name' => $options->getName($op),
                    'options' => array()
                );
            }

            $sql = "SELECT
            p_value,
            id_option,
            COUNT(id_option) as c
            FROM products_options
            WHERE
            id_option IN ('".implode("','", $groups)."') AND
            id_product
             IN  ('".implode("','", $ids)."')
            GROUP BY p_value ORDER BY id_option";

            //echo $sql;

            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0) {
                
                foreach($sql->fetchAll() as $ops) {
                    $array[$ops['id_option']]['options'][] = 
                    array(
                        'id' => $ops['id_option'],
                        'value'=>$ops['p_value'], 
                        'count'=>$ops['c']
                    );
                }
            }

            return $array;
        }


        private function buildWhere($filters) {
            
            $where = array(
                "1=1"
            );

            if(!empty($filters['category'])) {//Verifica se a informação vinda do array está preenchida
                $where[] = "id_category = :id_category"; //Se tiver, o array é sobreescrito com o valor que :id_category irá assumir
            }

            if(!empty($filters['brand'])) {
                $where[] = "id_brand IN ('".implode("','", $filters['brand'])."')";
            }

            if(!empty($filters['star'])) {
                $where[] = "rating IN ('".implode("','", $filters['star'])."')";
            }

            if(!empty($filters['sale'])) {
                $where[] = "sale = '1'";
            }

            if(!empty($filters['featured'])) {
                $where[] = "featured = '1'";
            }

            if(!empty($filters['options'])) {
                $where[] = "id IN (select id_product from products_options where products_options.p_value IN 
                ('".implode("','", $filters['options'])."'))";
            }

            if(!empty($filters['slider0'])) {
                $where[] = "price >= :slider0";
            }

            if(!empty($filters['slider1'])) {
                $where[] = "price <= :slider1";
            }


            if(!empty($filters['searchTerm'])) {
                $where[] = "name LIKE :searchTerm";
            }

            return $where;
        }


        private function bindWhere($filters , &$sql) {

            if(!empty($filters['category'])) { //Verifica se a informação vinda do array está preenchida
                $sql->bindValue(':id_category', $filters['category']); //Assume o valor recebido pelo $filters
            }

            if(!empty($filters['slider0'])) { //Verifica se a informação vinda do array está preenchida
                $sql->bindValue(':slider0', $filters['slider0']); //Assume o valor recebido pelo $filters
            }

            if(!empty($filters['slider1'])) { //Verifica se a informação vinda do array está preenchida
                $sql->bindValue(':slider1', $filters['slider1']); //Assume o valor recebido pelo $filters
            }

            if(!empty($filters['searchTerm'])) {
                $sql->bindValue(':searchTerm', '%'.$filters['searchTerm'].'%');
            }

        }


        

    }

?>
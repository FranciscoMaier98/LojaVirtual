<?php
    class Filters extends model {

        public function getFilters($filters = array()) {
            
            $brands = new Brands();
            $products = new Products();
            
            $array = array(
                'searchTerm' => "",
                'brands' => array(),
                'slider0' => 0,
                'slider1' => 0,
                'maxslider' => 1000,
                'stars' => array(
                    '0' => 0,
                    '1' => 0,
                    '2' => 0,
                    '3' => 0,
                    '4' => 0,
                    '5' => 0
                ),
                'sale' => 0,
                'options' => array()
            );

            if(isset($filters['searchTerm'])) { //Verifica se o usuário realizou uma busca na barra de buscas
                $array['searchTerm'] = $filters['searchTerm'];
            }
            
            $array['brands'] = $brands->getList();
            $brands_products = $products->getListOfBrands($filters); //Retorna a lista do id_brand de cada produto cadastrado

            foreach($array['brands'] as $bkey => $bitem) {
                $array['brands'][$bkey]['count'] = '0';
                foreach($brands_products as $bproduct) {
                    if($bproduct['id_brand'] == $bitem['id']) { //Compara o id_brand da marca e do produto
                        $array['brands'][$bkey]['count'] = $bproduct['c'];
                    }
                }
                if($array['brands'][$bkey]['count'] == '0'){
                    unset($array['brands'][$bkey]);
                }
            }

            /*foreach($array['brands'] as $bkey => $bitem) {
                


            }*/

            //Criando filtro de preço
            if(isset($filters['slider0'])) { // verifica se o valor do preço mínimo foi modificados
                $array['slider0'] = $filters['slider0'];
            }

            if(isset($filters['slider1'])) { // verifica se o valor do preço máximo foi modificados
                $array['slider1'] = $filters['slider1'];
            }

            $array['maxslider'] = $products->getMaxPrice($filters);

            if($array['slider1'] == 0) {
                $array['slider1'] = $array['maxslider'];
            }

            // Criando listras de estrelas
            $star_products = $products->getListOfStars($filters);
            foreach($array['stars'] as $skey => $sitem) {
                
                foreach($star_products as $sproduct) {
                
                    if($sproduct['rating'] == $skey){
                         
                        $array['stars'][$skey] = $sproduct['c'];
                        
                    }
                    
                }
            }

        
            //Criando filtro de promoções
            $array['sale'] = $products->getSaleCount($filters);


            //Criando filtro das opções
            $array['options'] = $products->getAvailableOptions($filters);


            /*$sql = "SELECT * FROM brands";
            $this->db->query($sql);

            if($sql->rowCount() > 0) {
                $sql = $sql->fetchAll();
                
            }*/

            return $array;
        } 

    }

?>
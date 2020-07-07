<?php
    class categoriesController extends controller {  //Essa classe tem como principal função auxiliar na filtragem na página inicial

        public function __construct() {
            parent::__construct(); 
        }

        public function index() {
            header("Location: ".BASE_URL);
        }

        public function enter($id) {
            $dados = array();
            $products = new Products();
            $categories = new Categories();

            $dados['category_name'] = $categories->getCategoryName($id);

            if(!empty($dados['category_name'])) { //Verifica se há um nome retornado. Caso não haja, ele redireciona para a página principal sem nenhum dado. Caso alguém tentar inserir um valor que não exista para na URL, não haverá erros
                $dados['categories'] = $categories->getList();
                
                $currentPage = 1;
                $offset = 0; //a posição do valor que será o inicial a ser pego da tabela
                $limit = 3; // aposição do último valor a ser pego da tabela
        
                if(!empty($_GET['p'])) { //verifica se o item $p não está vazio
                    $currentPage = $_GET['p'];
                }
        
                $offset = ($currentPage * $limit) - $limit; // define o offset da página
                $dados['list'] = $products->getList($offset, $limit);

                //Ajudam na paginação do site
                $dados['totalItems'] = $products->getTotal();
                $dados['numberOfPages'] = ceil($dados['totalItems']/$limit);
                $dados['currentPage'] = $currentPage;

                $dados['category_filter'] = $categories->getCategoryTree($id);
            } else {
                header("Location: ".BASE_URL);
            }
            
            $this->loadTemplate('categories', $dados);

        }

    }
?>
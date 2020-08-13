<?php
class buscaController extends controller {

	private $user;

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $dados = array();
        $products = new Products();
        $categories = new Categories();
        $f = new Filters();

        if(!empty($_GET['s'])) {
            $searchTerm = $_GET['s'];
            $category = $_GET['category'];
            
            $filters = array();
            if(!empty($_GET['filter']) && is_array($_GET['filter'])) {
                $filters = $_GET['filter'];
            }
            
            $filters['searchTerm'] = $searchTerm;
            $filters['category'] = $category;
            
            $currentPage = 1;
            $offset = 0; //a posição do valor que será o inicial a ser pego da tabela
            $limit = 3; // aposição do último valor a ser pego da tabela

            if(!empty($_GET['p'])) { //verifica se o item $p não está vazio
                $currentPage = $_GET['p'];
            }

            $offset = ($currentPage * $limit) - $limit; // define o offset da página

            $dados['list'] = $products->getList($offset, $limit, $filters);
            $dados['totalItems'] = $products->getTotal($filters);
            $dados['numberOfPages'] = ceil($dados['totalItems']/$limit);
            $dados['currentPage'] = $currentPage;

            $dados['categories'] = $categories->getList();

            $dados["filters"] = $f->getFilters($filters);
            $dados['filters_selected'] = $filters;

            $dados['searchTerm'] = $searchTerm;
            $dados['category'] = $category;

            $this->loadTemplate('busca', $dados);
        } else {
            header("Location: ".BASE_URL);
        }
    }
}
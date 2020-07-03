<?php
class homeController extends controller {

	private $user;

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $dados = array();
        $products = new Products();
        $categories = new Categories();


        $currentPage = 1;
        $offset = 0; //a posição do valor que será o inicial a ser pego da tabela
        $limit = 3; // aposição do último valor a ser pego da tabela

        if(!empty($_GET['p'])) { //verifica se o item $p não está vazio
            $currentPage = $_GET['p'];
        }

        $offset = ($currentPage * $limit) - $limit; // define o offset da página

        $dados['list'] = $products->getList($offset, $limit);
        $dados['totalItems'] = $products->getTotal();
        $dados['numberOfPages'] = ceil($dados['totalItems']/$limit);
        $dados['currentPage'] = $currentPage;

        $dados['categories'] = $categories->getList();

        $this->loadTemplate('home', $dados);
    }

}
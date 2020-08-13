<?php
    class Language{
    
        private $l;
        private $ini;

        public function __construct() {
            global $config;

            $this->l = $config['default_lang'];

            if(!empty($_SESSION['lang']) && file_exists('lang/'.$_SESSION['lang'].'.ini')) {
                
                $this->l = $_SESSION['lang'];
            }

            $this->ini = parse_ini_file('lang/'.$this->l.'.ini'); // Transforma em um array o arquivo ini

        }

        public function get($word, $return=false) { // Mana a palavra e se irá ter retorno ou não
            $text = $word;

            if(isset($this->ini[$word])) {
                $text = $this->ini[$word];
            }

            if($return) { //Verifica se tem retorno
                return $text; // retorna o texto
            } else {
                echo $text; // exibe o texto
            }

        }
    
    }   
?>
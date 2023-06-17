<?php
/**
 * VerificaPOSTForm() Verifica se os dados forem enviados por um sibmit de um formulário
 * @param = Nenhum
 * @return = String Hash.
 * @author Charles Corrêa <charlescorreaweb@gmail.com>
 * @version 1.0
 * @copyright Copyright © 2018, Charles Corrêa - Soluções Web & SMS Marketing.
 */
function VerificaPOSTForm()
{
    if (getenv('REQUEST_METHOD') != 'POST') {
        echo "<script>alert('Sua requisição não veio por um POST, verifique e tente novamente'); </script>";
    }

    //var_dump($_POST);

    return;
}


/* getGet
		* Verfica o se existe valor 
		
		* @param $campo var, valor na URL
		
	*/
	
	function getGet($Dados) {
		
		return isset($_GET["$Dados"]) ? $_GET["$Dados"] : '';
	}
	
	/**
		getPost
		* 
		* Verfica o se existe valor 
		
		* @param $Input var, name do input a ser verificado
		
	*/
	function getPost($Input) {
		
		return isset($_POST["$Input"]) ? $_POST["$Input"] : '';
	}
	
	/**
		RetornaValor
		* 
		* Verfica o se existe valor 
		
		* @param $campo var
		
	*/
	function RetornaValor($Valor) {
		
		$Valor = isset($Valor) ? $Valor : null;
		
		return $Valor;
	}

?>
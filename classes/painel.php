
<?php

class Painel
{

	public static function logado()
	{
		return isset($_SESSION['login']) ? true : false;
	}

	public static function loggout()
	{
		session_destroy();
		header('Location: ' . INCLUDE_PATH_PAINEL);
	}

	public static function loadPage()
	{
		if (isset($_GET['url'])) {
			$url = explode('/', $_GET['url']);
			if (file_exists('pages/' . $url[0] . '.php')) {
				include('pages/' . $url[0] . '.php');
			} else {
				//pagina nao existe
				header('Location:' . INCLUDE_PATH_PAINEL);
			}
		} else {
			include '../todd/pages/home.php';
		}
	}


	public static function imageValid($imagem)
	{
		if (
			$imagem['type'] == 'image/jpeg' ||
			$imagem['type'] == 'image/jpg' ||
			$imagem['type'] == 'image/png'
		) {
			$tamanho = intval($imagem['size'] / 1024);
			if ($tamanho < 7200)
				return true;
			else
				return false;
		} else {
			return false;
		}
	}

	public static function uploadFile($file)
	{
		$formatoArquivo = explode('.', $file['name']);
		$imagemNome = uniqid() . '.' . $formatoArquivo[count($formatoArquivo) - 1];
		if (move_uploaded_file($file['tmp_name'], BASE_DIR_PAINEL . 'uploads/' . $imagemNome))
			return $imagemNome;
		else
			return false;
	}

	/**public static function deleteFile($file){
			@unlink('uploads/.$file');
		}**/






	/** FUNÇÕES ESPECÍFICAS */

	public static function counter($table, $query)
	{

		$counter = server::connect()->prepare("SELECT COUNT(id) AS value FROM $table $query; ");
		$counter->execute();
		$counter = $counter->fetchColumn();

		return $counter;
	}

	public static function roleVerify($role)
	{
		if ($role != 1) {
			die('Você não tem permissão para acessar esse recurso.');
		}
	}
}









?>
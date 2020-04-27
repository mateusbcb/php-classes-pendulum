<?php

    namespace Pendulum\Model;

    use \Pendulum\DB\Sql;
    use \Pendulum\Model;

    class Favoritos extends Model{

        const SESSION = "User";
        const ERROR = "FavoritosError";
        const SUCCESS = "FavoritosSucesss";

        public static function getFromSession()
        {

            $user = new User();

            if (isset($_SESSION[User::SESSION]) && (int)$_SESSION[User::SESSION]['iduser'] > 0) {

                $user->setData($_SESSION[User::SESSION]);

            }

            return $user;

        }

        public function save()
        {

            $sql = new Sql();

            $results = $sql->select("CALL sp_favoritos_save(:idfavoritos, :idproduct, :iduser)", [
                ':idfavoritos'=>$this->getidfavoritos(),
                ':idproduct'=>$this->getidproduct(),
                ':iduser'=>$this->getiduser()
            ]);
    
            if (count($results) > 0) {
                $this->setData($results[0]);
            }
    
        }

        public function get($idfavoritos)
	{

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_favoritos WHERE idfavoritos = :idfavoritos", [
			':idfavoritos'=>$idfavoritos
		]);

		$this->setData($results[0]);

	}

	public function delete($idproduct , $iduser)
	{

		$sql = new Sql();

		$sql->query("DELETE FROM tb_favoritos WHERE idproduct = :idproduct AND iduser = :iduser", [
            ':idproduct'=>$idproduct,
            ':iduser'=>$iduser
		]);

    }

    public function listAll(){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_favoritos");

        return $results[0];
    }
    
    public function favoritosExiste($idproduct , $iduser)
	{

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_favoritos WHERE idproduct = :idproduct AND iduser = :iduser", [
            ':idproduct'=>$idproduct,
            ':iduser'=>$iduser
		]);
        
        return $results;
	}


        public static function setError($msg)
        {

            $_SESSION[Favoritos::ERROR] = $msg;

        }

        public static function getError()
        {

            $msg = (isset($_SESSION[Favoritos::ERROR]) && $_SESSION[Favoritos::ERROR]) ? $_SESSION[Favoritos::ERROR] : '';

            Favoritos::clearError();

            return $msg;

        }

        public static function clearError()
        {

            $_SESSION[Favoritos::ERROR] = NULL;

        }

        public static function setSuccess($msg)
        {

            $_SESSION[Favoritos::SUCCESS] = $msg;

        }

        public static function getSuccess()
        {

            $msg = (isset($_SESSION[Favoritos::SUCCESS]) && $_SESSION[Favoritos::SUCCESS]) ? $_SESSION[Favoritos::SUCCESS] : '';

            Favoritos::clearSuccess();

            return $msg;

        }

        public static function clearSuccess()
        {

            $_SESSION[Favoritos::SUCCESS] = NULL;

        }
    }

?>
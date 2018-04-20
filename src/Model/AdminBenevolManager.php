<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace Model;

/**
 *
 */
class AdminBenevolManager extends AbstractManager
{
    const TABLE = 'AdminBenevol';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
     /**
     * INSERT one row in dataase
     *
     * @param Array $data
     */
     public function benevolContentUpdate(array $data)
     {
        var_dump($data);
        var_dump($_POST);
        $errors = [];
        $updatePhoto = false;
        $updateArticle = false;
        $mimeTypeAllow = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
        $dirUpload = 'assets/DBimages/';

        if(!empty($_POST)){ 
            $post = array_map('trim', array_map('strip_tags', $_POST));  

            if(is_uploaded_file($_FILES['photo']['tmp_name']) && file_exists($_FILES['photo']['tmp_name'])){ 
                $finfo = new \finfo();
                $mimeType = $finfo->file($_FILES['photo']['tmp_name'], FILEINFO_MIME_TYPE); 

                if(in_array($mimeType, $mimeTypeAllow)){ 
                    $photoName = uniqid('photo_'); 
                    $photoName.= '.'.pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);

                    if(!is_dir($dirUpload)){
                        mkdir($dirUpload, 0755);
                    }

                    if(!move_uploaded_file($_FILES['photo']['tmp_name'], $dirUpload.$photoName)){
                        $errors[] = 'Erreur lors de l\'envoi de votre photo';
                    }
                }
                else{
                    $errors[] = 'Le type de fichier est invalide. Uniquement jpg/gif/png.'; 
                }

                $updatePhoto = true;
            }   

            if(count($errors) === 0){


                $update = $this::$pdoConnection->prepare("UPDATE '$this->table' SET article = :article, photo = :photo WHERE id= 1");

                $update->bindValue(':article', $data['article']);
                $update->bindValue(':photo', $data['photo']);

                if($updateArticle){
                    $update->bindValue(':article', $data['article']);
                }

                if($updatePhoto){
                    $update->bindValue(':photo', $dirUpload.$photoName);
                }

                if($update->execute()){

                    $formValid = true;
                }


                else {
                    var_dump($update->errorInfo());
                }
            }

        }
    }
}
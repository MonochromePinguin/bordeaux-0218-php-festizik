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
        $errors = [];
        $updatePhoto = false;
        $updateArticle = false;
        $mimeTypeAllow = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
        $dirUpload = 'uploads/';

    if(!empty($_POST)){ 
    $post = array_map('trim', array_map('strip_tags', $_POST));     

    if(count($errors) === 0){


        $columnSQL = 'article = :article, photo = :photo';

        if($updateArticle){    

            $columnSQL.= ', article = :article'; 
        }

        if($updatePhoto){ 

            $columnSQL.= ', photo = :photo';

        }

        

        $update = $bdd->prepare('UPDATE AdminBenevol SET '.$columnSQL.'');
        
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
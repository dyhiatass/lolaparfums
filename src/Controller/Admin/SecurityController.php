<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SecurityController extends AbstractController{

  public  function secusql($donnee){
        $regex1 = "SELECT";
        $regex2 = "INSERT";
        $regex3 = "DROP";
        $regex4 = "DELETE";
        $regex5 = "UPDATE";
        if(preg_match("/$regex1|$regex2|$regex3|$regex4|$regex5/i",$donnee)){
            $donnee = str_ireplace("$regex1", "s-elect", $donnee);
            $donnee = str_ireplace("$regex2", "i_nser_t", $donnee);
            $donnee = str_ireplace("$regex3", "d-r-o-p", $donnee);
            $donnee = str_ireplace("$regex4", "d-e-l-e-te", $donnee);
            $donnee = str_ireplace("$regex5", "up-date", $donnee);
        }
        return $donnee;
    }
    public  function secujs($donnee){
        $regex1 = "<";
        $regex2 = ">";
        $regex3 = "&lt;";
        $regex4 = "&gt;";
        $regex5 = "script";
        if(preg_match("/$regex1|$regex2|$regex3|$regex4|$regex5/i",$donnee)){
            $donnee = str_ireplace("$regex1", "", $donnee);
            $donnee = str_ireplace("$regex2", "", $donnee);
            $donnee = str_ireplace("$regex3", "", $donnee);
            $donnee = str_ireplace("$regex4", "", $donnee);
            $donnee = str_ireplace("$regex5", "scr_ip_t", $donnee);
        }
        return $donnee;
    }

    public  function secuhtml($donnee){
        $donnee = htmlspecialchars($donnee);
        $donnee = trim($donnee);
        $donnee = stripslashes($donnee);
        return $donnee;
    }
    public function secu($donnee)
    {
        $donnee = $this->secusql($donnee);
        $donnee = $this->secujs($donnee);
        $donnee = $this->secuhtml($donnee);
        return $donnee;
    }
    function verifyMail($mail){
        if(filter_var($mail, FILTER_VALIDATE_EMAIL)){
            return 1;
        }else{
            return 0;    } }
    
    function verifyMdp($mdp){
        if(preg_match("/^(?=.*?[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[#?!@$%^&_-]).{12,}$/", $mdp)){
            return 1;
        }else{
            return 0;
        }}

        
    function verifyText($text){
        if(preg_match("/^[a-zA-Z -'éàäâèîïêûöüç]+$/u", $text)){
            return 1;
        }else{
            return 0;
        }
    }
    
    
    function verifyQuantity($quantity) {
        if (preg_match("/^[0-9]+$/", $quantity)) {
            return 1; 
        } else {
            return 0;
        }
}
function verifyPrix($prix) {
    if (preg_match("/^\d+(\.\d{2})?$/", $prix)) {
        return 1;
    } else {
        return 0;
    }
}
function verifyPourcentage($pourcentage) {
    if (preg_match("/^\d+(\.\d+)?$/", $pourcentage)) {
        return 1;
    } else {
        return 0;
    }
}
}
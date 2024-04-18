<?php
// require 'class/Gestionnaire.php';
require 'class' . DIRECTORY_SEPARATOR . 'Gestionnaire.php';

//recuperation de page dans l'URL :
if(isset($_REQUEST['page'])){
    //Verifier si la la variable page n'est pas vide
    if($_REQUEST['page'] != ""){
        $page = $_REQUEST['page'];
        //Si la virable page est differant de ce qui est autoriser
        if($page != "connexion" && $page != "inscription" && $page != "motsDePasseOublier"){
            $page = "connexion";
        }
    }else{//si la virable page est vide
        $page = "connexion";
    }
}else{//si la virable page n'existe pas
    $page = "connexion";
}


//Connexion au compte :
if( isset($_POST['login']) && isset($_POST['mdp']) ){
    $gst = new Gestionnaire;
    $connexion = $gst->getConnexion($_POST['login'], $_POST['mdp']);//recuperation de la connexion
    // var_dump($connexion);


    if(!$connexion["connexion"]){//si l'utilisateur n'existe pas
        echo "<p>Identifient ou mots de passe incorrect</p>";
    }else{//si l'utilisateur existe
        session_start();
        $_SESSION["compte"] = $connexion;
        if($connexion["user"]->getUser_statut() == "admin"){//Pour utilisateur avec un acces administrateur
            //header('Location: public/admin/index.php');
            ?>
            <script>window.location.replace("public/admin/index.php");</script>
            <?php
        }else if($connexion["user"]->getUser_statut() == "client"){//Pour utilisateur avec un acces au compte normal
            //header('Location: public/client/index.php');
            ?>
            <script>window.location.replace("public/client/index.php");</script>
            <?php
        }
        // var_dump($connexion);
    }
}


//inscription :
if(isset($_POST["id"]) && isset($_POST["email"]) && isset($_POST["mdp1"]) && isset($_POST["mdp2"]) ){
    if($_POST["mdp1"] == $_POST["mdp2"]){
        
        $gst = new Gestionnaire;
        $connexion = $gst->getConnexion($_POST['id'], $_POST['mdp1']);

        if(!$connexion["connexion"]){//Verifier si le compte n'existe pas deja
            if(strlen($_POST["mdp1"]) > 10){//S'assuerer que le mots de passe contien bien 10 caracteres au minimum
                $gst->inscriptionNewClient($_POST['id'], $_POST['mdp1'], $_POST["email"]);//Fonction d'inscription
                echo '<script>window.alert("Votre compte est créer, connectez vous");</script>';
                echo '<script>window.location.replace("?page=connexion");</script>';//Redirection sur connection
            }else{
                echo "Votre mots de passe doit contenir au minimum 10 caractères";
            }
        }else{
            echo "Vous etes déjà inscrit, veuillez vous connecter";
        }
    }else{
        echo "Mots de passe pas identique";
    }
}


?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Facture</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        
    <?php
    if($page == "connexion"){//Partie connexion pour
        ?>
        <div class="container page_acceuil">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="shadow-lg p-3 mb-5 bg-body rounded">
                        <h4>Connectez vous pour retrouvez vos factures</h4><br>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Identifiant</label>
                                <input name="login"  type="text" class="form-control" placeholder="Entrez votre identifiant" autocomplete="off" Required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mots de passe</label>
                                <input name="mdp" type="password" class="form-control" placeholder="Entrez votre mots de passe" autocomplete="off" Required>
                                <p class="text-"><small>Mots de passe oublié ? <a href="?page=motsDePasseOublier" style="color:#566573">Cliquez ici</a></small></p>
                            </div>
                            <div class="d-grid gap-2 bouton_connexion">
                                <button type="submit" class="btn btn-outline-dark">Connexion</button>
                            </div>
                            <p class="text-center">Pas encore inscrit ? <a href="?page=inscription" style="color:#566573">Inscrivez vous</a></p>
                        </form>
                    
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else{
        if($page == "inscription"){//Partie Inscription
            ?>
            <div class="container page_acceuil">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="shadow-lg p-3 mb-5 bg-body rounded">
                            <h4>Inscrivez vous</h4><br>
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label class="form-label">Identifiant de connexion</label>
                                    <input name="id" type="text" class="form-control" placeholder="Entrez votre identifiant" autocomplete="off" Required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input name="email"  type="text" class="form-control" placeholder="Entrez votre mail" autocomplete="off" Required>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">Mots de passe</label>
                                            <input name="mdp1" placeholder="Entrez votre mots de passe" type="password" class="form-control" autocomplete="off" Required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">Confirmer votre Mots de passe</label>
                                            <input name="mdp2" placeholder="Confirmer votre mots de passe" type="password" class="form-control" autocomplete="off" Required>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid gap-2 bouton_connexion">
                                    <button type="submit" class="btn btn-outline-dark">S'inscrire</button>
                                </div>
                                <p class="text-center">Vous avez deja un compte ? <a href="?page=connexion" style="color:#566573">Connectez vous</a></p>
                            </form>
                        
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }else{
            if($page == "motsDePasseOublier"){//Partie Mots de passe oublié
            ?>
            <div class="container page_acceuil">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="shadow-lg p-3 mb-5 bg-body rounded">
                            <h4>Mots de passe oublié</h4><br>
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label class="form-label">Saisissez votre Email</label>
                                    <input name="email"  type="text" class="form-control" placeholder="Entrez votre mail" autocomplete="off" Required>
                                </div>
                                <div class="d-grid gap-2 bouton_connexion">
                                    <button type="submit" class="btn btn-outline-dark">Valider</button>
                                </div>
                                <p class="text-center">Vous avez deja un compte ? <a href="?page=connexion" style="color:#566573">Connectez vous</a></p>
                            </form>
                        
                        </div>
                    </div>
                </div>
            </div>

            <?php
            }
        }
    }

    
    ?>
    












    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>
<h1>Projet 6 - Développez de A à Z le site communautaire SnowTricks</h1>
<h2>>Codacy</h2>
<h3>Vous trouverez la qualité du code suivi via Codacy : </h3>
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/09cd42a3c42c4deb8e7d6b557a7de9c3)](https://www.codacy.com/gh/Thibault-OC/P6/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Thibault-OC/P6&amp;utm_campaign=Badge_Grade)
<h3>Installation avec git</h3>
    <ul>
        <li>Utiliser la ligne de commande git  <code> git clone https://github.com/Thibault-OC/P5_Blog.git</code> </li>
        <li>Déplacez-vous dans le dossier du projet avec la commande <code> cd P5</code> </li>
        <li>Pour une utilisation optimal du projet installé composer <code> composer install </code></li>
        <li>Assurez-vous d'avoir préalablement activé le mode rewrite sur le serveur.</li>
    </ul>
<h3>Modifications des fichiers:</h3>
    <ul>
        <li>L'accès à une base de données est essentiel pour que le projet fonctionne pour se faire vous devrez importer le fichier bdd.sql dans votre système de gestion de base de données.</li>
        <li>Le changement des informations de connexion doit être changé dans le fichier P5/models/bddManager.php2<br>
            <code>
                $bdd = new PDO('mysql:host=localhost;dbname=nom_base_de_données;charset=utf8', 'id_base_de_données', 'code_base_de_données');
                //$bdd-&gt;setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $bdd-&gt;setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            </code>
        </li>
        <li>Une fois les informations modifiées renommer le fichier <code>P5/models/bddManager.php2</code> en <code>P5/models/bddManager.php</code></li>
    </ul>
<h3>L'envoi de mail</h3>
    <ul>
        <li>Modifier l'adresse email dans le fichier <code>P5/controllers/HomeController.php</code> pour les emails.<br>
            <code>  $email_to = "monEmail@monEmail.fr"; </code>
        </li>
    </ul> 

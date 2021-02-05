<h1>Projet 6 - Développez de A à Z le site communautaire SnowTricks</h1>
<h2>>Codacy</h2>
<h3>Vous trouverez la qualité du code suivi via Codacy : </h3>
<a href="https://www.codacy.com/gh/Thibault-OC/P6/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Thibault-OC/P6&amp;utm_campaign=Badge_Grade"><img src="https://app.codacy.com/project/badge/Grade/09cd42a3c42c4deb8e7d6b557a7de9c3"/></a>
<h3>Installation avec git</h3>
    <ul>
        <li>Utiliser la ligne de commande git  <code> git clone https://github.com/Thibault-OC/P6.git</code> </li>
        <li>Déplacez-vous dans le dossier du projet avec la commande <code> cd P6</code> </li>
        <li>Pour une utilisation optimal du projet installé composer <code> composer install </code></li>
        <li>Création de la base de donnée <code>php bin/console doctrine:database:create</code></li>
        <li>Création des tables de la base de donnée <code>php bin/console make:migration</code></li>
    </ul>
<h3>Modifier le fichier .env :</h3>
    <ul>
        <li>Ajouter les identifiants et mots de passe de la base de donnée</li>
        <li>Comfiguer le smtp du serveur pour l'envoi de mail<br>
             <code>MAILER_DSN=gmail://[utilisateur]:[mot de passe]@smtp.gmail.com</code>
        </li>
    </ul>


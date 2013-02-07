# Apprentidate
Apprentidate est une application web développée par Marien Fressinaud permettant de gérer des évenements.
Elle permet notamment de créer des sondages sur le modèle de Doodle.

## INSTALLATION
* Modifiez le fichier `/app/configuration/application.ini` en modifiant la valeur de base_url (chemin partant de la racine du serveur apache jusqu'au répertoire public de l'application) et les paramètres de la base de données (pensez à décommenter les lignes
* Éxécutez le script MySQL `apprentidate.sql` après avoir créé la table correspondant au paramètre `base` de votre fichier de configuration
* Vérifiez les droits en lecture / écriture sur le répertoire de log (`/log`)

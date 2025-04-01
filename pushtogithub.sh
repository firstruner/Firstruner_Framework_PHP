echo "Création du dossier à destination de GitHub"
cd ..
cp -r Firstruner_Framework_PHP Firstruner_Framework_PHP_GitHub
cd Firstruner_Framework_PHP_GitHub

echo ""
echo "Ajout du repository GitHub"
git remote add github https://github.com/firstruner/Firstruner_Framework_PHP.git

echo ""
echo "Renommage des utilisateurs"
git filter-repo --force --name-callback 'return b"Firstruner"' --email-callback 'return b"contact@firstruner.fr"'
git push --force github main
cd ..
rm -rf Firstruner_Framework_PHP_GitHub
cd Firstruner_Framework_PHP

echo ""
echo "Fin du traitement"
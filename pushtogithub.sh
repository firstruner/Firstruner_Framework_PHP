#!/usr/bin/env bash
set -euo pipefail

: "${GITHUB_USER:?Missing GITHUB_USER}"
: "${GITHUB_TOKEN:?Missing GITHUB_TOKEN}"

echo "Création du dossier à destination de GitHub"
cd ..
rm -rf Firstruner_Framework_PHP_GitHub
cp -r Firstruner_Framework_PHP Firstruner_Framework_PHP_GitHub
cd Firstruner_Framework_PHP_GitHub

echo ""
echo "Ajout du repository GitHub"
git remote remove github 2>/dev/null || true
git remote add github "https://${GITHUB_USER}:${GITHUB_TOKEN}@github.com/firstruner/Firstruner_Framework_PHP.git"

echo ""
echo "Renommage des utilisateurs"
git filter-repo --force --name-callback 'return b"Firstruner"' --email-callback 'return b"contact@firstruner.fr"'

echo ""
echo "Push forcé vers GitHub"
git push --force github main

cd ..
rm -rf Firstruner_Framework_PHP_GitHub
cd Firstruner_Framework_PHP

echo ""
echo "Fin du traitement"

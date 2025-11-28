#!/bin/bash

# Script d'installation automatique pour Polyclinique
# Usage: bash setup.sh

clear
echo "╔════════════════════════════════════════════════════╗"
echo "║   🏥 INSTALLATION SYSTÈME POLYCLINIQUE           ║"
echo "╚════════════════════════════════════════════════════╝"
echo ""

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Vérification PHP
echo -n "🔍 Vérification de PHP... "
if command -v php &> /dev/null; then
    echo -e "${GREEN}✓ OK${NC}"
else
    echo -e "${RED}✗ PHP non trouvé${NC}"
    echo "Installation: sudo apt install php php-mysql"
    exit 1
fi

# Vérification MySQL
echo -n "🔍 Vérification de MySQL... "
if command -v mysql &> /dev/null; then
    echo -e "${GREEN}✓ OK${NC}"
else
    echo -e "${RED}✗ MySQL non trouvé${NC}"
    echo "Installation: sudo apt install mysql-server"
    exit 1
fi

# Création de la base de données
echo ""
echo "📊 Configuration de la base de données..."
echo -n "Voulez-vous créer/réinitialiser la base de données? (o/N): "
read -r response

if [[ "$response" =~ ^([oO][uU][iI]|[oO])$ ]]; then
    echo ""
    echo "🗄️  Création de la base de données..."
    
    # Demander le mot de passe MySQL
    echo -n "Mot de passe MySQL root (appuyez sur Entrée si vide): "
    read -s mysql_password
    echo ""
    
    # Importer le schéma
    if [ -z "$mysql_password" ]; then
        mysql -h 10.255.255.254 -u root < database/schema.sql 2>/dev/null
    else
        mysql -h 10.255.255.254 -u root -p"$mysql_password" < database/schema.sql 2>/dev/null
    fi
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ Base de données créée${NC}"
        
        # Demander si on veut les données de test
        echo -n "Importer les données de test? (o/N): "
        read -r test_data_response
        
        if [[ "$test_data_response" =~ ^([oO][uU][iI]|[oO])$ ]]; then
            if [ -f "database/test_data.sql" ]; then
                if [ -z "$mysql_password" ]; then
                    mysql -h 10.255.255.254 -u root < database/test_data.sql 2>/dev/null
                else
                    mysql -h 10.255.255.254 -u root -p"$mysql_password" < database/test_data.sql 2>/dev/null
                fi
                
                if [ $? -eq 0 ]; then
                    echo -e "${GREEN}✓ Données de test importées${NC}"
                else
                    echo -e "${YELLOW}⚠ Erreur lors de l'import des données de test${NC}"
                fi
            else
                echo -e "${YELLOW}⚠ Fichier test_data.sql introuvable${NC}"
            fi
        fi
    else
        echo -e "${RED}✗ Erreur lors de la création de la base${NC}"
        echo "Vérifiez vos identifiants MySQL"
        exit 1
    fi
fi

# Vérification des fichiers
echo ""
echo "📁 Vérification de la structure..."

required_files=(
    "config/database.php"
    "index.php"
    "models/Patient.php"
    "models/Medecin.php"
    "models/Consultation.php"
    "models/Prescription.php"
    "views/patients.php"
    "views/consultations.php"
    "views/medecins.php"
    "public/css/style.css"
    "public/js/main.js"
)

all_ok=true
for file in "${required_files[@]}"; do
    if [ -f "$file" ]; then
        echo -e "${GREEN}✓${NC} $file"
    else
        echo -e "${RED}✗${NC} $file ${YELLOW}(manquant)${NC}"
        all_ok=false
    fi
done

if [ "$all_ok" = false ]; then
    echo ""
    echo -e "${YELLOW}⚠ Certains fichiers sont manquants${NC}"
    echo "Assurez-vous que tous les fichiers sont présents"
fi

# Résumé
echo ""
echo "╔════════════════════════════════════════════════════╗"
echo "║              ✅ INSTALLATION TERMINÉE             ║"
echo "╚════════════════════════════════════════════════════╝"
echo ""
echo "🚀 Pour démarrer le serveur:"
echo "   ${GREEN}php -S localhost:8000${NC}"
echo ""
echo "🌐 Puis ouvrez votre navigateur:"
echo "   ${GREEN}http://localhost:8000${NC}"
echo ""
echo "📚 Documentation complète:"
echo "   Consultez le fichier README.md"
echo ""
echo "💡 Commandes utiles:"
echo "   • Voir les tables: mysql -h 10.255.255.254 -u root polyclinic_db -e 'SHOW TABLES;'"
echo "   • Tester connexion: php -r \"new PDO('mysql:host=10.255.255.254;dbname=polyclinic_db', 'root', '');\""
echo ""
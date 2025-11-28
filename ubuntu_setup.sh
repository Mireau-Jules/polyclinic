#!/bin/bash

# Script d'installation complète pour Ubuntu (WSL2)
# Ce script installe PHP, MySQL et configure tout le projet

clear
echo "╔══════════════════════════════════════════════════════╗"
echo "║  🐧 INSTALLATION COMPLÈTE - UBUNTU/WSL2             ║"
echo "║  Polyclinique - Environnement Complet               ║"
echo "╚══════════════════════════════════════════════════════╝"
echo ""

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Fonction pour afficher les étapes
step() {
    echo -e "${BLUE}➜${NC} $1"
}

success() {
    echo -e "${GREEN}✓${NC} $1"
}

error() {
    echo -e "${RED}✗${NC} $1"
}

warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

# ===================================
# ÉTAPE 1: MISE À JOUR DU SYSTÈME
# ===================================
step "Mise à jour du système..."
sudo apt update
if [ $? -eq 0 ]; then
    success "Système mis à jour"
else
    error "Erreur lors de la mise à jour"
    exit 1
fi
echo ""

# ===================================
# ÉTAPE 2: INSTALLATION DE PHP
# ===================================
step "Installation de PHP et extensions..."
sudo apt install -y php php-cli php-mysql php-mbstring php-xml php-curl php-zip php-gd
if [ $? -eq 0 ]; then
    success "PHP installé"
    php -v | head -1
else
    error "Erreur lors de l'installation de PHP"
    exit 1
fi
echo ""

# ===================================
# ÉTAPE 3: INSTALLATION DE MYSQL
# ===================================
step "Installation de MySQL Server..."
sudo apt install -y mysql-server
if [ $? -eq 0 ]; then
    success "MySQL Server installé"
    mysql --version
else
    error "Erreur lors de l'installation de MySQL"
    exit 1
fi
echo ""

# ===================================
# ÉTAPE 4: DÉMARRAGE DE MYSQL
# ===================================
step "Démarrage du service MySQL..."
sudo service mysql start
if [ $? -eq 0 ]; then
    success "MySQL démarré"
else
    error "Erreur lors du démarrage de MySQL"
fi
echo ""

# ===================================
# ÉTAPE 5: CONFIGURATION DE MYSQL
# ===================================
step "Configuration de MySQL..."
warning "Configuration du mot de passe root MySQL"
echo ""
echo "Voulez-vous configurer un mot de passe pour root MySQL? (o/N)"
read -r setup_password

if [[ "$setup_password" =~ ^([oO][uU][iI]|[oO])$ ]]; then
    echo "Entrez le nouveau mot de passe root MySQL:"
    read -s mysql_root_password
    echo ""
    
    # Créer le mot de passe
    sudo mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '$mysql_root_password';"
    sudo mysql -e "FLUSH PRIVILEGES;"
    
    if [ $? -eq 0 ]; then
        success "Mot de passe configuré"
        echo "📝 N'oubliez pas de mettre à jour config/database.php avec ce mot de passe"
    else
        error "Erreur lors de la configuration du mot de passe"
    fi
else
    warning "Pas de mot de passe configuré (mode par défaut)"
    # Permettre la connexion sans mot de passe
    sudo mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '';"
    sudo mysql -e "FLUSH PRIVILEGES;"
fi
echo ""

# ===================================
# ÉTAPE 6: CRÉATION DE LA BASE
# ===================================
step "Création de la base de données..."

if [ -f "database/schema.sql" ]; then
    if [ -z "$mysql_root_password" ]; then
        sudo mysql < database/schema.sql
    else
        mysql -u root -p"$mysql_root_password" < database/schema.sql
    fi
    
    if [ $? -eq 0 ]; then
        success "Base de données 'polyclinic_db' créée"
        
        # Proposer d'importer les données de test
        echo ""
        echo "Voulez-vous importer les données de test? (o/N)"
        read -r import_test
        
        if [[ "$import_test" =~ ^([oO][uU][iI]|[oO])$ ]]; then
            if [ -f "database/test_data.sql" ]; then
                if [ -z "$mysql_root_password" ]; then
                    sudo mysql < database/test_data.sql
                else
                    mysql -u root -p"$mysql_root_password" < database/test_data.sql
                fi
                
                if [ $? -eq 0 ]; then
                    success "Données de test importées"
                else
                    warning "Erreur lors de l'import des données de test"
                fi
            else
                warning "Fichier test_data.sql introuvable"
            fi
        fi
    else
        error "Erreur lors de la création de la base"
    fi
else
    error "Fichier database/schema.sql introuvable"
    warning "Assurez-vous d'être dans le bon dossier"
fi
echo ""

# ===================================
# ÉTAPE 7: MISE À JOUR DE LA CONFIG
# ===================================
step "Mise à jour de la configuration..."

if [ -f "config/database.php" ]; then
    # Créer une backup
    cp config/database.php config/database.php.backup
    
    # Mettre à jour avec localhost
    cat > config/database.php << 'EOF'
<?php
// Configuration pour Ubuntu/WSL2 local
$host = "localhost";
$dbname = "polyclinic_db";
$username = "root";
$password = "";  // Changez si vous avez défini un mot de passe

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Fonction utilitaire pour sécuriser les entrées
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
EOF
    
    success "Configuration mise à jour (backup créé)"
    
    if [ ! -z "$mysql_root_password" ]; then
        warning "N'oubliez pas d'ajouter votre mot de passe dans config/database.php"
    fi
else
    error "Fichier config/database.php introuvable"
fi
echo ""

# ===================================
# ÉTAPE 8: TEST DE CONNEXION
# ===================================
step "Test de la connexion à la base de données..."

TEST_RESULT=$(php -r "
try {
    \$pdo = new PDO('mysql:host=localhost;dbname=polyclinic_db', 'root', '');
    echo 'SUCCESS';
} catch(PDOException \$e) {
    echo 'FAILED: ' . \$e->getMessage();
}
" 2>&1)

if [[ $TEST_RESULT == "SUCCESS" ]]; then
    success "Connexion à la base de données OK"
else
    error "Échec de la connexion: $TEST_RESULT"
fi
echo ""

# ===================================
# RÉSUMÉ ET INSTRUCTIONS
# ===================================
echo ""
echo "╔══════════════════════════════════════════════════════╗"
echo "║            ✅ INSTALLATION TERMINÉE                 ║"
echo "╚══════════════════════════════════════════════════════╝"
echo ""
echo -e "${GREEN}Services installés:${NC}"
echo "  ✓ PHP $(php -v | head -1 | cut -d ' ' -f2)"
echo "  ✓ MySQL $(mysql --version | cut -d ' ' -f6)"
echo ""
echo -e "${BLUE}Base de données:${NC}"
echo "  • Nom: polyclinic_db"
echo "  • User: root"
echo "  • Pass: $([ -z "$mysql_root_password" ] && echo "[vide]" || echo "[configuré]")"
echo ""
echo -e "${YELLOW}Pour démarrer le serveur:${NC}"
echo "  ${GREEN}php -S localhost:8000${NC}"
echo ""
echo -e "${YELLOW}Puis ouvrir dans le navigateur:${NC}"
echo "  ${GREEN}http://localhost:8000${NC}"
echo ""
echo -e "${BLUE}Commandes utiles:${NC}"
echo "  • Démarrer MySQL:  ${GREEN}sudo service mysql start${NC}"
echo "  • Arrêter MySQL:   ${GREEN}sudo service mysql stop${NC}"
echo "  • Status MySQL:    ${GREEN}sudo service mysql status${NC}"
echo "  • Accéder MySQL:   ${GREEN}sudo mysql${NC}"
echo ""
echo -e "${YELLOW}Structure du projet:${NC}"
echo "  $(pwd)"
echo ""
echo "🎉 Tout est prêt ! Bon développement !"
echo ""
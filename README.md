# 🏥 Système de Gestion de Polyclinique

Un système moderne et simple de gestion des patients, consultations et prescriptions pour une polyclinique.

## 🚀 Technologies
- PHP 7.4+
- MySQL 5.7+
- HTML5/CSS3
- JavaScript (Vanilla)

## 📋 Prérequis

Vérifiez votre environnement avec :
```bash
php -v
mysql --version
```

## 🔧 Installation

### 1. Cloner ou télécharger le projet
```bash
cd ~/Development/code/polyclinic
```

### 2. Créer la base de données
```bash
# Se connecter à MySQL
sudo mysql -u root -p

# Ou si pas de mot de passe
mysql -u root
```

Puis exécuter :
```sql
source database/schema.sql;
exit;
```

### 3. Configurer la connexion
Vérifier que `config/database.php` contient les bonnes informations :
```php
$host = "10.255.255.254";  
$dbname = "polyclinic_db";
$username = "root";
$password = "";
```

### 4. Lancer le serveur
```bash
# Depuis le dossier racine du projet
php -S localhost:8000
```

Ouvrir dans le navigateur : `http://localhost:8000`

## 📁 Structure du Projet

```
polyclinic/
├── index.php              # Tableau de bord
├── config/
│   └── database.php       # Configuration DB
├── database/
│   └── schema.sql         # Schéma de la base
├── models/                # Logique métier
│   ├── Patient.php
│   ├── Medecin.php
│   ├── Consultation.php
│   └── Prescription.php
├── controllers/           # Contrôleurs
│   └── PatientController.php
├── views/                 # Pages d'interface
│   ├── patients.php
│   ├── add_patient.php
│   ├── patient_details.php
│   ├── consultations.php
│   └── medecins.php
├── includes/              # Templates communs
│   ├── header.php
│   └── footer.php
└── public/                # Assets
    ├── css/
    │   └── style.css
    └── js/
        └── main.js
```

## ✨ Fonctionnalités

### ✅ Gestion des Patients
- Créer un dossier patient
- Rechercher un patient
- Lister tous les patients
- Voir le dossier complet avec historique

### ✅ Gestion des Consultations
- Enregistrer une consultation
- Voir l'historique par patient
- Associer médecin et patient

### ✅ Gestion des Prescriptions
- Enregistrer une ordonnance
- Lier à une consultation
- Voir toutes les prescriptions d'un patient

### ✅ Tableau de Bord
- Statistiques globales
- Dernières consultations
- Navigation facile

## 🎨 Design
- Interface moderne et responsive
- Design simple et intuitif
- Compatible mobile/tablette
- Animations fluides

## 🔒 Sécurité
- Requêtes préparées (PDO)
- Protection XSS
- Validation des données
- Gestion des erreurs

## 📝 Commandes Utiles

```bash
# Vérifier les services
sudo systemctl status mysql
sudo systemctl status apache2

# Redémarrer MySQL
sudo systemctl restart mysql

# Voir les logs d'erreur PHP
tail -f /var/log/apache2/error.log

# Tester la connexion DB
php -r "new PDO('mysql:host=10.255.255.254;dbname=polyclinic_db', 'root', '');"
```

## 🐛 Dépannage

### Erreur de connexion DB
- Vérifier que MySQL est démarré
- Vérifier l'IP dans `config/database.php`
- Vérifier que la base existe

### Page blanche
- Activer l'affichage des erreurs dans PHP
- Vérifier les logs

### CSS ne se charge pas
- Vérifier les chemins dans `header.php`
- S'assurer que le serveur est lancé depuis la racine

## 🚀 Prochaines Améliorations
- [ ] Authentification médecins
- [ ] Export PDF des prescriptions
- [ ] Statistiques avancées
- [ ] Rappels de rendez-vous
- [ ] API REST

## 👨‍💻 Développé pour
Projet de Fin d'Année - Technologie Web

## 📄 Licence
Projet académique
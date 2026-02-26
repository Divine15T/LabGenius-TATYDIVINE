# LabGenius-TATYDIVINE
Simulateur de laboratoire
# LabGenius - Plateforme de Laboratoire Génétiqu

## Description

LabGenius est une application web de simulation de laboratoire génétique permettant de manipuler des sequences d'ADN, de simuler des syntheses et de gerer une bibliotheque de sequences. Developpee en PHP pur, elle offre une interface intuitive pour les chercheurs et etudiants en biologie moleculaire.

## Fonctionnalites

- Systeme d'authentification - Acces securise au laboratoire
- Tableau de bord - Vue d'ensemble des activites du laboratoire
- Sequenceur - Visualisation et mutation de sequences ADN (vues texte/graphique)
- Machine de synthese - Simulation de synthese avec taux de reussite
- Bibliotheque genomique - Gestion des sequences avec systeme de favoris
- Carnet de laboratoire - Notes et journal des experiences
- Theme sombre/clair - Interface adaptable

## Structure du projet

```
LabGenius/
│
├── index.php                  # Tableau de bord
├── login.php                  # Page de connexion
├── logout.php                 # Deconnexion
├── theme.php                  # Gestionnaire de theme
├── .gitignore                 # Fichiers ignores par Git
│
├── php/                       # Pages fonctionnelles
│   ├── Database.php           # Gestion des donnees JSON
│   ├── Sequence.php           # Classe de manipulation ADN
│   ├── functions.php          # Fonctions utilitaires
│   ├── sequenceur.php         # Page sequenceur
│   ├── synthese.php           # Page synthese
│   ├── bibliotheque.php       # Page bibliotheque
│   └── carnet.php             # Page carnet de notes
│
├── templates/                 # Templates reutilisables
│   ├── header.php             # En-tete commun
│   └── footer.php             # Pied de page commun
│
├── css/                       # Fichiers de style
│   ├── style.css              # Styles communs
│   ├── login.css              # Styles connexion
│   ├── dashboard.css          # Styles tableau de bord
│   ├── sequenceur.css         # Styles sequenceur
│   ├── synthese.css           # Styles synthese
│   ├── bibliotheque.css       # Styles bibliotheque
│   └── carnet.css             # Styles carnet
│
├── js/                        # Fichiers JavaScript
│   ├── main.js                # Script commun
│   ├── login.js               # Script connexion
│   ├── sequenceur.js          # Script sequenceur
│   ├── synthese.js            # Script synthese
│   ├── bibliotheque.js        # Script bibliotheque
│   └── carnet.js              # Script carnet
│
├── data/                      # Donnees (ignore par Git)
│   └── data.json              # Fichier de donnees (cree automatiquement)
│
└── img/                    # Ressources statiques
    └── logo.png                # Logo du site
```

## Installation

1. Cloner le depot
```bash
git clone https://github.com/votre-utilisateur/labgenius.git
```

2. Placer les fichiers dans le repertoire de votre serveur web
   - Pour Laragon : `C:\laragon\www\LaboGenius\`
   - Pour XAMPP : `C:\xampp\htdocs\LaboGenius\`
   - Pour WAMP : `C:\wamp64\www\LaboGenius\`

3. Configurer les permissions
```bash
# Sous Linux/Mac
chmod 755 data/
chmod 644 data/.gitkeep
```

4. Acceder a l'application
```
http://localhost/LabGenius/login.php
```

## Utilisation

### Connexion
- Identifiant par defaut : `labgenius`
- Mot de passe par defaut : `labgenius123`

### Navigation
- **Dashboard** : Vue d'ensemble des statistiques
- **Sequenceur** : Edition et mutation de sequences ADN
- **Synthese** : Simulation de synthese genetique
- **Bibliotheque** : Gestion des sequences sauvegardees
- **Carnet** : Notes de laboratoire et historique

## Configuration

### Fichier .gitignore
```
data/
*.log
.DS_Store
```

### Base de donnees
Le fichier `data/data.json` est cree automatiquement par la classe Database lors de la premiere utilisation. Il contient les donnees par defaut :
- 3 sequences d'exemple
- 1 projet par defaut
- Structure vide pour notes et journal

## Technologies utilisees

- PHP 7.4+ (Programmation orientee objet)
- HTML5 / CSS3
- JavaScript (Vanilla)
- Font Awesome 6 pour les icones
- JSON pour le stockage des donnees
- Git pour le versionnement


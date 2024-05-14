## 📖 Table of Contents
- Project Overview ➡️ [Level](https://level.abdel-mansouri.com/)
- [English Version](#level-project--us-)
- [Version Française](#projet-level--fr-)

# Level Project ( :us: )

This is the Level project developed with Symfony. Follow the instructions below to set up and run the project on your local machine for development and testing purposes.

## ⚙️ Prerequisites

Before you begin, ensure you have met the following requirements:
- You have a local server environment like WAMP, LAMP, or MAMP installed.
- You have PHP 8.1 or newer.
- You have Composer installed.
- You have Symfony CLI for managing the Symfony application.

## 🚀 Installation

Follow these steps to get your development environment running:

### 1. Clone the repository
Clone the repository and navigate into the directory :
```

git clone https://github.com/AbdelMansouri/Level.git

```

### 2. Install dependencies
Run Composer to install the project dependencies :
```

composer install

```

### 3. Database setup
First, make sure to update the .env.local file with your database credentials :
```

APP_ENV=dev
DATABASE_URL="mysql://username:password@127.0.0.1:3306/your_database_name"
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0

```

Then, create a new database :
```

php bin/console doctrine:database:create

```

Next, apply migrations to set up your database schema :
```

php bin/console doctrine:migrations:migrate

```
### 4. Start the development server
You can start the local development server by running (make sure your local server is ready) :
```

symfony server:start

```

This will start a server on http://127.0.0.1:8000 by default.

### 5. Load fixtures (optional)
To populate the database with initial data, run:
```

php bin/console doctrine:fixtures:load

```

# Projet Level ( :fr: )

Ceci est le projet Level développé avec Symfony. Suivez les instructions ci-dessous pour configurer et exécuter le projet sur votre machine locale à des fins de développement et de test.

## ⚙️ Prérequis

Avant de commencer, assurez-vous de répondre aux exigences suivantes :
- Vous avez un environnement serveur local tel que WAMP, LAMP ou MAMP installé.
- Vous disposez de PHP 8.1 ou plus récent.
- Vous avez Composer installé.
- Vous disposez de Symfony CLI pour gérer l’application Symfony.

## 🚀 Installation

Suivez ces étapes pour obtenir votre environnement de développement en cours d'exécution :

### 1. Cloner le dépôt
Clonez le dépôt et naviguez dans le répertoire :
```

git clone https://github.com/AbdelMansouri/Level.git

```

### 2. Installer les dépendances
Exécutez Composer pour installer les dépendances du projet :
```

composer install

```

### 3. Configuration de la base de données
Tout d'abord, assurez-vous de mettre à jour le fichier .env.local avec vos identifiants de base de données :
```

APP_ENV=dev
DATABASE_URL="mysql://username:password@127.0.0.1:3306/your_database_name"
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0

```

Ensuite, créez une nouvelle base de données :
```

php bin/console doctrine:database:create

```

Puis, appliquez les migrations pour configurer votre schéma de base de données :
```

php bin/console doctrine:migrations:migrate

```
### 4. Démarrer le serveur de développement
Vous pouvez démarrer le serveur de développement local en exécutant (assurez-vous que votre serveur local est prêt) :
```

symfony server:start

```

Cela démarrera un serveur par défaut sur http://127.0.0.1:8000.

### 5. Charger les fixtures (optionnel)
Pour peupler la base de données avec des données initiales, exécutez :
```

php bin/console doctrine:fixtures:load

```

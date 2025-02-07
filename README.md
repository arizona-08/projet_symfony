# **FlexiFleet - Système de Gestion de Flotte**

FlexiFleet est une application web permettant aux agences de location de gérer leurs flottes de véhicules et aux clients de réserver des voitures en ligne. Elle propose une interface intuitive pour les différents utilisateurs ayant des rôles distincts : administrateurs, chefs d'agence, gestionnaires de commandes, fournisseurs et clients (dont des abonnés VIP).

---

## **Table des Matières**

1. [Installation](#installation)
2. [Utilisation](#utilisation)
3. [Fonctionnalités](#fonctionnalités)
4. [Comptes de test](#comptes-de-test)
5. [Contributeurs](#contributeurs)

---

## **Installation**

### **Prérequis**
Avant de commencer, assurez-vous d'avoir :
- **Docker** et **Docker Compose** installés sur votre machine.
- **PHP 8** (si vous exécutez le projet sans Docker).

### **Installation du projet**

1. Clonez le projet :
   ```bash
   git clone https://github.com/arizona-08/projet_symfony.git
   cd flexifleet
   ```
2. Construisez et démarrez les containers Docker :
   ```bash
   docker compose build --no-cache
   docker compose up -d
   ```
3. Installez les dépendances PHP :
   ```bash
   docker exec -it web_app composer install
   ```
4. Créez la base de données :
   ```bash
   docker exec -it web_app php bin/console doctrine:database:create
   ```
5. Chargez les données d'exemple (fixtures) :
   ```bash
   docker exec -it web_app php bin/console hautelook:fixtures:load --env=dev
   ```
6. Exécutez TailwindCSS (nécessaire pour le design) :
   ```bash
   docker exec -it web_app php bin/console tailwind:build --watch --poll
   ```
7. Accédez à l'application :
   - Ouvrez **localhost** dans votre navigateur.

---

## **Utilisation**

Une fois installé, FlexiFleet permet :
- De naviguer dans le catalogue des véhicules disponibles.
- De réserver des véhicules avec un compte utilisateur.
- De gérer les véhicules et les agences en fonction des permissions attribuées aux rôles.
- D'accéder à des fonctionnalités avancées via l'abonnement VIP.

---

## **Fonctionnalités**

### **1. Gestion des Utilisateurs**
- Inscription, connexion et réinitialisation de mot de passe.
- Gestion des profils.
- Administration des comptes utilisateurs.

### **2. Dashboard**
- Accès à une page permettant de louer des véhicules.

### **3. Gestion des Véhicules**
- Ajout, modification et suppression de véhicules.
- Gestion des disponibilités et des kilométrages.

### **4. Gestion des Agences**
- Création et gestion des agences pour les chefs d'agence et les administrateurs.

### **5. Gestion des Locations**
- Recherche et réservation de véhicules par les clients.
- Validation et gestion des commandes par les gestionnaires de commande.

### **7. Gestion des Commandes**
- Confirmation, annulation et suivi des réservations.

---

## **Comptes de Test**

Pour tester l'application, utilisez les identifiants suivants :

| Rôle               | Email                     | Mot de passe |
|---------------------|-------------------------|--------------|
| **Darkadmin**      | dark@example.com        | password     |
| **Admin**          | admin@example.com       | password     |
| **Client Standard**| client1@example.com     | password     |
| **Client VIP**     | clientvip@example.com   | password     |
| **Chef d'Agence**  | agencyh1@example.com    | password     |
| **Gestionnaire**   | agencym@example.com     | password     |

---

## **Contributeurs**

Ce projet a été réalisé par l'équipe **FlexiFleet**.

**Bon développement !** 🚀


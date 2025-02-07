# **FlexiFleet**

FlexiFleet est un système de gestion de flotte conçu pour les agences de véhicules souhaitant gérer leur flotte avec simplicité et efficacité. L'application fera aussi office de plateforme pour les clients souhaitant louer des véhicules tout en proposant une interface intuitive pour tout les utilisateurs. Qu'ils soient administrateurs, chef d'agence, préparateur de commande, fournisseur ou locataire. Tous auront, bien entendu, des permissions et restrictions différentes. De plus l'application contiendra une fonctionnalité cachée permettant aux locataires d'accéder à un service spécial grâce à un abonnement réservé pour cet effet.

## **Table des matières**
- Fonctionnalités
- Roles
- Installation !

## **Fonctionnalités**

### **1. Gestions des utilisateurs**

Le site permettra à chaque utilisateur de se créer un compte, modifier ses informations personnelles, et réinitialiser son mot de passe en cas d'oublie. Il sera également possible à l'utilisateur de modifier, bannir et supprimer le compte de n'importe quel utilisateur.

### **2. Dashboard**

Le dashboard permettra aux différents utilisateurs d'avoir quelques données statistiques de leur activité.
!!! Détailler le dashboard de chaque utilisateur !!!

- Dashboard: View comprehensive statistics on the fleet.

### **3. Gestion des véhicules**

La gestion des véhicules sera disponibles pour les administrateurs du site, les fournisseurs et les chefs d'agence.  
Les administrateurs et les fournisseur pourront ajouter, supprimer et gérer toutes les informations liées à un véhicule tandis que les chefs d'agence pourront uniquement agir sur la disponibilité et le kilométrage des véhicules de leurs agences.

### **4. Gestion des agences**

Les chefs d'agence et les administrateurs pourront créer, modifier et supprimer des agences.

- Agency Management: CRUD operations for agencies.

### **5. Gestion des abonnements**

Il y aura deux type d'abonnements:

- L'abonnement standard (gratuit): Les utilisateurs pourront commander n'importe quel véhicule d'une agence et paireont lors de la commande.
- L'abonnement VIP (définir un prix): Les utilisateurs auront une réduction d'un certain pourcentage sur le prix de la location plus l'accès à une option kidnapping. Cette option intègre un véhicule équippé spécialement pour le kidnapping en fonction de la cible, hommes, femmes, personnes agées ou enfants.

### **6. Gestion des mises en location**

Les administrateurs et les chefs d'agence pourront mettre en location leurs véhicules. Les clients pourront donc se rendre sur l'agence qui leur convient pour commander le véhicule de leur choix. Seuls les abonnés VIP pourront accéder à l'onglet VIP pour profiter de l'option kidnapping.

### **7. Gestion des commandes**

Les gestionnaires de commandes s'assureront de valider ou non les commandes des clients.
- Order Management: CRUD operations for orders.
- Role-Based Access Control: Different roles with specific access restrictions.
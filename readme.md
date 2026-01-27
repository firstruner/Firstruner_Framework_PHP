# Framework Firstruner pour PHP

# Licence
c.f. : Fichier licence.md

## Introduction

Le framework **Firstruner** est une extension puissante pour PHP, conçu pour enrichir le langage avec une série de fonctionnalités pratiques. Il se distingue par sa capacité à porter une grande partie des fonctionnalités et des classes issues du framework **.NET** (DotNet) directement dans PHP, ce qui facilite la transition des développeurs venant de l’écosystème Microsoft. Grâce à cette portabilité, les développeurs peuvent bénéficier de fonctionnalités avancées similaires à celles de C# et .NET tout en restant dans l’environnement PHP.

## Fonctionnalités principales

### 1. **Extension des fonctionnalités PHP**

Firstruner ajoute une multitude de fonctions à PHP pour rendre les opérations courantes plus simples et efficaces. Voici quelques-unes des principales fonctionnalités offertes :

- **Gestion avancée des erreurs et exceptions** : Firstruner introduit un gestionnaire d'exceptions plus flexible et puissant que celui de PHP, avec une prise en charge améliorée des types d'erreurs et des messages de débogage.
  
- **Manipulation des fichiers et répertoires** : Des fonctions supplémentaires pour gérer les fichiers et répertoires de manière plus intuitive, avec des fonctions de compression, décompression, et manipulation de fichiers sur des volumes distants.

- **Amélioration de la gestion des sessions** : Des extensions pour une gestion des sessions plus sécurisée et flexible, incluant des options de persistance des sessions côté serveur et un meilleur contrôle des données sensibles.

- **API RESTful** : Une bibliothèque intégrée pour créer des API RESTful avec une gestion des routes, de l’authentification et des réponses standardisées en JSON.

### 2. **Portage de fonctionnalités .NET dans PHP**

Une des caractéristiques les plus marquantes de **Firstruner** est son effort de portage de nombreuses classes et fonctionnalités de **.NET** vers PHP. Cela permet aux développeurs PHP de bénéficier de puissantes bibliothèques et fonctionnalités, habituellement réservées aux applications .NET, tout en restant dans l'environnement PHP.

#### Fonctionnalités et classes portées depuis .NET vers PHP :

Voici une liste non-exhausive de fonctions et classes déjà développées ou en cours de développement

- **Collections et structures de données** : Firstruner porte des classes similaires à celles de .NET pour gérer des collections et des structures de données avancées. Par exemple, des classes telles que `List<T>`, `Dictionary<TKey, TValue>`, et `Queue<T>` sont directement disponibles en PHP.
  
- **LINQ-like** : Firstruner intègre des fonctionnalités similaires à **LINQ** de .NET, permettant d'effectuer des requêtes complexes sur des collections, tableaux et objets en utilisant une syntaxe fluide et intuitive, semblable à celle de C#.

- **Gestion des dates et heures** : Des classes avancées pour la manipulation des dates et heures, inspirées de **System.DateTime** et des fonctionnalités associées en .NET, sont disponibles dans Firstruner pour faciliter la gestion du temps.

- **Interopérabilité avec des APIs externes** : Firstruner permet d'interagir facilement avec des APIs externes, qu'elles soient développées en .NET ou dans d'autres langages, grâce à une série de classes compatibles pour la gestion des requêtes HTTP et des réponses JSON.

- **Multi-threading et exécution parallèle** : Bien que PHP ne supporte pas directement le multi-threading, Firstruner offre des solutions pour exécuter des tâches de manière parallèle et non-bloquante, un concept emprunté de **Task Parallel Library (TPL)** en .NET.

- **Gestion des exceptions et des erreurs** : Firstruner adopte des mécanismes de gestion des erreurs similaires à ceux de **.NET**, avec des exceptions personnalisées et un contrôle amélioré des erreurs système.

- **Cryptographie et sécurité** : Le framework fournit des classes et fonctions pour la gestion de la cryptographie (chiffrement, hachage, signature numérique, etc.), inspirées des bibliothèques de sécurité de .NET.

### 3. **Utilisation de `loader.php` pour charger le Framework**

Le fichier **`loader.php`** est utilisé pour charger l'intégralité du framework Firstruner dans votre projet. Il s'agit du point d'entrée principal pour initialiser toutes les fonctionnalités du framework, et il garantit que toutes les classes et ressources nécessaires seront disponibles dans votre projet.

#### Comment l'utiliser :

```php
require_once 'path/to/framework/loader.php';

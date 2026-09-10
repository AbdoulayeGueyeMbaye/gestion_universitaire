# Gestion des réservations de salles universitaires

Application web PHP orientée objet permettant de consulter les salles universitaires et de gérer leurs réservations.

## État du projet

Le projet est en cours de construction, étape par étape, conformément au cahier des charges du projet de week-end.

## Objectifs

- gérer les salles et leur état d'activation ;
- créer, consulter et annuler des réservations ;
- empêcher les réservations qui se chevauchent ;
- appliquer une architecture PHP en couches avec injection de dépendances.

## Technologies prévues

- PHP 8.2 ou 8.3 ;
- MySQL ;
- Composer ;
- FastRoute ;
- Eloquent ;
- PHP-DI ;
- Respect\Validation ;
- PHP dotenv.

Les instructions d'installation et d'utilisation seront complétées au fil des étapes.

## Configuration locale

```bash
cp .env.example .env
composer install
php database/migrate.php
```

La migration nécessite une base MySQL accessible et l'extension PHP `pdo_mysql`.
Les commandes recommandées sont `php bin/console.php migrate:up` et `php bin/console.php migrate:down`.

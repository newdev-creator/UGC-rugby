
# Attention:
Ce projet n'est pas terminé. Il est en cours de développement.
Veuillez passer sur la branche `Filter_category_module` pour voir le projet en cours de développement.

## Requirements

* [PHP](http://php.net/) 8.1 or higher
* [Composer](https://getcomposer.org/)
* [Node.js](https://nodejs.org/) 16 or higher

## Installation

1. Clone the repository
2. Run `composer install`
3. Run `npm install`
4. Run `npm run build`
5. Run `php -S localhost:8000 -t public` or `symfony serve` to start the server
6. Create a database and run `php bin/console doctrine:migrations:migrate` to create the tables
7. Run `php bin/console doctrine:fixtures:load` to load the fixtures
8. Open `http://localhost:8000` in your browser
9. You can create a new user or use any user from the fixtures. 
    * The password for all users is `user`
    * The password for all admins is `admin`
10. Enjoy!

## About

C'est application est développée dans le cadre de relation avec le club de Rugby de Villemur sur Tarn (31).
Son but est de permettre aux parents d'inscrire leurs enfants aux entrainements, matchs, tournois et de gérer leurs disponibilités.
L'application dispose aussi d'un espace covoiturage pour qu'un parent puisse emmener un autre enfant à un entrainement ou un match.
Un espace administrateur permet de gérer les utilisateurs, les entrainements, les matchs, les tournois, les disponibilités et les covoiturages.

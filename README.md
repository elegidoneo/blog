# Test #

Pruebas de conocimientos en php

Nota: cambiar el nombre de .env-example a .env

### Paquetes utilizados ###

- laravel/sanctum

#### Comandos a ejecutar antes de probar la aplicacion ####

- composer update
- php artisan key:generate
- php artisan migrate
- php artisan db:seed
- php artisan serve

#### Comprobar que la aplicacion funciona ####

- vendor/bin/phpunit

Con este comando se comprueba todos los casos de uso de la aplicacion

#### Urls de la aplicacion ####

##### Autenticarse #####
- POST http://localhost/api/login
##### Guardar/Registar usuario #####
- POST http://localhost/api/user
##### Listar todos los usuarios (Solo administradores) #####
- GET http://localhost/api/user
##### ver informacion del usuario #####
- GET http://localhost/api/user/{id}
##### editar el usuario #####
- PUT/PATCH http://localhost/api/user/{id}
##### eliminar el usuario #####
- Delete http://localhost/api/user/{id}
##### Guardar post #####
- POST http://localhost/api/post
##### Listar todos los post #####
- GET http://localhost/api/post
##### ver post #####
- GET http://localhost/api/post/{id}
##### editar el post #####
- PUT/PATCH http://localhost/api/post/{id}
##### eliminar el post #####
- Delete http://localhost/api/post/{id} (Solo administradores)
##### Guardar comentario #####
- POST http://localhost/api/comment
##### Listar todos los commentarios #####
- GET http://localhost/api/comment
##### ver comentario #####
- GET http://localhost/api/comment/{id}
##### editar el comentario #####
- PUT/PATCH http://localhost/api/comment/{id}
##### eliminar el comentario #####
- Delete http://localhost/api/comment/{id}
##### calificar #####
- POST http://localhost/api/qualify/{post_id}
##### Ver average rating #####
- GET http://localhost/api/average-rating/{post}
##### logout #####
- POST http://localhost/api/logout


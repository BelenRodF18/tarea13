# Tarea 13 - Gestión de autos

Fullstack · 3°ME Informática · Belén Rodríguez

Es una página para ver, agregar y modificar autos. Los datos están en una base de datos MySQL con dos tablas, autos y marcas, y todo corre en Docker.

## Cómo levantarlo

Hace falta tener Docker instalado. Desde la carpeta del proyecto:

```
docker compose up -d --build
```

No hay que crear ni cambiar nada: los archivos `.env` ya vienen en el repositorio con todo lo necesario. La primera vez la base tarda unos segundos en cargarse.

Para apagarlo:

```
docker compose down
```

Si se quiere empezar de cero y borrar los datos que se cargaron: `docker compose down -v`.

## Dónde se abre

| Qué | Dirección |
|---|---|
| La página (listado de autos) | http://localhost:8094/front/index.html |
| La API | http://localhost:8094/api/autos |
| phpMyAdmin | http://localhost:8095 (usuario `usuario`, contraseña `password`) |

## Qué se puede hacer

- Ver el listado de autos con su marca y modelo.
- Hacer clic en un auto para ver todos sus datos.
- Modificar un auto desde el formulario. Al guardar aparece un mensaje y el cambio se ve al volver al listado.
- Agregar un auto nuevo con el botón «Agregar auto», eligiendo una de las marcas que ya existen.
- Los formularios avisan si falta algún dato obligatorio.

## Los archivos .env

El `.env` de la raíz tiene los puertos y los datos de la base para Docker. El de `api/.env` tiene los datos que usa PHP para conectarse (el servidor de la base se llama `db`).

## Cómo está armado

```
api/        el backend en PHP (rutas, controladores, modelo y conexión)
  config/   la conexión y init.sql, la base de datos de prueba
front/      las páginas HTML y su JavaScript
phpmyadmin/ configuración de phpMyAdmin
```

Las rutas de la API están en `api/routes.php`. En `api.http` hay ejemplos de pedidos para probarlas.

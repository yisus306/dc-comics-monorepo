# DC Comics Monorepo 🦸‍♂️📚

## Introducción

Este proyecto es una aplicación web básica que permite la visualización y búsqueda de personajes de DC Comics. La aplicación utiliza HTML, PHP, CSS y MySQL, y está diseñada para demostrar varias técnicas como el uso de cookies, procedimientos almacenados, inner joins y JSON.

## Estructura del Proyecto 🗂️

```plaintext
dc-comics-monorepo/
├── public/
│   ├── index.html
│   ├── css/
│   │   └── styles.css
├── src/
│   ├── database/
│   │   └── conexion.php
│   ├── models/
|   |   ├── comic.php
│   │   └── personaje.php
│   ├── views/
│   │   └── personaje.php
│   ├── controllers/
|   |   ├── controladorComic.php
│   │   └── controladorPersonaje.php
├── stored_procedures.sql
└── README.md
```

## Configuración del Entorno ⚙️
1.  Clona el repositorio:

  ```bash
  git clone https://github.com/tu_usuario/dc-comics-monorepo.git
  ```

2.  Configura tu entorno de desarrollo local:
  +  Instala un servidor local como XAMPP o MAMP.
  + Copia la carpeta **`dc-comics-monorepo`** en la carpeta **`htdocs`** de tu servidor local.

3.  Configura la base de datos:
  + Crea una base de datos llamada **`dc_comics`**.
  + Importa el archivo **`stored_procedures.sql`** para crear las tablas y procedimientos almacenados necesarios

## Instrucciones de Uso 🚀

1.  **Accede a la página principal:**

  +  Ve a http://localhost/dc-comics-monorepo/public/index.html para ver la página de bienvenida.
  +  Haz clic en el enlace para ver la lista de personajes.

2.  **Buscar un personaje:**
  +  En la página de personajes, ingresa el nombre de un personaje en el campo de búsqueda y presiona "Buscar".
  +  Los detalles del personaje se mostrarán si se encuentra en la base de datos.

3.  **Agregar nuevo personaje:**
  + En la página de personajes, presiona el botón "Nuevo 🦸‍♂️", que se encuentra a la derecha del botón buscar.
  + Ingresa los datos del nuevo personaje y presiona "Guardar".
  + Una vez guardado correctamente, el personaje se mostrará automáticamente en la lista de personajes.

4.  **Editar un personaje:**
  + En la página de personajes se muestra una lista de los personajes. Pasa el puntero sobre el personaje que desees modificar y haz clic en el botón "✒️".
  + Ingresa los nuevos datos del personaje y presiona "Guardar".
  + Una vez guardado correctamente, el personaje se mostrará automáticamente en la lista de personajes

5.  **Eliminar un personaje:**
  + En la página de personajes se muestra una lista de los personajes. Pasa el puntero sobre el personaje que desees eliminar y haz clic en el botón "❌".
  + El personaje se eliminará de la base de datos y se obtendrá la nueva lista de personajes.

## Contribuir 🤝
Si deseas contribuir a este proyecto, sigue estos pasos:

1.  **Fork el repositorio.**
2.  **Crea una nueva rama (git checkout -b feature/nueva-funcionalidad).**
3.  **Haz commit de tus cambios (git commit -am 'Añadir nueva funcionalidad').**
4.  **Haz push a la rama (git push origin feature/nueva-funcionalidad).**
5.  **Crea un nuevo Pull Request.**

## Documentación Técnica 📖
Para detalles técnicos sobre la implementación y estructura del proyecto, por favor consulta el [MANUAL-TECNICO](docs/MANUAL-TECNICO.md).

***

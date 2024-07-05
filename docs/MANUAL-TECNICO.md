# Documentación Técnica

## Índice
1. [Detalles de Implementación](#detalles-de-implementación)
    - [Base de Datos](#base-de-datos)
    - [Conexión a la Base de Datos](#conexión-a-la-base-de-datos)
    - [Modelos](#modelos)
    - [Controladores](#controladores)
    - [Vistas](#vistas)
    - [Estilos CSS](#estilos-css)
    - [Uso de Cookies](#uso-de-cookies)

## Detalles de Implementación
### Base de Datos
La base de datos consta de dos tablas: **`personajes`** y **`apariciones`**.

1.  **Tabla `personajes`:**

    +   **`id:`** Identificador único del personaje (clave primaria).
    +   **`nombre:`** Nombre del personaje.
    +   **`alias:`** Alias o apodo del personaje.
    +   **`especie:`** Especie del personaje.
    +   **`id_aparicion:`** Identificador de la aparicion del comic del personaje (clave foranea).

    ```sql
        CREATE TABLE personajes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            alias VARCHAR(100),
            especie VARCHAR(100),
            id_aparicion INT NOT NULL,
            CONSTRAINT fk_id_aparicion FOREIGN KEY (id_aparicion) REFERENCES apariciones(id)
        );
    ```
2.  **Tabla `apariciones`:**

    +   **`id:`** Identificador único de la aparicion (clave primaria).
    +   **`titulo_comic:`** Titulo del comic.
    ```sql
        CREATE TABLE apariciones (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo_comic VARCHAR(100) NOT NULL
        );
    ```
3.  **Procedimiento almacenado `obtenerPersonajePorNombre:`**
    Este procedimiento recupera los detalles de un personaje basado en su nombre.

    ```sql
    DELIMITER //

    CREATE PROCEDURE obtenerPersonajePorNombre (IN nombre_personaje VARCHAR(100))
    BEGIN
    SELECT personajes.id, personajes.id_aparicion, personajes.nombre, personajes.alias, personajes.especie, apariciones.titulo_comic FROM apariciones INNER JOIN personajes ON personajes.id_aparicion = apariciones.id WHERE nombre = nombre_personaje;
    END //

    DELIMITER ;
    ```

### Conexión a la Base de Datos
Para conectarnos a la base de datos MySQL, utilizamos un archivo PHP que establece la conexión utilizando las credenciales y parámetros adecuados.

Archivo: **`src/database/conexion.php`**

```php
<?php
$nombreServidor = "localhost";
$nombreUsuario = "root";
$contrasena = "";
$nombreBaseDatos = "dc_comics";

// Crear conexion
$con = new mysqli($nombreServidor, $nombreUsuario, $contrasena, $nombreBaseDatos);

// Verificar conexion
if($con->connect_error){
    die("Conexcion fallida: ". $con->connect_error);
}
?>
```

### Modelos
El modelo se encarga de interactuar con la base de datos. Aquí tenemos una clase **`Personaje`** que define métodos para crear, obtener, actualizar y eliminar información de los personajes.

Archivo: **`src/models/personaje.php`**

```php
<?php

class Personaje {
    private $con;

    public function __construct($con){
        $this->con = $con;
    }

    public function obtenerPersonajePorNombre($nombre){
        $sql = "CALL obtenerPersonajePorNombre(?)";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function obtenerPersonajePorId($idCharacter){
        $sql = "SELECT personajes.id, personajes.id_aparicion, personajes.nombre, personajes.alias, personajes.especie, apariciones.titulo_comic FROM apariciones INNER JOIN personajes ON personajes.id_aparicion = apariciones.id WHERE personajes.id = $idCharacter;";
        $result = $this->con->query($sql);
        $personaje = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        return $personaje;    
    }

    public function editarPersonaje($idCharacter, $idComic, $nombre, $alias, $especie){
        $sql = "UPDATE personajes SET id_aparicion = ?, nombre = ?, alias = ?, especie = ? WHERE id = ?;";
        
        if ($stmt = $this->con->prepare($sql)) {
            $stmt->bind_param("isssi", $idComic, $nombre, $alias, $especie, $idCharacter);
            
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                $resultado = true;
            } else {
                $resultado = false; 
            }
            
            $stmt->close();
        } else {
            $resultado = false; 
        }
              
        return $resultado;
    }

    public function obtenerPersonajes(){
        $sql = "SELECT * FROM personajes;";
        $result = $this->con->query($sql);
        $personajes = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        return $personajes;
    }

    public function eliminarPersonajePorID($idPersonaje){
        $sqlDelete = "DELETE FROM personajes WHERE id = ?";
        
        if ($stmt = $this->con->prepare($sqlDelete)) {
            $stmt->bind_param("i", $idPersonaje);
            
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                $resultado = true;
            } else {
                $resultado = false; 
            }
            
            $stmt->close();
        } else {
            $resultado = false; 
        }
        
        return $resultado;
    }

    public function insertarPersonaje($idComic, $nombre, $alias, $especie){
        $sqlDelete = "INSERT INTO personajes (id_aparicion, nombre, alias, especie) VALUES(?, ?, ?, ?);";
        
        if ($stmt = $this->con->prepare($sqlDelete)) {
            $stmt->bind_param("isss", $idComic, $nombre, $alias, $especie);
            
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                $resultado = true;
            } else {
                $resultado = false; 
            }
            
            $stmt->close();
        } else {
            $resultado = false; 
        }
              
        return $resultado;
    }
}
?>
```

Aquí tenemos una clase **`Comic`** que define métodos para obtener información de las apariciones.

Archivo: **`src/models/comic.php`**
```php
<?php

class Comic {
    private $con;

    public function __construct($con){
        $this->con = $con;
    }

    public function obtenerComics(){
        $sql = "SELECT * FROM apariciones;";
        $result = $this->con->query($sql);
        $apariciones = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        return $apariciones;
    }
}
?>
```

### Controladores
El controlador se encarga de manejar las solicitudes del usuario y coordinar las acciones entre el modelo y las vistas.

Archivo: **`src/controllers/controladorPersonaje.php`**

```php
<?php
include_once '../database/conexion.php';
include_once '../models/personaje.php';

$modeloPersonaje = new Personaje($con);

$personajes = $modeloPersonaje->obtenerPersonajes();
$showModal = false;
$error = "";
$onEdit = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['abrir_modal'])){
    if($_POST['abrir_modal'] == "yes") $showModal = true;
} 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre_personaje'])) {
    $nombre = $_POST['nombre_personaje'];
    $personaje = $modeloPersonaje->obtenerPersonajePorNombre($nombre);

    if ($personaje === null) {
        $error = "El personaje '$nombre' no se encontró.";
    } else {
        // Almacenar en una cookie el último personaje encontrado
        setcookie("ultima_busqueda", json_encode($personaje), time() + (86400 * 1), "/");
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_personaje'])){
    $idPersonaje = $_POST['id_personaje'];
    $isDeleted = $modeloPersonaje->eliminarPersonajePorID($idPersonaje);
    if($isDeleted){
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['id_personaje_editar'])){
    $idPersonaje = $_POST['id_personaje_editar'];
    $personajeData = $modeloPersonaje->obtenerPersonajePorId($idPersonaje);
    $personaje = $personajeData[0];
    $idCharacter = $personaje['id'];
    $onEdit = true;
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_personaje'])){
    $idPersonaje = $_POST['id_personaje'];
    $isDeleted = $modeloPersonaje->eliminarPersonajePorID($idPersonaje);
    if($isDeleted){
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre']) && isset($_POST['alias']) && isset($_POST['especie']) && isset($_POST['id_comic'])){   
    $characterName = $_POST['nombre'];
    $alias = $_POST['alias'];
    $especie = $_POST['especie'];

    if($characterName != "" && $alias != "" && $especie != ""){
        $idComic = $_POST['id_comic'];
        $isInsert = $modeloPersonaje->insertarPersonaje($idComic, $characterName, $alias, $especie);
        if($isInsert){
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $error =  "Registro no creado";
        }    
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_nombre']) && isset($_POST['edit_alias']) && isset($_POST['edit_especie']) && isset($_POST['edit_id_comic'])){   
    $characterName = $_POST['edit_nombre'];
    $alias = $_POST['edit_alias'];
    $especie = $_POST['edit_especie'];

    if($characterName != "" && $alias != "" && $especie != ""){
        $idCharacter = $_POST['id'];
        $idComic = $_POST['edit_id_comic'];
        $isEdit = $modeloPersonaje->editarPersonaje($idCharacter, $idComic, $characterName, $alias, $especie);
        if($isEdit){
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $error =  "Registro no editado";
        }    
    }
}

?>
```

Archivo: **`src/controllers/controladorComic.php`**

```php
<?php
include_once '../database/conexion.php';
include_once '../models/comic.php';

$modeloComic = new Comic($con);

$comics = $modeloComic->obtenerComics();

?>
```

### Vistas
Las vistas son responsables de la presentación de la información. Aquí tenemos una vista básica que muestra un formulario de búsqueda, un formulario de personaje y la lista de personajes.

Archivo: **`src/views/personaje.php`**

```php
<?php 
include '../controllers/controladorPersonaje.php'; 
include '../controllers/controladorComic.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Personajes - DC Comics</title>
        <link rel="stylesheet" href="./../../public/css/styles.css">
        <style>
             /* Estilos para el modal */
            .create-modal {
                display: <?php echo $showModal && $onEdit == false ? 'block' : 'none'; ?>;
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgb(0,0,0);
                background-color: rgba(0,0,0,0.4);
                padding-top: 60px;
            }

            .edit-modal {
                display: <?php echo $showModal && $onEdit == true ? 'block' : 'none'; ?>;
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgb(0,0,0);
                background-color: rgba(0,0,0,0.4);
                padding-top: 60px;
            }

            .modal-content {
                background-color: #fefefe;
                margin: 5% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%;
            }

            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }

            .close:hover,
            .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <h1>Personajes de DC Comics</h1>
        <div class="row-container">
            <form method="POST" class="form-row" action="">
                <input type="text" name="nombre_personaje" placeholder="Ingresa nombre de personaje">
                <button type="submit" class="search-button">Buscar</button>
            </form>
            <form method="POST" action="">
                <input type="hidden" name="abrir_modal" value="no">
                <button type="submit" name="abrir_modal" value="yes" class="new-character-button">Nuevo 🦸‍♂️</button>
            </form>
        </div>

        <div id="myModal" class="create-modal">
            <div class="modal-content">
                <h2>Nuevo Personaje</h2>
                <!-- Aquí va el contenido del formulario del modal -->
                <form method="POST" action="" class="column-form">
                    <div class="form-column">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" >
                    </div>

                    <div class="form-column">
                        <label for="alias">Alias:</label>
                        <input type="text" id="alias" name="alias" >
                    </div>
                    
                    <div class="form-column">
                        <label for="especie">Especie:</label>
                        <input type="text" id="especie" name="especie" >
                    </div>

                    <div class="form-column">
                        <label for="comic">Comic:</label>
                        <select id="comics" name="id_comic">
                            <?php foreach($comics as $comic): ?>
                                <option nombre="opcion" value="<?= htmlspecialchars($comic['id']) ?>"><?= htmlspecialchars($comic['titulo_comic']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-column">
                        <button type="submit">Guardar</button>
                     </div>
                </form>
                <form method="GET" class="column-form">
                    <div class="form-column">
                        <button type="submit" name="abrir_modal" value="no">Cancelar</button>
                    </div>
                </form>            
            </div>
        </div>  

        <div id="myModal" class="edit-modal">
            <div class="modal-content">
                <h2>Nuevo Personaje</h2>
                <!-- Aquí va el contenido del formulario del modal -->
                <form method="POST" action="" class="column-form">
                    <div class="form-column">
                        <input type="hidden" name="id" value="<?= htmlspecialchars(isset($personaje) ? $personaje['id'] : "") ?>">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="edit_nombre" name="edit_nombre" value="<?= htmlspecialchars(isset($personaje) ? $personaje['nombre'] : "") ?>">
                    </div>

                    <div class="form-column">
                        <label for="alias">Alias:</label>
                        <input type="text" id="edit_alias" name="edit_alias" value="<?= htmlspecialchars(isset($personaje) ? $personaje['alias'] : "") ?>">
                    </div>

                    <div class="form-column">
                        <label for="especie">Especie:</label>
                        <input type="text" id="edit_especie" name="edit_especie" value="<?= htmlspecialchars(isset($personaje) ? $personaje['especie'] : "") ?>">
                    </div>
                    <div class="form-column">
                        <label for="comic">Comic:</label>
                        <select id="edit_comics" name="edit_id_comic">
                            <?php foreach($comics as $comic): ?>
                                <option value="<?= htmlspecialchars($comic['id']) ?>" <?= isset($personaje) && $personaje['id_aparicion'] == $comic['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($comic['titulo_comic']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-column">
                        <button type="submit">Guardar</button>
                    </div>
                </form>
                <form method="GET" class="column-form">
                    <div class="form-column">
                        <button type="submit" name="abrir_modal" value="no">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if(isset($personaje) && $onEdit == false): ?>
            <h2>Detalles del personaje</h2>
            <p>Nombre: <?= htmlspecialchars($personaje['nombre']) ?></p>
            <p>Alias: <?= htmlspecialchars($personaje['alias']) ?></p>        
            <p>Especie: <?= htmlspecialchars($personaje['especie']) ?></p>
            <p>Apareció: <?= htmlspecialchars($personaje['titulo_comic']) ?></p>
        <?php endif; ?>

        <h2>Todos los Personajes</h2>
        <?php if (!empty($personajes)): ?>
            <ul>
                <?php foreach($personajes as $personaje): ?>
                    <li>
                        <div class="form-row">
                            <?= htmlspecialchars($personaje['nombre']) ?> (<?= htmlspecialchars($personaje['alias']) ?>)
                            <form class="edit-form" method="POST" action="">
                                <input type="hidden" name="id_personaje_editar" value="<?= htmlspecialchars($personaje['id']) ?>">
                                <button type="submit" class="character-button" name="abrir_modal" value="yes">✒️</button>
                            </form>
                            <form class="delete-form" method="POST" action="">
                                <input type="hidden" name="id_personaje" value="<?= htmlspecialchars($personaje['id']) ?>">
                                <button type="submit" class="character-button" name="eliminar_personaje" value="Eliminar">❌</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="no-data">No existen personajes en la base de datos.</p>
        <?php endif; ?>
        <footer>
            <hr>
            <p>Creado con ❤️ por Martín Lujano</p>
        </footer>
    </body>
</html>
```

### Estilos CSS
Para darle estilo a la aplicación, utilizamos un archivo CSS simple.

Archivo: **`public/css/styles.css`**

```css
/* styles.css */

/* Estilo general */
body {
  font-family: Arial, sans-serif;
  background-color: #f0f0f0;
  color: #333;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
}

/* Encabezado */
h1, h2 {
  color: #007bff; /* Azul brillante inspirado en Superman */
  margin-bottom: 20px;
}

/* Párrafo */
p {
  font-size: 1.2rem;
  color: #555;
  text-align: center;
  margin: 0 auto;
}

/* Enlace */
a {
  color: #ff0000; /* Rojo brillante inspirado en Flash */
  text-decoration: none;
  font-weight: bold;
}

a:hover {
  text-decoration: underline;
}

.row-container {
  display: flex;
  flex-direction: row;
  align-items: center;
  padding: 5px 10px 5px 20px;
}

/* Formulario */
.form-row {
  display: flex;
  flex-direction: row;
  align-items: center;
}

input[type="text"] {
  padding: 10px;
  font-size: 1rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  margin-right: 10px;
  width: 100%;
  max-width: 300px;
}

button {
  padding: 10px 20px;
  font-size: 1rem;
  background-color: #007bff; /* Azul brillante inspirado en Superman */
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-right: 10px;
}

.search-button:hover {
  background-color: #0056b3; /* Color más oscuro para hover */
}

.new-character-button:hover {
  background-color: #0056b3; /* Color más oscuro para hover */
}

.no-data {
  background-color: none;
}

/* Lista de personajes */
ul {
  list-style-type: none;
  padding: 0;
  margin: 0 auto;
}

li {
  position: relative;
  padding-right: 30px; /* Space for the delete icon */
  transition: background-color 0.3s ease;
  background-color: #e0e0e0;
  margin: 10px 0;
  padding: 10px;
  border-radius: 4px;
  width: 30.5rem;
}

li .delete-form {
  display: none;
  position: absolute;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
}

li .edit-form { 
  display: none;
  position: absolute;
  right: 8%;
  top: 50%;
  transform: translateY(-50%);
}

li:hover {
  background-color: #f0f0f0;
}

li:hover .delete-form {
  display: inline;
}

li:hover .edit-form {
  display: inline;
}

.character-button {
  background: none;
  background-color: none;
  border: none;
  color: red;
  cursor: pointer;
  font-size: 16px;
}

.column-form {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  max-width: 600px;
  margin: 10px auto;
}

.form-column {
  flex: 1 1 calc(50% - 20px);
  display: flex;
  flex-direction: column;
  margin-right: 10px;
}

/* Mensaje de error */
.error-message {
  color: red;
  font-weight: bold;
}

footer {
  margin-top: auto;
  color: white;
  text-align: center;
  padding: 10px 0;
  width: 100%;
  box-sizing: border-box;
}

footer hr {
  border: none;
  border-top: 1px solid #fff;
  margin: 0 0 10px 0;
}

footer p {
  margin: 0;
}
```

### Uso de Cookies
Las cookies se utilizan para almacenar la última búsqueda del usuario. Esto se maneja en el controlador **`controladorPersonaje.php`**.

Archivo: **`src/controllers/characterController.php`**

```php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre_personaje'])) {
    $nombre = $_POST['nombre_personaje'];
    $personaje = $modeloPersonaje->obtenerPersonajePorNombre($nombre);

    if ($personaje === null) {
        $error = "El personaje '$nombre' no se encontró.";
    } else {
        // Almacenar en una cookie el último personaje encontrado
        setcookie("ultima_busqueda", json_encode($personaje), time() + (86400 * 1), "/");
    }
}
```
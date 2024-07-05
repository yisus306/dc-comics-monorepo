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
            .modal {
                display: <?php echo $showModal ? 'block' : 'none'; ?>;
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

        <div id="myModal" class="modal">
            <div class="modal-content">
                <h2>Nuevo Personaje</h2>
                <!-- Aquí va el contenido del formulario del modal -->
                <form method="POST" action="">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre">

                    <label for="alias">Alias:</label>
                    <input type="text" id="alias" name="alias">

                    <label for="especie">Especie:</label>
                    <input type="text" id="especie" name="especie">

                    <label for="comic">Comic:</label>
                    <select id="comics" name="id_comic">
                        <?php foreach($comics as $comic): ?>
                            <option nombre="opcion" value="<?= htmlspecialchars($comic['id']) ?>"><?= htmlspecialchars($comic['titulo_comic']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Guardar</button>
                    <button type="submit" name="abrir_modal" value="no">Cancelar</button>
                </form>
            </div>
        </div>  

        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if(isset($personaje)): ?>
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
                        <?= htmlspecialchars($personaje['nombre']) ?> (<?= htmlspecialchars($personaje['alias']) ?>)
                        <form class="delete-form" method="POST" action="">
                            <input type="hidden" name="id_personaje" value="<?= htmlspecialchars($personaje['id']) ?>">
                            <button type="submit" class="delete-button" name="eliminar_personaje" value="Eliminar">&times;</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="no-data">No existen personajes en la base de datos.</p>
        <?php endif; ?>
    </body>
</html>
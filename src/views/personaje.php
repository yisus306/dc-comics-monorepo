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
    </body>
</html>
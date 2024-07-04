<?php include '../controllers/controladorPersonaje.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Personajes - DC Comics</title>
        <link rel="stylesheet" href="../public/css/styles.css">
    </head>
    <body>
        <h1>Personajes de DC Comics</h1>
        <form method="POST" action="">
            <input type="text" name="nombre_personaje" placeholder="Ingresa nombre de personaje">
            <button type="submit">Buscar</button>
        </form>

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
                    <li><?= htmlspecialchars($personaje['nombre']) ?> (<?= htmlspecialchars($personaje['alias']) ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No se encontraron personajes.</p>
        <?php endif; ?>
    </body>
</html>
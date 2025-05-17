<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Formulário de Usuários</title>
</head>
<body>
    <form method="POST" action="usuarios/5">

        <?= csrf(); ?>
        <?= method('PUT'); ?>

        <label>Nome:</label>
        <input type="text" name="name">

        <br>
        
        <label>Email:</label>
        <input type="text" name="email">
        
        <br>

        <label>Senha:</label>
        <input type="password" name="password">

        <label>Nível:</label>
        <select name="level">
            <option value=""></option>
            <option value="1">Operador</option>
            <option value="2">Admin</option>
        </select>
        
        <button type="submit">Enviar</button>

    </form>
</body>
</html>

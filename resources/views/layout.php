<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Mini Framework') ?></title>

    <meta name="csrf-token" content='<?= csrf(true); ?>'>
    <!-- index.html -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
    <script type="module" src="http://localhost:5173/resources/js/main.js"></script>
</head>
<body>
    <div id="app"
         data-page="<?= htmlspecialchars($page) ?>"
         data-props='<?= isset($jsonProps) ? htmlspecialchars($jsonProps, ENT_QUOTES, 'UTF-8') : "{}" ?>'
    ></div>
</body>
</html>
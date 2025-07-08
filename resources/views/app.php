<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
=======
    <link rel="icon" type="image/png" href="<?= env('APP_URL'). '/public/img/logo.png'; ?>"/>
>>>>>>> 5c7bf29705850666a4329fa9e091dfcf25d4e5f5
    <title><?= htmlspecialchars(env('APP_NAME') ?? 'Meu Framework') ?></title>

    <meta name="csrf-token" content='<?= csrf(true); ?>'>
    <!-- index.html -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
    <?php echo vite('resources/js/app.js'); ?>
</head>
<body>
    <div id="app" data-page='<?= json_encode($page, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>'></div>
</body>
</html>
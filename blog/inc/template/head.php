<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= (isset($pageTitle) ? $pageTitle : APP_TITLE) ?></title>
        
        <link rel="stylesheet" href="<?= BASE_URL ?>css/bootstrap.min.css">
        <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
        
        <script src="<?= BASE_URL ?>js/jquery-3.6.0.min.js"></script>
        
        <script src="<?= BASE_URL ?>js/bootstrap.bundle.min.js"></script>
        
        <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
        <script>tinymce.init({selector:'textarea.editor'});</script>
    </head>
    <body>
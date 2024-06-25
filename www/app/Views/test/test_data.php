<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<div>
    <pre>
        <?php if (!empty($data)) {
            foreach ($data as $key => $value) {
                ?>
                $<?= $key ?> = <?= var_export($value, true) . PHP_EOL ?>
            <?php }
        } ?>
    </pre>
</div>
<div>
    <?php if (!empty($links)) {
        foreach ($links as $title => $link) {
            ?>
        <div><a href="<?= $link ?>"><?= $title ?></a> </div>
        <?php }
    } ?>
</div>
</body>
</html>
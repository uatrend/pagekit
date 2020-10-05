<?php foreach ($widgets as $widget) : ?>

    <?= getHTML($widget->get('result')) ?>

<?php endforeach ?>

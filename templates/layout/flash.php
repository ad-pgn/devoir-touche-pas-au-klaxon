<?php
/**
 * Affichage des messages flash.
 *
 * @var \Core\Controller $this
 */

$messages = \Core\Flash::pull();
?>
<?php foreach ($messages as $message) : ?>
    <div class="alert alert-<?= $this->e($message['type']) ?>" role="alert">
        <?= $this->e($message['message']) ?>
    </div>
<?php endforeach; ?>
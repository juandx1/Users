<?php
/**
 * @var \App\View\AppView $this
 */
?>
<div class="form large-8 large-offset-2 medium-8 medium-offset-2 columns content">
    <h1>Login</h1>
    <?= $this->Form->create() ?>
    <?= $this->Form->control('email') ?>
    <?= $this->Form->control('password') ?>
    <?= $this->Form->button('Login') ?>
    <?= $this->Form->end() ?>
    <?= $this->Html->link(__('Register'), ['controller' => 'Users', 'action' => 'register']) ?>
    <?php echo $this->Facebook->loginLink(); ?>`
</div>

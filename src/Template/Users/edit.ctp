<?php
/**
 * @var \App\View\AppView $this
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <?php if ($role === 'admin'): ?>
            <li><?= $this->Form->postLink(
                    __('Delete'),
                    ['action' => 'delete', $user->id],
                    ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]
                )
                ?></li>
        <?php endif; ?>
        <?php if ($role === 'admin' || $role === 'agent'): ?>
            <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('Export Users'), ['controller' => 'Excel', 'action' => 'exportUsers']) ?></li>
            <li><?= $this->Html->link(__('Import Users'), ['controller' => 'Excel', 'action' => 'importUsers']) ?></li>
        <?php endif; ?>
        <?php if ($role === 'admin'): ?>
            <li><?= $this->Html->link(__('List Roles'), ['controller' => 'Roles', 'action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('New Role'), ['controller' => 'Roles', 'action' => 'add']) ?></li>
        <?php endif; ?>
    </ul>
</nav>
<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Edit User') ?></legend>
        <?php
        echo $this->Form->control('name');
        echo $this->Form->control('email');
        echo $this->Form->control('password');
        echo $this->Form->control('phone_number');
        echo "<label for='active'>Active</label>";
        echo $this->Form->radio('active', ['no', 'yes']);
        if ($role === 'admin') {
            echo $this->Form->control('roles._ids', ['options' => $roles]);
        }
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>

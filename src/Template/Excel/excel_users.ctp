<?php
/**
 * @var \App\View\AppView $this
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Role'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Roles'), ['controller' => 'Roles', 'action' => 'index']) ?></li>
    </ul>
</nav>

<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create('Excel', ['type' => 'file', 'url' => ['controller' => 'Excel', 'action' => 'excelUsers'], 'role' => 'form']) ?>
    <fieldset>
        <legend><?= __('Import Users') ?></legend>
        <?php echo $this->Form->file('file', ['type' => 'file', 'accept'=>'.tsv, .csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel' ,'class' => 'form-control', 'label' => false, 'placeholder' => 'file upload',]); ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
<nav class="top-bar expanded" data-topbar role="navigation">
    <ul class="title-area large-3 medium-4 columns">
        <li class="name">
            <h1><a href=""><?= $this->fetch('title') ?></a></h1>
        </li>
    </ul>
        <ul class="title-area large-9 medium-8 columns">
            <li class="name text-right">
                <h1>
                    <?= $this->Html->link(__('Edit Personal Info'), ['controller' => 'Users', 'action' => 'edit', $user['id']]) ?>
                </h1
            </li>
            <li class="name text-right">
                <h1>
                    <?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout']) ?>
                </h1
            </li>
        </ul>
        <ul class="title-area large-9 medium-8 columns">
            <li class="name text-right">
                <h1>
                    <?= $this->Html->link(__('Register'), ['controller' => 'Users', 'action' => 'register']) ?>
                </h1
            </li>
        </ul>
</nav>
<?= $this->Flash->render() ?>
<div class="container clearfix">
    <?= $this->fetch('content') ?>
</div>
</body>
</html>

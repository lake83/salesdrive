<?php
app\assets\AdminAsset::register($this);

/* @var $this \yii\web\View */
/* @var $content string */

?>
<aside class="main-sidebar">
    <section class="sidebar">
<?= app\widgets\Menu::widget([
    'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
    'encodeLabels' => false,
    'items' => [
        ['label' => Yii::t('app', 'Users'), 'url' => ['/admin/user/index'], 'icon' => 'users'],
        ['label' => Yii::t('app', 'Candidates'), 'url' => ['/admin/candidates/index'], 'icon' => 'address-card-o']
    ]
]);	
?>
    </section>
</aside>
<?php

use yii\db\Migration;

class m170301_124905_settings extends Migration
{
    public function safeUp()
    {
        $this->createTable('settings', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'value' => $this->text()->notNull(),
            'label' => $this->string(100)->notNull(),
            'icon' => $this->string(50)->notNull(),
            'rules' => $this->string(50)->notNull(),
            'hint' => $this->string()->notNull()
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null);
        
        $settings = [
            [
                'name' => 'adminEmail',
                'value' => 'admin@example.com',
                'label' => Yii::t('app', 'Administrator Email'),
                'icon' => 'fa-envelope-o',
                'rules' => 'email',
                'hint' => Yii::t('app', 'Used to contact the site administrator.')
            ],
            [
                'name' => 'user.passwordResetTokenExpire',
                'value' => '86400',
                'label' => Yii::t('app', 'Password recovery time (sec.)'),
                'icon' => 'fa-clock-o',
                'rules' => 'integer',
                'hint' => Yii::t('app', 'After the specified period expires, the request to change the password becomes invalid.')
            ],
            [
                'name' => 'skin',
                'value' => 'skin-green',
                'label' => Yii::t('app', 'Theme admin panel'),
                'icon' => 'fa-paint-brush',
                'rules' => 'safe',
                'hint' => Yii::t('app', 'Color scheme. Options: ') . 'skin-blue, skin-black, skin-red, skin-yellow, skin-purple, skin-green, skin-blue-light, skin-black-light, skin-red-light, skin-yellow-light, skin-purple-light, skin-green-light'
            ],
            [
                'name' => 'phone_mask',
                'value' => '+7 (999) 999-99-99',
                'label' => Yii::t('app', 'Phone pattern'),
                'icon' => 'fa-phone',
                'rules' => 'safe',
                'hint' => Yii::t('app', 'Phone mask used in forms.')
            ]
        ];
        Yii::$app->db->createCommand()->batchInsert('settings', array_keys($settings[0]), $settings)->execute();
    }
    
    public function safeDown()
    {
        $this->dropTable('settings');        
    }
}
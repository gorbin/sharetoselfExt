<?php
/**
 * Created by PhpStorm.
 * User: Lesya
 * Date: 07.10.2016
 * Time: 12:46
 */

namespace app\models;

use yii\db\ActiveRecord;

class Subscribers extends ActiveRecord
{

    public function rules()
    {
        return [
            [['email'], 'required'],
            ['email', 'email'],
        ];
    }

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return 'subscribers';
    }

}
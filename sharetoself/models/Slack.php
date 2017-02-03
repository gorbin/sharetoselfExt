<?php
/**
 * Created by PhpStorm.
 * User: Lesya
 * Date: 11.10.2016
 * Time: 20:23
 */

namespace app\models;


use yii\db\ActiveRecord;

class Slack extends ActiveRecord
{

    public static function model()
    {
    }

    public function rules()
    {
        return [

        ];
    }

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return 'slack';
    }

}
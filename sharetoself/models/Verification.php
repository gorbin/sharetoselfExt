<?php
/**
 * Created by PhpStorm.
 * User: Lesya
 * Date: 11.10.2016
 * Time: 20:28
 */

namespace app\models;


use yii\db\ActiveRecord;

class Verification extends ActiveRecord
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
        return 'verification';
    }

//    public function save($runValidation = true, $attributeNames = null)
//    {
//
//    }
}
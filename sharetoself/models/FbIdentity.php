<?php
/**
 * Created by PhpStorm.
 * User: Lesya
 * Date: 10.10.2016
 * Time: 10:51
 */

namespace app\models;


use yii\db\ActiveRecord;

class FbIdentity extends ActiveRecord
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
        return 'fbidentify';
    }

//    public function save($runValidation = true, $attributeNames = null)
//    {
//
//    }
}
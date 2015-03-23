<?php
/**
 * Created by PhpStorm.
 * User: MacBookEr
 * Date: 3/23/15
 * Time: 1:01 PM
 */

namespace Base;


use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model{

    protected $primaryOwnerClassName;

    protected $multiOwnerClassNames = [];

    protected $modelAttributes = [
        //		START AT ZERO (0)!!! => [
        //
        //			'name' => 'nameOfAttribute',
        //
        //			'format' => '(choose 1: email, phoneNumber, url, password,
        //							 string, exists, enum, text, id, token, ipAddress, date)',
        //
        //			'nullable' => false,
        //
        //			'unique' => true,
        //
        //			'exists' => null,
        //
        //          'identifier' => false,
        //
        //          'key' => false,
        //
        //
        //			'enumValues' => [
        //				'item1',
        //				'item2',
        //				'item3'
        //			]
        //		],

    ];


    public function getSelfAttributes()
    {
        return $this->modelAttributes;
    }


    public function getSelfClassName()
    {
        return '\\'. get_class($this);
    }


    public function getSelfPrimaryOwnerClassName()
    {
        return $this->primaryOwnerClassName;
    }




    public function getSelfAttributeWithSetting()
    {

    }


}
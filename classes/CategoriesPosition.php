<?php

class CategoriesPosition extends ObjectModel
{
    public $id_categoriesposition;
    public $categorie_name;
    public $position;

    public static $definition = [
        'table' => 'categoriesposition',
        'primary' => 'id_categoriesposition',
        'fields' => [
            'id_categoriesposition' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'categorie_name' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName','required' => true],
            'position' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt','required' => true]
        ]
    ];


    public static function getAll()
    {
        $sql = 'SELECT * FROM '._DB_PREFIX_.'categoriesposition';

        return DB::getInstance()->executeS($sql);

    }

    public static function getCategByPosition()
    {
        $sql = 'SELECT * FROM '._DB_PREFIX_.'categoriesposition ORDER BY position ASC';

        return DB::getInstance()->executeS($sql);
    }
}
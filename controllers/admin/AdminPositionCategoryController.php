<?php

require_once _PS_MODULE_DIR_.'categorieshome/classes/CategoriesPosition.php';

class AdminPositionCategoryController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = CategoriesPosition::$definition['table'];
        $this->className = CategoriesPosition::class;
        $this->module = Module::getInstanceByName('categorieshome');
        $this->identifier = CategoriesPosition::$definition['primary'];
        $this->_orderBy = CategoriesPosition::$definition['primary'];
        $this->bootstrap = true;

        parent::__construct();

        $this->fields_list = [
            'categorie_name' => [
                'title' => 'Name',
                'search' => true,
            ],
            'position' => [
                'title' => 'Position',
                'search' => true,
            ]
        ];

        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->addRowAction('view');

    }

    public function renderForm()
    {
        $categories = Category::getAllCategoriesName();

        $category_list = array();

        foreach ($categories as $category) {
            $category_list[] = [
                'id' => $category['name'],
                'name' => $category['name'],
            ];
        }

        $this->fields_form = [
            'legend' => [
                'title' => $this->l('My config')
            ],
            'input' => [
                [
                    'type' => 'select',
                    'label' => $this->l('Choose your category'),
                    'name' => 'categorie_name',
                    'required' => true,
                    'options' => [
                        'query' => $category_list,
                        'id' => 'id',
                        'name' => 'name'
                    ]
                ],
                [
                    'type' => 'text',
                    'label' => 'Position',
                    'name' => 'position',
                    'required' => true
                ]
            ],
            'submit' => [
                'title' => 'Save',
                'class' => 'btn btn-primary'
            ]
        ];



        return parent::renderForm();
    }
}
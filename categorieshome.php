<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_.'categorieshome/classes/CategoriesPosition.php';

class CategoriesHome extends Module
{
    public function __construct()
    {
        $this->name = 'categorieshome';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Doryan Fourrichon';
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_
        ];
        

        parent::__construct();
        $this->bootstrap = true;

        $this->displayName = $this->l('Category Home');
        $this->description = $this->l('Module qui permet d\'afficher les catégories choisis sur la page d\'accueil');

        $this->confirmUninstall = $this->l('Do you want to delete this module');

    }

    public function install()
    {
        if (!parent::install() ||
        !Configuration::updateValue('SHOWIMAGE', 0) ||
        !Configuration::updateValue('SHOWCATEGORYNAME', 0) ||
        !Configuration::updateValue('SHOWCATEGORYDESCRIPTION', 0) ||
        !Configuration::updateValue('IMAGE_SIZE', '') ||
        !Configuration::updateValue('LARGE_DEVICE', 3) ||
        !Configuration::updateValue('MEDIUM_DEVICE', 4) ||
        !Configuration::updateValue('SMALL_DEVICE', 12) ||
        !Configuration::updateValue('POSITIONCATEGORIE', '') ||
        !$this->createTable() ||
        !$this->installTab('AdminPositionCategory','Position Catégorie', 'AdminParentThemes')
        ) {
            return false;
        }
            return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
        !Configuration::deleteByName('SHOWIMAGE') ||
        !Configuration::deleteByName('SHOWCATEGORYNAME') ||
        !Configuration::deleteByName('SHOWCATEGORYDESCRIPTION') ||
        !Configuration::deleteByName('IMAGE_SIZE') ||
        !Configuration::deleteByName('LARGE_DEVICE') ||
        !Configuration::deleteByName('MEDIUM_DEVICE') ||
        !Configuration::deleteByName('SMALL_DEVICE') ||
        !Configuration::deleteByName('POSITIONCATEGORIE') ||
        !$this->deleteTable() ||
        !$this->uninstallTab()
        ) {
            return false;
        }
            return true;
    }

    public function getContent()
    {

        return $this->postProcess().$this->renderForm();
    }

    public function renderForm()
    {
        $imgs = ImageType::getImagesTypes();

        $imgs_list = array();
        
        foreach ($imgs as $img) {
            $imgs_list[] = array(
                'id' => $img['id_image_type'],
                'name' => $img['name']
            );
        }

        $field_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('Settings'),
            ],
            'input' => [
                [
                    'type' => 'switch',
                        'label' => $this->l('Display image category'),
                        'name' => 'SHOWIMAGE',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'label2_on',
                                'value' => 1,
                                'label' => $this->l('Oui')
                            ),
                            array(
                                'id' => 'label2_off',
                                'value' => 0,
                                'label' => $this->l('Non')
                            )
                        )
                ],
                [
                    'type' => 'switch',
                        'label' => $this->l('Display name category'),
                        'name' => 'SHOWCATEGORYNAME',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'label2_on',
                                'value' => 1,
                                'label' => $this->l('Oui')
                            ),
                            array(
                                'id' => 'label2_off',
                                'value' => 0,
                                'label' => $this->l('Non')
                            )
                        )
                ],
                [
                    'type' => 'switch',
                        'label' => $this->l('Display description category'),
                        'name' => 'SHOWCATEGORYDESCRIPTION',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'label2_on',
                                'value' => 1,
                                'label' => $this->l('Oui')
                            ),
                            array(
                                'id' => 'label2_off',
                                'value' => 0,
                                'label' => $this->l('Non')
                            )
                        )
                ],
                [
                    'type' => 'select',
                    'label' => $this->l('Choose image size'),
                    'name' => 'IMAGE_SIZE',
                    'options' => [
                        'query' => $imgs_list,
                        'id' => 'id',
                        'name' => 'name'
                    ]
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Number of column in large device'),
                    'name' => 'LARGE_DEVICE',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Number of column in medium device'),
                    'name' => 'MEDIUM_DEVICE'
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Number of column in small device'),
                    'name' => 'SMALL_DEVICE'
                ]
            ],
            'submit' => [
                'title' => $this->l('save'),
                'class' => 'btn btn-primary',
                'name' => 'saving'
            ]
        ];

        $helper = new HelperForm();
        $helper->module  = $this;
        $helper->name_controller = $this->name;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->fields_value['SHOWIMAGE'] = Configuration::get('SHOWIMAGE');
        $helper->fields_value['SHOWCATEGORYNAME'] = Configuration::get('SHOWCATEGORYNAME');
        $helper->fields_value['SHOWCATEGORYDESCRIPTION'] = Configuration::get('SHOWCATEGORYDESCRIPTION');
        $helper->fields_value['IMAGE_SIZE'] = Configuration::get('IMAGE_SIZE');
        $helper->fields_value['LARGE_DEVICE'] = Configuration::get('LARGE_DEVICE');
        $helper->fields_value['MEDIUM_DEVICE'] = Configuration::get('MEDIUM_DEVICE');
        $helper->fields_value['SMALL_DEVICE'] = Configuration::get('SMALL_DEVICE');
        // $helper->fields_value['POSITIONCATEGORIE'] = Configuration::get('POSITIONCATEGORIE');
        return $helper->generateForm($field_form);
    }

    public function postProcess()
    {
        if (Tools::isSubmit('saving')) {
            if ( Validate::isBool(Tools::getValue('SHOWIMAGE')) || Validate::isBool(Tools::getValue('SHOWCATEGORYNAME')) || 
            Validate::isBool(Tools::getValue('SHOWCATEGORYDESCRIPTION')) || Validate::isInt(Tools::getValue('LARGE_DEVICE')) ||
            Validate::isInt(Tools::getValue('MEDIUM_DEVICE')) || Validate::isInt(Tools::getValue('SMALL_DEVICE'))
            ) {
                Configuration::updateValue('SHOWIMAGE',Tools::getValue('SHOWIMAGE'));
                Configuration::updateValue('SHOWCATEGORYNAME',Tools::getValue('SHOWCATEGORYNAME'));
                Configuration::updateValue('SHOWCATEGORYDESCRIPTION',Tools::getValue('SHOWCATEGORYDESCRIPTION'));
                Configuration::updateValue('IMAGE_SIZE',Tools::getValue('IMAGE_SIZE'));
                Configuration::updateValue('LARGE_DEVICE',Tools::getValue('LARGE_DEVICE'));
                Configuration::updateValue('MEDIUM_DEVICE',Tools::getValue('MEDIUM_DEVICE'));
                Configuration::updateValue('SMALL_DEVICE',Tools::getValue('SMALL_DEVICE'));

                return $this->displayConfirmation('Bien enregistré !');
            }
        }
    }


    public function installTab($className, $tabName, $tabParentName = false)
    {
        // ajouter un lien vers le controller d'admin
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = $className;
        $tab->name = array();

        foreach(Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $tabName;
        }

        if($tabParentName){

            $tab->id_parent = Tab::getIdFromClassName($tabParentName);
        } else{
            $tab->id_parent =  10;
        }

        $tab->module = $this->name;

        return $tab->add();
    }

    public function uninstallTab()
    {
        $idTab = Tab::getIdFromClassName('AdminParametre');
        $tab =  new Tab($idTab);
        $tab->delete();
    }

    public function createTable()
    {
        return DB::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'categoriesposition(
                id_categoriesposition INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
                categorie_name VARCHAR(255) NOT NULL,
                position INT NOT NULL
            )'
        );
    }

    public function deleteTable()
    {
        return Db::getInstance()->execute(
            'DROP TABLE IF EXISTS '._DB_PREFIX_.'categoriesposition'
        );
    }

    public function hookDisplayFooter($params)
    {
        // récupération du width et du height par rapport au select image de configuration
        $sql = 'SELECT width, height FROM '._DB_PREFIX_.'image_type WHERE id_image_type = '.Configuration::get('IMAGE_SIZE');
        $result = DB::getInstance()->executeS($sql);
        //

        //récupération description category

        $tabCateg = array();
        $link = new Link();

        $categoriesPosition = CategoriesPosition::getCategByPosition();

        foreach ($categoriesPosition as $categoryPosition) {
            $categorys = Category::searchByName($this->context->language, $categoryPosition['categorie_name']);

            $contentCategory = new Category((int)$categorys[0]['id_category']);
            $descriptionClean = Category::getDescriptionClean($contentCategory->description[$this->context->language->id]);
            $categImage = $link->getCatImageLink($contentCategory->name,$contentCategory->id_category);
            $url = $link->getCategoryLink($contentCategory);


            $tabCateg[] = array(
                'name' => $contentCategory->name[$this->context->language->id],
                'description' => $descriptionClean,
                'image' => Tools::getShopProtocol().$categImage,
                'url' => $url

            );
        }
        //

        $this->smarty->assign(
            array(
                'width' => $result[0]['width'],
                'height' => $result[0]['height'],
                'tabCateg' => $tabCateg,
                'displayImage' => Configuration::get('SHOWIMAGE'),
                'displayTitle' => Configuration::get('SHOWCATEGORYNAME'),
                'displayDescription' => Configuration::get('SHOWCATEGORYDESCRIPTION'),
                'large_device' => Configuration::get('LARGE_DEVICE'),
                'medium_device' => Configuration::get('MEDIUM_DEVICE'),
                'small_device' => Configuration::get('SMALL_DEVICE'),
            )
        );


        $this->context->controller->registerStylesheet('css-categoryhome','modules/categorieshome/views/css/style.css');
        
        return $this->display(__FILE__, '/views/templates/hook/blockCategorie.tpl');
    }

    public function hookhookDisplayFooterBefore($params)
    {
        // récupération du width et du height par rapport au select image de configuration
        $sql = 'SELECT width, height FROM '._DB_PREFIX_.'image_type WHERE id_image_type = '.Configuration::get('IMAGE_SIZE');
        $result = DB::getInstance()->executeS($sql);
        //

        //récupération description category

        $tabCateg = array();
        $link = new Link();

        $categoriesPosition = CategoriesPosition::getCategByPosition();

        foreach ($categoriesPosition as $categoryPosition) {
            $categorys = Category::searchByName($this->context->language, $categoryPosition['categorie_name']);

            $contentCategory = new Category((int)$categorys[0]['id_category']);
            $descriptionClean = Category::getDescriptionClean($contentCategory->description[$this->context->language->id]);
            $categImage = $link->getCatImageLink($contentCategory->name,$contentCategory->id_category);
            $url = $link->getCategoryLink($contentCategory);


            $tabCateg[] = array(
                'name' => $contentCategory->name[$this->context->language->id],
                'description' => $descriptionClean,
                'image' => Tools::getShopProtocol().$categImage,
                'url' => $url

            );
        }
        //

        $this->smarty->assign(
            array(
                'width' => $result[0]['width'],
                'height' => $result[0]['height'],
                'tabCateg' => $tabCateg,
                'displayImage' => Configuration::get('SHOWIMAGE'),
                'displayTitle' => Configuration::get('SHOWCATEGORYNAME'),
                'displayDescription' => Configuration::get('SHOWCATEGORYDESCRIPTION'),
                'large_device' => Configuration::get('LARGE_DEVICE'),
                'medium_device' => Configuration::get('MEDIUM_DEVICE'),
                'small_device' => Configuration::get('SMALL_DEVICE'),
            )
        );


        $this->context->controller->registerStylesheet('css-categoryhome','modules/categorieshome/views/css/style.css');
        
        return $this->display(__FILE__, '/views/templates/hook/blockCategorie.tpl');
    }

    public function hookDisplayHome($params)
    {
        // récupération du width et du height par rapport au select image de configuration
        $sql = 'SELECT width, height FROM '._DB_PREFIX_.'image_type WHERE id_image_type = '.Configuration::get('IMAGE_SIZE');
        $result = DB::getInstance()->executeS($sql);
        //

        //récupération description category

        $tabCateg = array();
        $link = new Link();

        $categoriesPosition = CategoriesPosition::getCategByPosition();

        foreach ($categoriesPosition as $categoryPosition) {
            $categorys = Category::searchByName($this->context->language, $categoryPosition['categorie_name']);

            $contentCategory = new Category((int)$categorys[0]['id_category']);
            $descriptionClean = Category::getDescriptionClean($contentCategory->description[$this->context->language->id]);
            $categImage = $link->getCatImageLink($contentCategory->name,$contentCategory->id_category);
            $url = $link->getCategoryLink($contentCategory);


            $tabCateg[] = array(
                'name' => $contentCategory->name[$this->context->language->id],
                'description' => $descriptionClean,
                'image' => Tools::getShopProtocol().$categImage,
                'url' => $url

            );
        }
        //

        $this->smarty->assign(
            array(
                'width' => $result[0]['width'],
                'height' => $result[0]['height'],
                'tabCateg' => $tabCateg,
                'displayImage' => Configuration::get('SHOWIMAGE'),
                'displayTitle' => Configuration::get('SHOWCATEGORYNAME'),
                'displayDescription' => Configuration::get('SHOWCATEGORYDESCRIPTION'),
                'large_device' => Configuration::get('LARGE_DEVICE'),
                'medium_device' => Configuration::get('MEDIUM_DEVICE'),
                'small_device' => Configuration::get('SMALL_DEVICE'),
            )
        );


        $this->context->controller->registerStylesheet('css-categoryhome','modules/categorieshome/views/css/style.css');
        
        return $this->display(__FILE__, '/views/templates/hook/blockCategorie.tpl');
    }

    public function hookDisplayContentWrapperTop($params)
    {
        // récupération du width et du height par rapport au select image de configuration
        $sql = 'SELECT width, height FROM '._DB_PREFIX_.'image_type WHERE id_image_type = '.Configuration::get('IMAGE_SIZE');
        $result = DB::getInstance()->executeS($sql);
        //

        //récupération description category

        $tabCateg = array();
        $link = new Link();

        $categoriesPosition = CategoriesPosition::getCategByPosition();

        foreach ($categoriesPosition as $categoryPosition) {
            $categorys = Category::searchByName($this->context->language, $categoryPosition['categorie_name']);

            $contentCategory = new Category((int)$categorys[0]['id_category']);
            $descriptionClean = Category::getDescriptionClean($contentCategory->description[$this->context->language->id]);
            $categImage = $link->getCatImageLink($contentCategory->name,$contentCategory->id_category);
            $url = $link->getCategoryLink($contentCategory);


            $tabCateg[] = array(
                'name' => $contentCategory->name[$this->context->language->id],
                'description' => $descriptionClean,
                'image' => Tools::getShopProtocol().$categImage,
                'url' => $url

            );
        }
        //

        $this->smarty->assign(
            array(
                'width' => $result[0]['width'],
                'height' => $result[0]['height'],
                'tabCateg' => $tabCateg,
                'displayImage' => Configuration::get('SHOWIMAGE'),
                'displayTitle' => Configuration::get('SHOWCATEGORYNAME'),
                'displayDescription' => Configuration::get('SHOWCATEGORYDESCRIPTION'),
                'large_device' => Configuration::get('LARGE_DEVICE'),
                'medium_device' => Configuration::get('MEDIUM_DEVICE'),
                'small_device' => Configuration::get('SMALL_DEVICE'),
            )
        );


        $this->context->controller->registerStylesheet('css-categoryhome','modules/categorieshome/views/css/style.css');
        
        return $this->display(__FILE__, '/views/templates/hook/blockCategorie.tpl');
    }
}
<?php

namespace app\models;

use yii\db\ActiveRecord;

class Category extends ActiveRecord
{

    public static function tableName()
    {
        return 'categories';
    }

    public static function getCategories()
    {
        return self::find()
            ->indexBy('id')
            ->all();
    }
    
    public static function getCategoriesList()
    {
        $arr = static::getCategoriesByHierarchy();
        $res = [];
        foreach ($arr as $id => $data) {
            $res[$id] = str_repeat('-', $data['indent']) . $data['category']->name;
        }
        return $res;
    }

    public static function getCategoriesByHierarchy()
    {
        $tree = self::getTree();
        if (!is_null($tree)) {
            $res = array();
            self::_treeToArray($tree, $res, 0);
            return $res;
        }
        return null;
    }

    public static function getTree()
    {
        $arr = self::find()
            ->indexBy('id')
            ->orderBy(['parent' => 'ASC', 'name' => 'ASC'])
            ->all();

        if (!is_null($arr)) {

            $struct_arr = array();

            foreach ($arr as $id => $category) {
                $struct_arr[!$category->parent ? 0 : $category->parent][$id]['category'] = $category;
            }

            $res_arr = $struct_arr[0];
            unset($struct_arr[0]);

            self::_structuringCategory($res_arr, $struct_arr);

            return $res_arr;
        }

        return null;
    }

    public function getSubcategories()
    {
        $arr = static::find()
            ->indexBy('id')
            ->where(['parent' => $this->id])
            ->orderBy(['name' => 'ASC'])
            ->all();

        $res_arr = [];

        foreach ($arr as $id => $category) {
            $res_arr[$id]['category'] = $category;
        }

        return $res_arr;
    }

    protected static function _structuringCategory(array &$parents_arr, array $childs_arr)
    {
        foreach ($parents_arr as $pid => &$parent) {
            if (isset($childs_arr[$pid])) {
                foreach ($childs_arr[$pid] as $cid => $child) {
                    $parent['children'][$cid] = $child;
                    self::_structuringCategory($parent['children'], $childs_arr);
                }
            }
        }
    }

    protected static function _treeToArray(array $tree, array &$arr, $indent)
    {
        foreach ($tree as $id => $leaf) {
            $arr[$id] = [
                'category' => $leaf['category'],
                'indent' => $indent,
            ];
            if (isset($leaf['children'])) {
                self::_treeToArray($leaf['children'], $arr, $indent + 1);
            }
        }
    }

    public function attributes()
    {
        return ['id', 'name', 'parent'];
    }

    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'name' => \Yii::t('app', 'Название'),
            'parent' => \Yii::t('app', 'Родительская категория'),
        ];
    }

    public function rules()
    {
        return [
            ['name', 'required'],
            ['parent', 'number'],
            ['parent', 'validateParent'],
            ['parent', 'default'], // set "parent" as null if it is empty
        ];
    }

    public function getBooks()
    {
        return $this->hasMany(Book::className(), ['category_id' => 'id']);
    }

    public function getBooksCount()
    {
        return $this->getBooks()->count();
    }

    public function validateParent($attribute, $params)
    {
        //Check if Category with given ID exists
        if (is_null($parent = static::findOne($this->$attribute))) {
            $this->addError($attribute, 'Указанной родительской категории не существует!');
            return;
        }

        //Check if Parent is not one of children of current Category
        $pid = $parent->parent;
        while ($pid) {
            if ($pid == $this->id) {
                $this->addError($attribute, \Yii::t('app', 'Вы не можете задать потомка в качестве родительской категории!'));
                return;
            }
            $pid = static::findOne($pid)->parent;
        }
    }

    public function delete()
    {
        //Remove all subcategories if they are
        $children = static::find()
            ->where(['parent' => $this->id])
            ->all();

        foreach ($children as $child) {
            $child->delete();
        }

        return parent::delete();
    }

}
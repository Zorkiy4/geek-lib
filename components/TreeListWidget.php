<?php

namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class TreeListWidget extends Widget
{
    public $linked;
    public $tree;
    public $count;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        if(isset($this->tree)) {
            $this->generateList($this->tree);
        }
    }
    
    protected function generateList(array $subtree) {
        if(is_array($subtree) && count($subtree) > 0) {
            echo '<ul>';
            foreach ($subtree as $id => $data) {
                echo '<li>';
                if($this->linked) {
                    echo Html::a(
                        Html::encode($data['category']->name),
                        Url::toRoute(['site/view-category', 'id' => $data['category']->id])
                    );
                }
                else {
                    echo Html::encode($data['category']->name);
                }
                if($this->count) {
                    echo ' (' . $data['category']->booksCount . ')';
                }
                if(isset($data['children'])) {
                    $this->generateList($data['children']);
                }
                echo '</li>';
            }
            echo '</ul>';
        }
    }
}
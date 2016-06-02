<?php

namespace app\controllers;

use app\models\Book;
use app\models\Category;
use yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

class AdminController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionCategories()
    {

        if (Yii::$app->request->isPost) {
            $category = new Category();
            $category->load(Yii::$app->request->post());
            $category->save();
        }

        $categories = Category::getCategoriesByHierarchy();
        return $this->render('categories', ['categories' => $categories]);
    }

    public function actionEditCategory($id)
    {
        $category = Category::findOne($id);

        if (is_null($category)) {
            throw new NotFoundHttpException(\Yii::t('app', 'Категория не найдена.'));
        }

        if (Yii::$app->request->isPost) {
            $category->load(Yii::$app->request->post());
            $category->save();
            return $this->redirect(Url::to(['admin/categories']));
        }

        return $this->render('edit-category', ['category' => $category]);
    }

    public function actionDelCategory($id)
    {
        $category = Category::findOne($id);

        if (is_null($category)) {
            throw new NotFoundHttpException(\Yii::t('app', 'Категория не найдена.'));
        }

        if (Yii::$app->request->isPost) {
            $category->delete();
            return $this->redirect(Url::to(['admin/categories']));
        }
        return $this->render('del-category', ['name' => $category->name]);
    }

    public function actionBooks()
    {
        $books = Book::getBooks();

        $dataProvider = new ActiveDataProvider([
            'query' => Book::find(),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        return $this->render('books', ['books' => $books, 'dataProvider' => $dataProvider]);
    }

    public function actionAddBook()
    {
        $book = new Book(['scenario' => Book::SCENARIO_CREATE]);

        if (Yii::$app->request->isPost) {
            $book->load(Yii::$app->request->post());
            $book->cover = UploadedFile::getInstance($book, 'cover');
            $book->file = UploadedFile::getInstance($book, 'file');

            if ($book->validate() && $book->upload()) {
                $book->save(false);
                return $this->redirect(Url::to(['admin/books']));
            }
        }

        return $this->render('add-book', ['book' => $book]);
    }

    public function actionEditBook($id)
    {
        $book = Book::findOne($id);
        $book->scenario = Book::SCENARIO_UPDATE;

        if (is_null($book)) {
            throw new NotFoundHttpException(\Yii::t('app', 'Книга не найдена.'));
        }

        if (Yii::$app->request->isPost) {
            $book->load(Yii::$app->request->post());
            $book->cover = UploadedFile::getInstance($book, 'cover');
            $book->file = UploadedFile::getInstance($book, 'file');

            if ($book->validate() && $book->upload()) {
                $book->save(false);
                return $this->redirect(Url::to(['admin/books']));
            }
        }

        return $this->render('edit-book', ['book' => $book]);
    }

    public function actionDelBook($id)
    {
        $book = Book::findOne($id);

        if (is_null($book)) {
            throw new NotFoundHttpException(\Yii::t('app', 'Книга не найдена.'));
        }

        if (Yii::$app->request->isPost) {
            $book->delete();
        }
        
        return $this->redirect(Url::to(['admin/books']));
    }
    
    public function actionMoveBooks()
    {
        if (Yii::$app->request->isPost) {
            $category_id = Yii::$app->request->post('category');
            $book_ids = Yii::$app->request->post('selection');
            if($category_id && is_array($book_ids)) {
                $books = Book::findAll(array_values($book_ids));
                foreach ($books as $book) {
                    $book->category_id = $category_id;
                    if($book->validate()) {
                        $book->save();
                    }
                }
            }
        }

        return $this->redirect(Url::to(['admin/books']));
    }
}
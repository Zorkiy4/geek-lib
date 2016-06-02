<?php

namespace app\controllers;

use app\models\Book;
use app\models\Category;
use app\models\LoginForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $query = Book::find();

        if (Yii::$app->request->isGet) {
            $idx = trim(Yii::$app->request->get('idx'));
            if (!empty($idx)) {
                $query = $query->where(['like', 'title', [$idx . '%', mb_strtolower($idx) . '%'], false]);
            }
        }

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 6,
            ],
            'sort' => [
                'defaultOrder' => [
                    'title' => SORT_ASC,
                ]
            ],
        ]);

        $categories_tree = Category::getTree();

        return $this->render('index', ['books_provider' => $provider, 'categories_tree' => $categories_tree, 'idx' => isset($idx) ? $idx : '']);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionViewBook($id)
    {
        $book = Book::findOne($id);

        if (is_null($book)) {
            throw new NotFoundHttpException(\Yii::t('app', 'Книга не найдена.'));
        }

        return $this->render('view-book', ['book' => $book]);
    }

    public function actionDownloadBook($id)
    {
        $book = Book::findOne($id);

        if (is_null($book)) {
            throw new NotFoundHttpException(\Yii::t('app', 'Книга не найдена.'));
        }

        Yii::$app->mailer->compose()
            ->setFrom('mail@geeklib.ru')
            ->setTo(\Yii::$app->params['adminEmail'])
            ->setSubject(\Yii::t('app', 'Скачана книга'))
            ->setTextBody(\Yii::t('app', 'С сайта Geek Lib была скачана книга {title}', ['title' => $book->title]))
            ->send();

        return \Yii::$app->response->sendFile(Url::to('uploads/' . $book->file));
    }

    public function actionViewCategory($id)
    {
        $query = Category::findOne($id)->getBooks();

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 6,
            ],
            'sort' => [
                'defaultOrder' => [
                    'title' => SORT_ASC,
                ]
            ],
        ]);

        return $this->render('view-category', ['category' => Category::findOne($id), 'books_provider' => $provider]);
    }

    public function actionSearchResults()
    {
        $provider = null;

        if (Yii::$app->request->isGet) {
            $search = trim(Yii::$app->request->get('search-text'));

            if (!empty($search)) {
                $query = Book::find()
                    ->where(['like', 'title', $search]);

                $provider = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                    'sort' => [
                        'defaultOrder' => [
                            'title' => SORT_ASC,
                        ]
                    ],
                ]);
            }
        }

        return $this->render('search-results', ['books_provider' => $provider]);
    }
}

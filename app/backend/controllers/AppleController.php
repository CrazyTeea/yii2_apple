<?php

namespace backend\controllers;

use backend\models\AddApplesForm;
use backend\models\Apple;
use backend\models\EatApplesForm;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * AppleController implements the CRUD actions for Apple model.
 */
class AppleController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    protected function getApplesProvidxer(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Apple::allActive(),
            'pagination' => [
                'pageSize' => 5
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],

        ]);
    }

    /**
     * Lists all Apple models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index', [
            'dataProvider' => $this->getApplesProvidxer(),
            'model' => new AddApplesForm(),
        ]);
    }

    /**
     * @throws Exception
     */
    public function actionAddApples(): Response|string
    {
        $model = new AddApplesForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $errors = [];
            for ($i = 0; $i < $model->amount; $i++) {
                $apple = new Apple();
                $apple->setColor();
                if (!$apple->save()) {
                    $errors[] = $apple->errors;
                }
            }
            if (!empty($errors)) {
                Yii::$app->session->setFlash('error', $errors);
            } else {
                Yii::$app->session->setFlash('success', 'elements created successful');
            }

            if (Yii::$app->request->isAjax) {

                return $this->renderAjax('index', [
                    'dataProvider' => $this->getApplesProvidxer(),
                    'model' => new AddApplesForm(),
                ]);
            }


        }
        return $this->redirect(['index']);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionFall($id): string
    {
        $model = $this->findModel($id);
        $model->fall();
        Yii::$app->session->setFlash('success', 'fall successful');
        return $this->renderAjax('index', [
            'dataProvider' => $this->getApplesProvidxer(),
            'model' => new AddApplesForm(),
        ]);
    }

    public function actionEatForm($id): string
    {
        return $this->renderAjax('_eat_form', ['model' => Apple::findOne($id), 'formModel' => new EatApplesForm()]);
    }

    public function actionEatApple($id): Response|string
    {
        $apple = Apple::findOne($id);
        $model = new EatApplesForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $apple->eat($model->amount);

                if ($apple->save()) {
                    Yii::$app->session->setFlash('success', 'elements eaten successful');
                }

                if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_eat_form', ['model' => $apple, 'formModel' => $model]);
                }

            } catch (Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }


        }
        return $this->redirect(['index']);


    }

    /**
     * Deletes an existing Apple model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->safeDelete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Apple model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Apple the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Apple
    {
        if (($model = Apple::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

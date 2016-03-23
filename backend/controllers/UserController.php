<?php

namespace backend\controllers;

use common\models\Chats;
use common\models\Messages;
use Yii;
use common\models\User;
use backend\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Contacts;
use common\models\ChatUsers;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'set-admin',
                            'set-user',
                            'delete-contact',
                            'delete-chat',
                            'delete-message',
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $id_user_2 = null, $chat_name = null)
    {
        $contacts = Contacts::findAllContacts(Yii::$app->request->get('id'));
        $all_chats = ChatUsers::getAllChat(Yii::$app->request->get('id'));
        if($id_user_2 != null) {
            $messages = Messages::findAllMessageFromUser(Yii::$app->request->get('id_user_2'), Yii::$app->request->get('id'));
            $user[] = User::findById(Yii::$app->request->get('id'));
            $user[] = User::findById(Yii::$app->request->get('id_user_2'));
        }
        if($chat_name != null) {
            $user = ChatUsers::findAllChatUser(Yii::$app->request->get('chat_name'));
            $messages = Messages::findAllMessageChat(Yii::$app->request->get('chat_name'));
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'contacts' => $contacts,
            'all_chats' => $all_chats,
            'messages' => $messages,
            'user' => $user,
        ]);
    }


    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемый пользователь не найден.');
        }
    }

    public function actionSetAdmin()
    {
        $userRole = Yii::$app->authManager->getRole('admin');
        Yii::$app->authManager->assign($userRole, Yii::$app->request->get('id'));
        return $this->redirect(['view', 'id' => Yii::$app->request->get('id')]);
    }

    public function actionSetUser()
    {
        $userRole = Yii::$app->authManager->getRole('admin');
        Yii::$app->authManager->revoke($userRole, Yii::$app->request->get('id'));
        return $this->redirect(['view', 'id' => Yii::$app->request->get('id')]);
    }

    public function actionDeleteContact()
    {
        $contacts = Contacts::findOne([
            'my_id' => Yii::$app->request->get('id2'),
            'contact_id' => Yii::$app->request->get('id'),
        ]);
        if(isset($contacts)) {
            $contacts->delete();
        }
        $contacts = Contacts::findOne([
            'my_id' => Yii::$app->request->get('id'),
            'contact_id' => Yii::$app->request->get('id2'),
        ]);
        if(isset($contacts)) {
            $contacts->delete();
        }
        Messages::deleteAll([
            'from_user' => Yii::$app->request->get('id'),
            'for_user' => Yii::$app->request->get('id2'),
        ]);

        Messages::deleteAll([
            'from_user' => Yii::$app->request->get('id2'),
            'for_user' => Yii::$app->request->get('id'),
        ]);
        return $this->actionView(Yii::$app->request->get('id'));
    }


    public function actionDeleteChat()
    {
        Chats::deleteAll([
            'chat_name' => Yii::$app->request->get('chat_name'),
        ]);
        ChatUsers::deleteAll([
            'chat_name' => Yii::$app->request->get('chat_name'),
        ]);
        Messages::deleteAll([
            'chat_name' => Yii::$app->request->get('chat_name'),
        ]);
        return $this->actionView(Yii::$app->request->get('id'));
    }

    public function actionDeleteMessage()
    {
        Messages::deleteAll([
            'id_message' => Yii::$app->request->get('id_message')
        ]);
        if(Yii::$app->request->get('id_user_2') != null) {
            return $this->redirect(['user/view', 'id' => Yii::$app->request->get('id'), 'id_user_2' => Yii::$app->request->get('id_user_2')]);
        }elseif (Yii::$app->request->get('chat_name') != null) {
            return $this->redirect(['user/view', 'id' => Yii::$app->request->get('id'), 'chat_name' => Yii::$app->request->get('chat_name')]);
        }

    }
}

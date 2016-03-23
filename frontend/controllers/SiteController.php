<?php
namespace frontend\controllers;


use yii\bootstrap\Html;
use common\models\BlackList;
use common\models\Chats;
use common\models\ChatUsers;
use common\models\Messages;
use common\models\User;
use common\models\Contacts;
use frontend\models\ChangeEmailForm;
use frontend\models\ChangePasswordForm;
use frontend\models\SettingForm;
use frontend\models\IndexForm;
use Yii;
use common\models\LoginForm;
use frontend\models\About;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\SqlDataProvider;
use yii\web\UploadedFile;
use yii\helpers\Url;


/**
 * Site controller
 * @var $messages array
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
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

    /**
     * @inheritdoc
     */
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

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->actionLogin();
        } elseif (Yii::$app->user->identity)
        {
            if(Yii::$app->request->get('id'))
            {
                    $user = User::findById(Yii::$app->request->get('id'));
                    $model = new IndexForm();
                    if ($model->load(Yii::$app->request->post()))
                    {
                        if (Yii::$app->request->isPost)
                        {
                            $model->file = UploadedFile::getInstance($model, 'file');
                            if ($model->file && $model->validate())
                            {
                                mkdir($new_dir = 'uploads/' . date("dmYHis") . '/');
                                $model->file->saveAs($file_upload_name = $new_dir . $model->file->baseName . '.' . $model->file->extension);
                            }
                        }
                        $model->sendMessage($file_upload_name);
                        return $this->refresh();
                    }
            } elseif(Yii::$app->request->get('chat'))
            {
                    $user = ChatUsers::findAllChatUser(Yii::$app->request->get('chat'));
                    $model = new IndexForm();
                    if ($model->load(Yii::$app->request->post())) {
                        if (Yii::$app->request->isPost) {
                            $model->file = UploadedFile::getInstance($model, 'file');
                            if ($model->file && $model->validate()) {
                                mkdir($new_dir = 'uploads/' . date("dmYHis") . '/');
                                $model->file->saveAs($file_upload_name = $new_dir . $model->file->baseName . '.' . $model->file->extension);
                            }
                        }
                        $model->sendMessage($file_upload_name, Yii::$app->request->get('chat'));
                        return $this->refresh();
                    }
            }

            return $this->render('index', [
                'model' => $model,
                'user' => $user,
            ]);
        }
    }







    public function actionAjaxMessage()
    {
        if(Yii::$app->request->get('id'))
        {
            $black_list = BlackList::findOne([
                'my_id' => Yii::$app->request->get('id'),
                'blocked_user_id' => Yii::$app->user->identity->id,
            ]);
            if(isset($black_list))
            {
                Yii::$app->session->setFlash('danger', 'Пользователь занес вас в черный список, вы не можете добавить его в контакты и писать ему сообщения.');
                return $this->redirect(['site/index']);
            } else {
                $messages = Messages::findAllMessageFromUser(Yii::$app->request->get('id'), Yii::$app->user->identity->id);
                Contacts::readContact(Yii::$app->user->identity->id, Yii::$app->request->get('id'));
                if($messages == null) {
                    echo '<p class="text-center" style="margin-top: 50px; font-size: 18px">Сообщений нет! Будь первым!</p>';
                }
                $message_for_ajax = null;
                foreach($messages as $message) {
                    $message_for_ajax .= '<li class="';
                    if($message['from_user'] == Yii::$app->user->identity->id) {
                        $message_for_ajax .= 'right';
                    }else{
                        $message_for_ajax .= 'left';
                    }
                    $message_for_ajax .= ' clearfix">';
                    $message_for_ajax .= '<span class="chat-img ';
                    if($message['from_user'] == Yii::$app->user->identity->id) {
                        $message_for_ajax .= 'pull-right';
                    }else{
                        $message_for_ajax .= 'pull-left';
                    }
                    $message_for_ajax .= '">';
                    $message_for_ajax .= '<img src="';
                    $message_for_ajax .= $message['avatar'];
                    $message_for_ajax .= '" alt="User Avatar">';
                    $message_for_ajax .= '</span>';
                    $message_for_ajax .= '<div class="chat-body clearfix">';
                    $message_for_ajax .= '<div class="header">';
                    $message_for_ajax .= '<strong class="primary-font">';
                    $message_for_ajax .= $message['firstname'] . ' (' . $message['username'] . ') ' . $message['lastname'];
                    $message_for_ajax .= '</strong>';
                    $message_for_ajax .= '<small class="pull-right text-muted">';
                    $message_for_ajax .= '<i class="fa fa-clock-o"></i>';
                    $message_for_ajax .= $message['date_time'];
                    $message_for_ajax .= '</small>';
                    $message_for_ajax .= '</div>';
                    $message_for_ajax .= '<p>';
                    if($message['file'] != NULL) {
                        $message_for_ajax .= '<img class = "message-img img-thumbnail" src="';
                        $message_for_ajax .= Url::home(true) . '/' . $message['file'];
                        $message_for_ajax .= '">';
                    }
                    $message_for_ajax .= '<p style="margin-top: 10px">';
                    $message_for_ajax .= Html::encode($message['message']);
                    $message_for_ajax .= '</p>';
                    $message_for_ajax .= '</p>';
                    $message_for_ajax .= '</div>';
                    $message_for_ajax .= '</li>';
                }
                echo $message_for_ajax;
            }
        } elseif(Yii::$app->request->get('chat'))
        {
            if (ChatUsers::isIChat()) {
                $messages = Messages::findAllMessageChat(Yii::$app->request->get('chat'));
                ChatUsers::readChat(Yii::$app->request->get('chat'));
                if ($messages == null) {
                    echo '<p class="text-center" style="margin-top: 50px; font-size: 18px">Сообщений нет! Будь первым!</p>';
                }
                $message_for_ajax = null;
                foreach ($messages as $message) {
                    $message_for_ajax .= '<li class="';
                    if ($message['from_user'] == Yii::$app->user->identity->id) {
                        $message_for_ajax .= ' right';
                    } else {
                        $message_for_ajax .= ' left';
                    }
                    $message_for_ajax .= ' clearfix">';
                    $message_for_ajax .= '<span class="chat-img';
                    if ($message['from_user'] == Yii::$app->user->identity->id) {
                        $message_for_ajax .= ' pull-right';
                    } else {
                        $message_for_ajax .= ' pull-left';
                    }
                    $message_for_ajax .= '">';
                    $message_for_ajax .= '<img src="';
                    $message_for_ajax .= $message['avatar'];
                    $message_for_ajax .= '" alt="User Avatar">';
                    $message_for_ajax .= '</span>';
                    $message_for_ajax .= '<div class="chat-body clearfix">';
                    $message_for_ajax .= '<div class="header">';
                    $message_for_ajax .= '<strong class="primary-font">';
                    $message_for_ajax .= $message['firstname'] . ' (' . $message['username'] . ') ' . $message['lastname'];
                    $message_for_ajax .= '</strong>';
                    $message_for_ajax .= '<small class="pull-right text-muted">';
                    $message_for_ajax .= '<i class="fa fa-clock-o"></i>';
                    $message_for_ajax .= $message['date_time'];
                    $message_for_ajax .= '</small>';
                    $message_for_ajax .= '</div>';
                    $message_for_ajax .= '<p>';
                    if ($message['file'] != NULL) {
                        $message_for_ajax .= '<img class = "message-img img-thumbnail" src="';
                        $message_for_ajax .= Url::home(true) . '/' . $message['file'];
                        $message_for_ajax .= '">';
                    }
                    $message_for_ajax .= '<p style="margin-top: 10px">';
                    $message_for_ajax .= Html::encode($message['message']);
                    $message_for_ajax .= '</p>';
                    $message_for_ajax .= '</p>';
                    $message_for_ajax .= '</div>';
                    $message_for_ajax .= '</li>';
                }
                echo $message_for_ajax;
            } else {
                Yii::$app->session->setFlash('danger', 'Вы не состоите в этом чате.');
                return $this->redirect(['site/index']);
            }
        }
    }






    public function actionAjaxContacts()
    {
        $contacts = Contacts::findAllContacts(Yii::$app->user->identity->id);
        $contacts_for_ajax = null;

        if($contacts == null) {
            $contacts_for_ajax .= '<p style="text-align: center; margin-top: 15px">У вас нет ни одного контакта!<br />Начните с добавления ';
            $contacts_for_ajax .= Html::a('пользователей', ['site/search-contacts']);
            $contacts_for_ajax .= ' к себе в контакты.</p>';
        }else{
            foreach($contacts as $item) {
                $contacts_for_ajax .= '<li class="active bounceInDown">';
                $contacts_for_ajax .= '<a href="'; $contacts_for_ajax .= Url::to(['site/index/', 'id' => $item['id']]);
                $contacts_for_ajax .= '" class="clearfix">'; $contacts_for_ajax .= '<img src="';
                $contacts_for_ajax .= $item['avatar']; $contacts_for_ajax .= '" alt="" class="img-circle">';
                $contacts_for_ajax .= '<div class="friend-name">'; $contacts_for_ajax .= '<strong>';
                $contacts_for_ajax .= $item['firstname'] . ' (' . $item['username'] . ') ' . $item['lastname'];
                $contacts_for_ajax .= '</strong>'; $contacts_for_ajax .= '</div>';
                $contacts_for_ajax .= '<div class="last-message text-muted"></div>';
                $contacts_for_ajax .= '<small class="time text-muted"></small>';
                $contacts_for_ajax .= '<small class="chat-alert label label-danger">';
                if($item['read_message'] == '0') {
                    $contacts_for_ajax .= 'new';
                }
                $contacts_for_ajax .= '</small>'; $contacts_for_ajax .= '</a>'; $contacts_for_ajax .= '</li>';
            }
        }
        echo $contacts_for_ajax;
    }



    public function actionAjaxChats()
    {
        $all_chats = ChatUsers::getAllChat(Yii::$app->user->identity->id);
        $chats_for_ajax = null;
        if($all_chats == null) {
            $chats_for_ajax .= '<p style="text-align: center; margin-top: 15px">У вас нет ни одного чата!<br />';
        }else {
            foreach($all_chats as $item)
            {
                $chats_for_ajax .= '<li class="active bounceInDown">';
                $chats_for_ajax .= '<a href="';
                $chats_for_ajax .= Url::to(['site/index', 'chat' => $item['chat_name']]);
                $chats_for_ajax .= '" class="clearfix">';
                $chats_for_ajax .= '<img src="';
                $chats_for_ajax .= $item['USER'][0]['avatar'];
                $chats_for_ajax .= '" alt="" class="img-circle">';
                $chats_for_ajax .= '<div class="friend-name">';
                $temp = null;
                foreach($item['USER'] as $user)
                {
                    $temp .= $user['username'] . ', ';
                }
                $temp = substr($temp, 0, -2);
                if(strlen($temp) > 31)
                {
                    $temp = substr($temp, 0, 31) . ' . . .';
                }
                $chats_for_ajax .= '<strong>';
                $chats_for_ajax .= $temp;
                $chats_for_ajax .= '</strong>';
                $chats_for_ajax .= '</div>';
                $chats_for_ajax .= '<div class="last-message text-muted"></div>';
                $chats_for_ajax .= '<small class="time text-muted"></small>';
                $chats_for_ajax .= '<small class="chat-alert label label-danger">';
                if($item['read_message'] == '0')
                {
                    $chats_for_ajax .= 'new';
                }
                $chats_for_ajax .= '</small>';
                $chats_for_ajax .= '</a>';
                $chats_for_ajax .= '</li>';
            }
        }
        echo $chats_for_ajax;
    }




    public function actionAddUserChat()
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->actionLogin();
        } elseif (Yii::$app->request->get('chat')) {
            // получаем пользователей данного чата
            $chat_users = ChatUsers::findAllChatUser(Yii::$app->request->get('chat'));
            // получаем заблокированных пользователей
            $blocked_users = BlackList::getBlockedUsers();
            // получаем всех пользователей
            $allUsers = User::findAllUsers();
            // подсчитываем общее количество пунктов
            $totalCount = Yii::$app->db->createCommand('SELECT COUNT(*) FROM User Where validation_email=:status', [':status' => 'confirmed'])->queryScalar();
            $sql = 'SELECT * FROM User Where validation_email=:status';
            $dataProvider = new SqlDataProvider([
                'sql' => $sql,
                'params' => [':status' => 'confirmed'],
                'totalCount' => (int)$totalCount,
                'pagination' => [
                    'pageSize' => 10,
                ],
                'sort' => [
                    'attributes' => [
                        'id',
                        'username',
                        'lastname',
                        'firstname',
                        'email',
                    ],
                ],
            ]);
        }

            $contacts = Contacts::findAllContacts(Yii::$app->user->identity->id);
        $all_chats = ChatUsers::getAllChat(Yii::$app->user->identity->id);
            return $this->render('addUserChat', [
                'all_chats' => $all_chats,
                'contacts' => $contacts,
                'chat_users' => $chat_users,
                'dataProvider' => $dataProvider,
                'blocked_users' => $blocked_users,
                'allUsers' =>$allUsers,
            ]);

    }




    public function actionGreatNewChat()
    {
        $chat_name = Yii::$app->security->generateRandomString(15) . '_' . date("dmYHis");
        Chats::saveChat($chat_name);
        ChatUsers::saveUser($chat_name, Yii::$app->user->identity->id);
        ChatUsers::saveUser($chat_name, Yii::$app->request->get('id'));
        return $this->redirect(['site/add-user-chat', 'chat' => $chat_name]);
    }




    public function actionAddToChat()
    {
        if(ChatUsers::isIChat()) {
            $black_list = BlackList::findOne([
                'my_id' => Yii::$app->request->get('id'),
                'blocked_user_id' => Yii::$app->user->identity->id,
            ]);
            if (isset($black_list)) {
                Yii::$app->session->setFlash('danger', 'Пользователь занес вас в черный список, вы не можете добавить его в чат.');
                return $this->redirect(['site/add-user-chat', 'chat' => Yii::$app->request->get('chat')]);
            } else {
                ChatUsers::saveUser(Yii::$app->request->get('chat'), Yii::$app->request->get('id'));
                return $this->redirect(['site/add-user-chat', 'chat' => Yii::$app->request->get('chat')]);
            }
        }else {
            Yii::$app->session->setFlash('danger', 'Вы не состоите в этом чате. И не можете добавлять сюда пользователей!');
            return $this->redirect(['site/index']);
        }
    }




    public function actionDeleteToChat()
    {
        if(ChatUsers::isIChat()) {
            ChatUsers::deleteUser(Yii::$app->request->get('chat'), Yii::$app->request->get('id'));
            return $this->redirect(['site/add-user-chat', 'chat' => Yii::$app->request->get('chat')]);
        }else {
            Yii::$app->session->setFlash('danger', 'Вы не состоите в этом чате. И не можете удалять из него пользователей!');
            return $this->redirect(['site/index']);
        }
    }




    public function actionExitChat()
    {
        ChatUsers::exitChat(Yii::$app->request->get('chat'));
        return $this->redirect(['site/index']);

    }



    public function actionDeleteChat()
    {
        if(Chats::isIGreat())
        {
            Chats::findOne([
                'chat_name' => Yii::$app->request->get('chat')
            ])->delete();
            ChatUsers::deleteAll(['chat_name' => Yii::$app->request->get('chat')]);
            Messages::deleteAll(['chat_name' => Yii::$app->request->get('chat')]);
            return $this->redirect(['site/index']);
        }
    }



    public function actionDeleteContact()
    {
        $contacts = Contacts::findOne([
            'my_id' => Yii::$app->user->identity->id,
            'contact_id' => Yii::$app->request->get('id'),
        ]);
        if(isset($contacts))
        {
            $contacts->delete();
        }
        return $this->actionSearchContacts();
    }




    public function actionAddContact()
    {
        $black_list = BlackList::findOne([
            'my_id' => Yii::$app->request->get('id'),
            'blocked_user_id' => Yii::$app->user->identity->id,
        ]);
        if(isset($black_list))
        {
            Yii::$app->session->setFlash('danger', 'Пользователь занес вас в черный список, вы не можете добавить его в контакты и писать ему сообщения.');
            return $this->actionSearchContacts();
        } else
        {
            Contacts::newContact(Yii::$app->user->identity->id, Yii::$app->request->get('id'));
            Contacts::newContact(Yii::$app->request->get('id'), Yii::$app->user->identity->id);
            return $this->actionSearchContacts();
        }
    }



    public function actionAddBlackList ()
    {
        $black_list = BlackList::findOne([
            'my_id' => Yii::$app->user->identity->id,
            'blocked_user_id' => Yii::$app->request->get('id'),
        ]);
        if(!isset($black_list))
        {
            $black_list = new BlackList();
            $black_list->my_id = Yii::$app->user->identity->id;
            $black_list->blocked_user_id = Yii::$app->request->get('id');
            $black_list->save();
        }
        $contact = Contacts::findOne([
            'my_id' => Yii::$app->user->identity->id,
            'contact_id' => Yii::$app->request->get('id'),
        ]);
        if(isset($contact))
        {
            $contact->delete();
        }
        $contact = Contacts::findOne([
            'my_id' => Yii::$app->request->get('id'),
            'contact_id' => Yii::$app->user->identity->id,
        ]);
        if(isset($contact))
        {
            $contact->delete();
        }
        return $this->actionSearchContacts();
    }









    public function actionUnBlocked()
    {
        $un_blocked = BlackList::findOne([
            'my_id' => Yii::$app->user->identity->id,
            'blocked_user_id' => Yii::$app->request->get('id'),
        ]);
        if(isset($un_blocked))
        {
            $un_blocked->delete();
        }
        return $this->actionSearchContacts();
    }




    public function actionSearchContacts()
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->actionLogin();
        } elseif (Yii::$app->user->identity)
        {
            // получаем свои контакты
            $contacts = Contacts::findAllContacts(Yii::$app->user->identity->id);
            // получаем заблокированных пользователей
            $blocked_users = BlackList::getBlockedUsers();
            // получаем всех пользователей
            $allUsers = User::findAllUsers();
            // подсчитываем общее количество пунктов
            $totalCount = Yii::$app->db->createCommand('SELECT COUNT(*) FROM User Where validation_email=:status', [':status' => 'confirmed'])->queryScalar();
            $sql = 'SELECT * FROM User Where validation_email=:status';
            $dataProvider = new SqlDataProvider([
                'sql' => $sql,
                'params' => [':status' => 'confirmed'],
                'totalCount' => (int)$totalCount,
                'sort' => [
                    'attributes' => [
                        'id',
                        'username',
                        'lastname',
                        'firstname',
                        'email',
                    ],
                ],
                'pagination' => [
                    'pageSize' => 10,
                ],

            ]);
            $all_chats = ChatUsers::getAllChat(Yii::$app->user->identity->id);
            return $this->render('searchContacts', [
                'all_chats' => $all_chats,
                'blocked_users' => $blocked_users,
                'contacts' => $contacts,
                'allUsers' => $allUsers,
                'dataProvider' => $dataProvider,
            ]);
        }
    }



    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login())
        {
            return $this->goBack();
        } else
        {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionSetting()
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->actionLogin();
        } elseif (Yii::$app->user->identity)
        {
            $model = new SettingForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate())
            {
                $model->changeSetting();
                return $this->refresh();
            } else
            {
                return $this->render('setting', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        $model = new About();
        if (Yii::$app->request->get())
        {
            $model->getValue();
            if ($model->getUser())
            {
                $model->validationEmail();
            } else {
                Yii::$app->session->setFlash('error', 'Пользователь с таким логином не зарегистрирован. Проверьте правильность ссылки или зарегистрируйтесь.');
            }
        }
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()))
        {
            if ($user = $model->signup())
            {
                if (Yii::$app->getUser()->login($user))
                {
                    return $this->goHome();
                }
            }
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            if ($model->sendEmail())
            {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            } else
            {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }
        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword())
        {
            Yii::$app->session->setFlash('success', 'Новый пароль сохранен.');
            return $this->goHome();
        }
        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }





    public function actionChangePassword()
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->actionLogin();
        } elseif (Yii::$app->user->identity)
        {
            $model = new ChangePasswordForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->changePassword())
            {
                Yii::$app->session->setFlash('success', 'Новый пароль сохранен.');
            }
            return $this->render('changePassword', [
                'model' => $model,
            ]);
        }
    }


    public function actionChangeEmail()
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->actionLogin();
        } elseif (Yii::$app->user->identity)
        {
            $model = new ChangeEmailForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->changeEmail())
            {
                Yii::$app->user->logout();
                return $this->goHome();
            }
            return $this->render('changeEmail', [
                'model' => $model,
            ]);
        }
    }



    public function actionDeleteUser()
    {
        User::findOne(Yii::$app->user->identity->id)->delete();
        Yii::$app->user->logout();
        return $this->goHome();
    }




}

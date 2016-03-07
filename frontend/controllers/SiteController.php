<?php
namespace frontend\controllers;



use common\models\BlackList;
use common\models\Messages;
use common\models\User;
use common\models\Contacts;
use frontend\models\ChangeEmailForm;
use frontend\models\ChangePasswordForm;
use frontend\models\SettingForm;
use common\models\IndexForm;
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
        if (Yii::$app->user->isGuest) {
            return $this->actionLogin();
        }elseif (Yii::$app->user->identity) {

            if(Yii::$app->request->get('id_user')) {


                $black_list = BlackList::findOne([
                    'my_id' => Yii::$app->request->get('id_user'),
                    'blocked_user_id' => Yii::$app->user->identity->id,
                ]);
                if(isset($black_list)) {
                    Yii::$app->session->setFlash('danger', 'Пользователь занес вас в черный список, вы не можете добавить его в контакты и писать ему сообщения.');
                    return $this->actionLogin();
                }else {


                    $messages = Messages::findAllMessageFromUser(Yii::$app->request->get('id_user'));
                    Contacts::readContact(Yii::$app->user->identity->id, Yii::$app->request->get('id_user'));
                    $user = User::findById(Yii::$app->request->get('id_user'));
                    $model = new IndexForm();
                    if ($model->load(Yii::$app->request->post())) {
                        $model->sendMessage();
                        return $this->refresh();
                    }

                }
            }

            $contacts = Contacts::findAllContacts(Yii::$app->user->identity->id);

            return $this->render('index', [
                'model' => $model,
                'contacts' => $contacts,
                'messages' => $messages,
                'user' => $user,
            ]);

        }
    }



    public function actionDeleteContact()
    {
        $contacts = Contacts::findOne([
            'my_id' => Yii::$app->user->identity->id,
            'contact_id' => Yii::$app->request->get('id'),
        ]);
        if(isset($contacts)) {
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
        if(isset($black_list)) {
            Yii::$app->session->setFlash('danger', 'Пользователь занес вас в черный список, вы не можете добавить его в контакты и писать ему сообщения.');
            return $this->actionSearchContacts();
        }else {
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
        if(!isset($black_list)) {
            $black_list = new BlackList();
            $black_list->my_id = Yii::$app->user->identity->id;
            $black_list->blocked_user_id = Yii::$app->request->get('id');
            $black_list->save();
        }
        $contact = Contacts::findOne([
            'my_id' => Yii::$app->user->identity->id,
            'contact_id' => Yii::$app->request->get('id'),
        ]);
        if(isset($contact)) {
            $contact->delete();
        }
        $contact = Contacts::findOne([
            'my_id' => Yii::$app->request->get('id'),
            'contact_id' => Yii::$app->user->identity->id,
        ]);
        if(isset($contact)) {
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
        if(isset($un_blocked)) {
            $un_blocked->delete();
        }
        return $this->actionSearchContacts();
    }




    public function actionSearchContacts()
    {
        if (Yii::$app->user->isGuest) {
            return $this->actionLogin();
        }elseif (Yii::$app->user->identity) {
            // получаем свои контакты
            $contacts = Contacts::findAllContacts(Yii::$app->user->identity->id);

            // получаем заблокированных пользователей
            $blocked_users = BlackList::getBlockedUsers();
            // получаем всех пользователей
            $allUsers = User::findAllUsers();
            // подсчитываем общее количество пунктов



            $totalCount = Yii::$app->db->createCommand('SELECT COUNT(*) FROM Users Where validation_email=:status', [':status' => 'confirmed'])->queryScalar();
            $sql = 'SELECT * FROM Users Where validation_email=:status';
            $dataProvider = new SqlDataProvider([
                'sql' => $sql,
                'params' => [':status' => 'confirmed'],
                'totalCount' => (int)$totalCount,
                'pagination' => [
                    'pageSize' => 10,
                ]
            ]);



            return $this->render('searchContacts', [
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
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
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
        if (Yii::$app->user->isGuest) {
            return $this->actionLogin();
        }elseif (Yii::$app->user->identity) {
            $model = new SettingForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->changeSetting();
                return $this->refresh();
            } else {
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
        if (Yii::$app->request->get()) {
            $model->getValue();
            if ($model->getUser()) {
                $model->validationEmail();
            }else {
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
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
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
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
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

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Новый пароль сохранен.');
            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }





    public function actionChangePassword()
    {
        if (Yii::$app->user->isGuest) {
            return $this->actionLogin();
        }elseif (Yii::$app->user->identity) {
            $model = new ChangePasswordForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->changePassword()) {
                Yii::$app->session->setFlash('success', 'Новый пароль сохранен.');
            }
            return $this->render('changePassword', [
                'model' => $model,
            ]);
        }
    }


    public function actionChangeEmail()
    {
        if (Yii::$app->user->isGuest) {
            return $this->actionLogin();
        }elseif (Yii::$app->user->identity) {
            $model = new ChangeEmailForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->changeEmail()) {
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

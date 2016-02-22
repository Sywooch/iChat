<?php
namespace frontend\controllers;


use frontend\models\Index;
use frontend\models\ChangeEmailForm;
use frontend\models\ChangePasswordForm;
use frontend\models\SettingForm;
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



/**
 * Site controller
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
            return $this->render('index');
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


}

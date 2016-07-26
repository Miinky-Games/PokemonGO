<?php
use App\Entity\Order;

use App\DataBaseService\RepositoryServiceProvider;
use App\PayPalService\PayPalServiceProvider;
use App\MailingService\MailingServiceProvider;

use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Silex\Provider\ValidatorServiceProvider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Class App
 * @property \App\DataBaseService\Repository $repository
 * @property \App\PayPalService\PayPalHandler $payPalHandler
 * @property \App\PayPalService\PayPalApiContext $payPalApiContext
 * @property \App\MailingService\MailBuilder $mailBuilder
 * @property \App\MailingService\MailBodyBuilder $mailBodyBuilder
 * @property \App\MailingService\MailLogger $mailLogger
 * @property \Twig_Environment $twig
 * @property \Symfony\Component\HttpFoundation\Session\Session $session
 * @property \Swift_Mailer $mailer
 * @property \Symfony\Component\Validator\Validator\RecursiveValidator $validator
 */
class App extends Silex\Application
{
    use Silex\Application\UrlGeneratorTrait;
    use Silex\Application\TwigTrait;

    public function __get($name)
    {
        return $this[$name];
    }
}

$app = new App;

$app['debug'] = true;

$app->register(new UrlGeneratorServiceProvider());

$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../views'
));

$app->register(new SessionServiceProvider(), array(
    'session.storage.options' => array(
        'cookie_lifetime' => (1500))
));

$app->register(new SwiftmailerServiceProvider());

$app->register(new ValidatorServiceProvider());

$app->register(new RepositoryServiceProvider());

$app->register(new PayPalServiceProvider());

$app->register(new MailingServiceProvider());

$app->session->start();

$app->get('/', function () use ($app) {
    return $app->twig->render('main.twig');
})->bind('index');

$app->post('/checkout', function (Request $request) use ($app) {
    $email = $request->get('email');
    $accountLogin = $request->get('accountLogin');
    $accountPassword = $request->get('accountPassword');

    $dataToValidate = array(
        'email' => $email,
        'accountLogin' => $accountLogin,
        'accountPassword' => $accountPassword
    );

    $constraints = new Assert\Collection(array(
        'email' => array(
            new Assert\NotBlank(array(
                'message' => 'Email cannot be blank.'
            )),
            new Assert\NotNull(array(
                'message' => 'Unable to obtain your email.'
            )),
            new Assert\Email(array(
                'strict' => true,
                'checkMX' => true,
                'message' => 'The email: {{ value }} is not a valid email.'
            )),
            new Assert\Length(array(
                'max' => 50,
                'maxMessage' => 'Your email cannot be longer than {{ limit }} characters.'
            ))
        ),
        'accountLogin' => array(
            new Assert\NotBlank(array(
                'message' => 'Login cannot be blank.'
            )),
            new Assert\NotNull(array(
                'message' => 'Unable to obtain your login'
            )),
            new Assert\Length(array(
                'min' => 3,
                'max' => 50,
                'minMessage' => "Your login must be at least {{ limit }} characters long.",
                'maxMessage' => "Your login cannot be longer than {{ limit }} characters."
            ))
        ),
        'accountPassword' => array(
            new Assert\NotBlank(array(
                'message' => 'Password cannot be blank.'
            )),
            new Assert\NotNull(array(
                'message' => 'Unable to obtain your password.'
            )),
            new Assert\Length(array(
                'max' => 60,
                'maxMessage' => 'Password cannot be longer than {{ limit }} characters.'
            ))
        )
    ));

    $validationErrors = $app->validator->validate($dataToValidate, $constraints);

    if (count($validationErrors) > 0) {

        $errorsMessages = array();

        foreach ($validationErrors as $validationError) {
            array_push($errorsMessages, $validationError->getMessage() . "\n");
        }
        $response = array(
            'status' => 'error',
            'msg' => $errorsMessages
        );

    } else {
        $order = new Order;
        $app->payPalHandler->CreatePayment($order,$app->url('index'));
        $approvalURL = $app->payPalHandler->GetApprovalUrl($app->payPalApiContext->GetApiContext());

        $response = array(
            'status'=>'success',
            'msg'=>$approvalURL
        );
    }
    return json_encode($response);
})->bind('checkout');

$app->get('/payment', function (Request $request) use ($app) {

    $success = $request->get('success');

    if ($success == 'true') {

    } else {
        $app->session->set('dialog', 'paymentFailure');
    }
});

$app->run();
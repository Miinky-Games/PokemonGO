<?php
namespace App\MailingService;

use Swift_SmtpTransport;
use Swift_Mailer;
use Silex\ServiceProviderInterface;
use Silex\Application;

class MailingServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['mailer'] = $app->share(function () {
            $mailerCredentials = json_decode(file_get_contents('../assets/credentials.json'), true)['Mailer'];
            $smtp = $mailerCredentials['smtp'];
            $port = $mailerCredentials['port'];
            $username = $mailerCredentials['username'];
            $password = $mailerCredentials['password'];

            $transport = Swift_SmtpTransport::newInstance($smtp, $port)
                ->setUsername($username)
                ->setPassword($password);
            return Swift_Mailer::newInstance($transport);
        });
        $app['mailBuilder'] = $app->share(function () {
            return new MailBuilder();
        });
        $app['mailBodyBuilder'] = $app->share(function () {
            return new MailBodyBuilder();
        });
        $app['mailLogger'] = $app->share(function () {
            return new MailLogger();
        });
    }

    public function boot(Application $app)
    {
    }

}
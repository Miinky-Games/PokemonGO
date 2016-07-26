<?php
/**
 * Created by PhpStorm.
 * User: sebas
 * Date: 26.07.2016
 * Time: 19:47
 */

namespace App\DataBaseService;

use Silex\ServiceProviderInterface;
use Silex\Application;

use PDO;

class RepositoryServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['sBoostRepository'] = $app->share(function () use ($app) {
            $dbCredentials = json_decode(file_get_contents('../assets/credentials.json'), true)['Database'];
            $host = $dbCredentials['host'];
            $dbname = $dbCredentials['dbname'];
            $username = $dbCredentials['username'];
            $password = $dbCredentials['password'];
            $pdo = new PDO("mysql:host={$host};dbname={$dbname}", $username, $password);
            return new Repository($pdo);
        });
    }

    public function boot(Application $app)
    {
    }
}

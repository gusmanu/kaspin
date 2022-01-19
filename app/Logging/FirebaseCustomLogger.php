<?php
namespace App\Logging;
use Monolog\Logger;
class FirebaseCustomLogger
{
    /**
     * Create a custom Monolog instance.
     *
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config){
        $logger = new Logger("FirebaseLoggingHandler");
        return $logger->pushHandler(new FirebaseLoggingHandler());
    }
}
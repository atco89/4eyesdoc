<?php
declare(strict_types=1);

namespace App\Repositories\Logger;

use DateTime;
use Slim\Container;

/**
 * Class Logger
 * @package App\Repositories\Logger
 */
class Logger
{

    /** @var string */
    protected $root;
    /** @var Container */
    protected $container;

    /**
     * Logger constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->root = $container->settings['loggerPath'];
        $this->container = $container;
        $this->createFolder();
    }

    /**
     * @return void
     */
    protected function createFolder(): void
    {
        $dirs = scandir($this->root);
        if (!in_array("logs", $dirs))
            mkdir("{$this->root}logs", 0777);
    }

    /**
     * @param string $message
     */
    public function writeLog(string $message): void
    {
        $dateTime = new DateTime;
        $fileName = "{$this->root}logs/logs@{$dateTime->format('Y-m-d')}.log";
        $file = fopen($fileName, 'a+');
        $logContent = "{$dateTime->format('Y-m-d H:i:s')}|$message.\n";
        fwrite($file, $logContent);
        fclose($file);
    }

}
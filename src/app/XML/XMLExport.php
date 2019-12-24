<?php
declare(strict_types=1);

namespace App\XML;

use SimpleXMLElement;
use Slim\Container;

/**
 * Class XMLExport
 * @package App\XML
 */
abstract class XMLExport
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * XMLExport constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return void
     */
    public abstract function run(): void;

    /**
     * @param string $rootName
     * @param string $rowName
     * @param array $data
     */
    protected function render(string $rootName, string $rowName, array $data): void
    {
        $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><$rootName></$rootName>");
        foreach ($data as $name => $value) {
            $rootElement = $xml->addChild($rowName);
            array_walk($value, function ($value, $key) use ($rootElement) {
                $rootElement->addChild(strval($key), strval($value));
            });
        }
        $xml->saveXML('php://output');
    }
}
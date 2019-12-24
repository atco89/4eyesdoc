<?php
declare(strict_types=1);

namespace App\Excel;

use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Slim\Container;

/**
 * Class ExcelExport
 * @package App\Excel
 */
abstract class ExcelExport
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * ExcelExport constructor.
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
     * @param array $header
     * @param array $data
     */
    protected function render(array $header, array $data): void
    {
        $spreadsheet = new Spreadsheet;
        $xls = new Xls($spreadsheet);
        try {
            $spreadsheet->getDefaultStyle()
                ->getFont()
                ->setName('Arial')
                ->setSize(9);
            $spreadsheet->getDefaultStyle()
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0);
            $activeSheet = $spreadsheet->getActiveSheet();
            $activeSheet
                ->fromArray($header, null, 'A1')
                ->fromArray($data, null, 'A2');
            $xls->save('php://output');
        } catch (Exception $e) {
            $this->container->logger->writeLog(__FUNCTION__ . '|' . $e->getMessage());
        }
    }
}

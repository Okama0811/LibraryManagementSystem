<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ExcelExportService
{
    private $spreadsheet;
    private $activeSheet;
    private $translations = [];
    
    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->activeSheet = $this->spreadsheet->getActiveSheet();
    }

    /**
     * Set translations for data values
     * 
     * @param array $translations Associative array of translations
     * @return void
     */
    public function setTranslations(array $translations): void
    {
        $this->translations = $translations;
    }

    /**
     * Get default header style
     * 
     * @return array
     */
    private function getDefaultHeaderStyle(): array
    {
        return [
            'font' => [
                'bold' => true
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'FFFF00'
                ]
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ];
    }

    /**
     * Export data to Excel file
     * 
     * @param array $headers Associative array of column keys and headers
     * @param array $data Array of data to export
     * @param string $filename Output filename
     * @param string|null $translateColumn Column key to translate (optional)
     * @param array|null $customHeaderStyle Custom header style (optional)
     * @return void
     */
    public function export(
        array $headers,
        array $data,
        string $filename,
        ?string $translateColumn = null,
        ?array $customHeaderStyle = null
    ): void {
        // Set column headers
        $headerStyle = $customHeaderStyle ?? $this->getDefaultHeaderStyle();
        
        // Auto-size columns and apply header style
        foreach (range('A', chr(64 + count($headers))) as $col) {
            $this->activeSheet->getColumnDimension($col)->setAutoSize(true);
            $this->activeSheet->getStyle($col . '1')->applyFromArray($headerStyle);
        }

        // Set headers
        $this->activeSheet->fromArray(array_values($headers), NULL, 'A1');

        // Process and transform data if needed
        $processedData = [];
        foreach ($data as $row) {
            if ($translateColumn && isset($row[$translateColumn]) && isset($this->translations[$row[$translateColumn]])) {
                $row[$translateColumn] = $this->translations[$row[$translateColumn]];
            }
            $processedData[] = $row;
        }

        // Add data rows
        $this->activeSheet->fromArray($processedData, NULL, 'A2');

        // Create Excel file
        $writer = new Xlsx($this->spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Clear previous output
        if (ob_get_length()) {
            ob_end_clean();
        }

        // Save to output
        $writer->save('php://output');
        exit;
    }

    /**
     * Export data with custom styling
     * 
     * @param array $config Configuration array containing:
     *                      - headers: Associative array of column keys and headers
     *                      - data: Array of data to export
     *                      - filename: Output filename
     *                      - translations: Array of translations (optional)
     *                      - translateColumn: Column key to translate (optional)
     *                      - headerStyle: Custom header style (optional)
     * @return void
     */
    public function exportWithConfig(array $config): void
    {
        if (isset($config['translations'])) {
            $this->setTranslations($config['translations']);
        }

        $this->export(
            $config['headers'],
            $config['data'],
            $config['filename'],
            $config['translateColumn'] ?? null,
            $config['headerStyle'] ?? null
        );
    }
}
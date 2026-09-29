<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\Admin\ChannelsExport;
use App\Exports\Admin\VideosExport;
use App\Exports\Admin\UsersExport;
use App\Exports\Admin\ReelsExport;
use App\Exports\Admin\CommentsExport;
use App\Exports\Admin\DepositsExport;
use App\Exports\Admin\WithdrawalsExport;
use App\Exports\Admin\PlansExport;
use App\Exports\Admin\BannersExport;
use App\Exports\Admin\MarketplaceExport;
use App\Exports\Admin\PlaylistsExport;
use App\Exports\Admin\KycExport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpWord\PhpWord;

class ExportController extends Controller
{
    private array $exportMap = [
        'channels'    => ChannelsExport::class,
        'videos'      => VideosExport::class,
        'users'       => UsersExport::class,
        'reels'       => ReelsExport::class,
        'comments'    => CommentsExport::class,
        'deposits'    => DepositsExport::class,
        'withdrawals' => WithdrawalsExport::class,
        'plans'       => PlansExport::class,
        'banners'     => BannersExport::class,
        'marketplace' => MarketplaceExport::class,
        'playlists'   => PlaylistsExport::class,
        'kyc'         => KycExport::class,
    ];

    public function export(string $module, string $format, Request $request)
    {
        if (!isset($this->exportMap[$module])) {
            abort(404, "Unknown export module: $module");
        }

        $exportClass = $this->exportMap[$module];
        $exporter = new $exportClass($module);
        $data = $exporter->getData();
        $headers = $exporter->headers();

        if ($data->isEmpty()) {
            return back()->withNotify([['error', 'No entries available to export.']]);
        }

        $rows = $data->map(fn($row) => $exporter->map($row))->toArray();

        $filename = $module . '-' . now()->format('Y-m-d-His');

        return match ($format) {
            'csv'  => $this->exportCsv($filename, $headers, $rows),
            'xlsx' => $this->exportXlsx($filename, $headers, $rows),
            'pdf'  => $this->exportPdf($filename, $headers, $rows),
            'docx' => $this->exportDocx($filename, $headers, $rows),
            default => abort(400, "Unsupported format: $format"),
        };
    }

    private function exportCsv(string $filename, array $headers, array $rows)
    {
        $callback = function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $headers);
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ]);
    }

    private function exportXlsx(string $filename, array $headers, array $rows)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($headers as $colIndex => $header) {
            $coord = [$colIndex + 1, 1];
            $sheet->setCellValue($coord, $header);
            $sheet->getStyle($coord)->getFont()->setBold(true);
        }

        foreach ($rows as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $sheet->setCellValue([$colIndex + 1, $rowIndex + 2], $value);
            }
        }

        foreach (range(1, count($headers)) as $col) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_');
        $writer->save($tempFile);

        return response()->download($tempFile, $filename . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    private function exportPdf(string $filename, array $headers, array $rows)
    {
        $pdf = Pdf::loadView('admin.exports.table', [
            'headers' => $headers,
            'rows' => $rows,
            'title' => ucfirst($filename),
        ]);

        return $pdf->download($filename . '.pdf');
    }

    private function exportDocx(string $filename, array $headers, array $rows)
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addTitle(ucfirst(str_replace('-', ' ', $filename)), 1);

        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
        $table->addRow();
        foreach ($headers as $header) {
            $table->addCell()->addText($header, ['bold' => true]);
        }

        foreach ($rows as $row) {
            $table->addRow();
            foreach ($row as $value) {
                $table->addCell()->addText((string) $value);
            }
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'docx_');
        $phpWord->save($tempFile, 'Word2007');

        return response()->download($tempFile, $filename . '.docx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }
}

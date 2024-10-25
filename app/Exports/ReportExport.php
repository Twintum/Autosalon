<?php
namespace App\Exports;

use App\Models\User;
use App\Models\Order;
use App\Models\CarModel;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        $data = [];

        // Пользователи
        $data[] = ['Пользователи', '', ''];

        $currentMonthUsers = User::where('created_at', '>=', Carbon::now()->subMonth())->count();
        $previousMonthUsers = User::where('created_at', '>=', Carbon::now()->subMonths(2))->where('created_at', '<', Carbon::now()->subMonth())->count();
        $percentageChange = $previousMonthUsers > 0 ? (($currentMonthUsers - $previousMonthUsers) / $previousMonthUsers) * 100 : 0;
        $percentageChange = round($percentageChange, 2); // Округление до двух знаков после запятой

        $data[] = ['За месяц', $currentMonthUsers, $percentageChange > 0 ? "+{$percentageChange}%" : "{$percentageChange}%"];
        $data[] = ['За пол года', User::where('created_at', '>=', Carbon::now()->subMonths(6))->count(), ''];
        $data[] = ['За все время', User::count(), ''];

        // Продажи
        $data[] = ['', '', '']; // Пустая строка для разделения
        $data[] = ['Продажи', '', ''];
        $data[] = ['За месяц', Order::where('status', 'delivered')->where('created_at', '>=', Carbon::now()->subMonth())->count(), ''];
        $data[] = ['За пол года', Order::where('status', 'delivered')->where('created_at', '>=', Carbon::now()->subMonths(6))->count(), ''];
        $data[] = ['За все время', Order::where('status', 'delivered')->count(), ''];

        // Бронирования
        $data[] = ['', '', '']; // Пустая строка для разделения
        $data[] = ['Бронирования', '', ''];
        $data[] = ['Всего бронирований', Order::count(), ''];
        $data[] = ['Отмененных', Order::where('status', 'cancelled')->count(), ''];
        return collect($data);
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:C1');
        $sheet->mergeCells('A6:C6');
        $sheet->mergeCells('A11:C11');
        $sheet->mergeCells('A15:C15');

        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        $sheet->getStyle('A6:C6')->getFont()->setBold(true);
        $sheet->getStyle('A11:C11')->getFont()->setBold(true);
        $sheet->getStyle('A15:C15')->getFont()->setBold(true);

        $sheet->getStyle('A1:C18')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:C18')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Установка автоматического размера для каждого столбца
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
    }
}

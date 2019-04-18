<?php

namespace App\Exports;

use App\Ssl;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SslExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function collection()
    {
        return Ssl::where('user_id',$this->id)->select('domain','expire_at','dayleft','issue_by','send_noti_before','send_noti_after')->orderBy('dayleft', 'ASC')->get();
    }

    public function headings(): array
    {
        return [
            'Domain',
            'Hết hạn vào',
            'Ngày còn lại',
            'Cung cấp bởi',
            'Thông báo trước',
            'Thông báo lại sau'
        ];
    }
}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Domain;

class DomainExportCustom implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(string $exp,int $day)
    {
        $this->exp = $exp;
        $this->day = $day;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Domain::where('user_id',Auth::user()->id)->where('dayleft',$this->exp,$this->day)->select('domain','expire_at','create_at','dayleft','owner','register','send_noti_before','send_noti_after')->orderBy('dayleft', 'ASC')->get();
    }
    public function headings(): array
    {
        return [
            'Domain',
            'Hết hạn vào',
            'Ngày khởi tạo',
            'Ngày còn lại',
            'Chủ sở hữu',
            'Đăng kí bởi',
            'Thông báo trước',
            'Thông báo lại sau'
        ];
    }
}

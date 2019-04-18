<?php

namespace App\Exports;

use App\Domain;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DomainExport implements FromCollection, WithHeadings, ShouldAutoSize
{	
	public function __construct(int $id)
    {
        $this->id = $id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Domain::where('user_id',$this->id)->select('domain','expire_at','create_at','dayleft','owner','register','send_noti_before','send_noti_after')->orderBy('dayleft', 'ASC')->get();
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

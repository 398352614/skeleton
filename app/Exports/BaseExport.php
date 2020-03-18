<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;     // 自动注册事件监听器
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;    // 导出 0 原样显示，不为 null
use Maatwebsite\Excel\Concerns\WithTitle;    // 设置工作䈬名称
use Maatwebsite\Excel\Events\AfterSheet;    // 在工作表流程结束时会引发事件

class BaseExport implements FromArray, WithTitle, WithEvents, WithStrictNullComparison,WithHeadings
{
    protected $data;
    protected $title;
    protected $headings;

    public function __construct($data,$headings,$title)
    {
        $this->data =$data;
        $this->title =$title;
        $this->headings =$headings;
    }

    public function array(): array
    {
        $data = $this->data;//测试数据
        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // 合并单元格
                //$event->sheet->getDelegate()->setMergeCells(['A1:O1', 'A2:C2', 'D2:O2']);
                // 冻结窗格
                //$event->sheet->getDelegate()->freezePane('A1');
                //设置单元格内容自动转行
                $event->sheet->getDelegate()->getStyle('A1:Z100')->getAlignment()->setWrapText(TRUE);
                // 设置单元格内容水平靠右
                $event->sheet->getDelegate()->getStyle('A1:Z100')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                //设置单元格内容垂直居中
                $event->sheet->getDelegate()->getStyle('A1:Z100')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                // 定义列宽度
                $widths =[];
                if($this->title === 'merchant'){
                    $widths = ['A' => 10, 'B' => 10, 'C' => 25,'D'=>15,'E'=>10,'F'=>10,'G'=>20,'H'=>25,'I'=>10];
                }elseif (strstr($this->title,'TOUR')){
                    $widths = ['A' => 5, 'B' => 15, 'C' => 15,'D'=>15,'E'=>25,'F'=>15,'G'=>15,'H'=>10,'I'=>10,'J'=>10,'K'=>15,'L'=>15];
                }
                foreach ($widths as $k => $v) {
                    $event->sheet->getDelegate()->getColumnDimension($k)->setWidth($v);
                }
                // 其他样式需求（设置边框，背景色等）处理扩展中给出的宏，也可以自定义宏来实现，详情见官网说明
            },
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        // 设置工作䈬的名称
        return $this->title;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return $this->headings;
    }
}

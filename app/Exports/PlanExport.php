<?php

namespace App\Exports;

use App\Traits\FactoryInstanceTrait;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

// 自动注册事件监听器
// 导出 0 原样显示，不为 null
// 设置工作䈬名称
// 在工作表流程结束时会引发事件

class PlanExport implements FromArray, WithTitle, WithEvents, WithStrictNullComparison, WithHeadings
{
    use FactoryInstanceTrait;
    protected $data;
    protected $title;
    protected $headings;
    private $type;
    private $params;

    public function __construct($data, $headings, $title, $dir, $params)
    {
        $this->data = $data;
        $this->title = $title;
        $this->headings = $headings;
        $this->type = $dir;
        $this->params = $params;
    }


    /**
     * 表格数据
     * @return array
     */
    public function array(): array
    {
        return $this->data;
    }

    /**
     * 表格工作簿标题
     * @return string
     */
    public function title(): string
    {
        // 设置工作䈬的名称
        return $this->title;
    }

    /**
     * 表头
     * @return array
     */
    public function headings(): array
    {
        return $this->headings;
    }

    /**
     * 格式调整
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $endColumn = $event->sheet->getDelegate()->getHighestColumn();
                $endRow = $event->sheet->getDelegate()->getHighestRow();
                $cell = 'A1:' . $endColumn . $endRow;
                // 合并单元格
                //$event->sheet->getDelegate()->setMergeCells(['A1:'.$endColumn.'1']);
                //设置行高
                /*                for ($i = 2; $i < 100; $i++) {
                                    $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(16);
                                }*/
                //设置单元格内容自动转行
                $event->sheet->getDelegate()->getStyle('A1:G100')->getAlignment()->setWrapText(TRUE);
                // 设置单元格内容水平靠右
                $event->sheet->getDelegate()->getStyle($cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                //设置单元格内容垂直居中
                $event->sheet->getDelegate()->getStyle($cell)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                // 定义列宽度
                for ($i = 0, $j = count($this->headings()[5]); $i <= $j; $i++) {
                    $event->sheet->getDelegate()->getColumnDimensionByColumn($i)->setWidth('15');
                }
                $event->sheet->getDelegate()->getColumnDimensionByColumn(5)->setWidth('20');
                $event->sheet->getDelegate()->getColumnDimensionByColumn(11)->setWidth('20');
                //设置单元格内容自动转行
                $event->sheet->getDelegate()->getStyle($cell)->getAlignment()->setWrapText(TRUE);
                //设置字体大小
                $event->sheet->getDelegate()->getStyle('A1:' . $endColumn . '1')->getFont()->setSize(12);
                //合并
                $sortArray = $this->params['sort'];
                foreach ($sortArray as $k => $v) {
                    $sortArray[$k + 1] = $v;
                }
                $sortArray[0] = 0;
                $data = [];
                for ($i = 0, $j = count($sortArray); $i < $j; $i++) {
                    if (!empty($sortArray[$i + 1])) {
                        $data[$i][0] = $sortArray[$i] + 1 + 6;
                        $data[$i][1] = $sortArray[$i + 1] + 6;
                    }
                }
                foreach ($data as $k => $v) {
                    $event->sheet->getDelegate()->mergeCells('A' . $v[0] . ':' . 'A' . $v[1]);
                    $event->sheet->getDelegate()->mergeCells('B' . $v[0] . ':' . 'B' . $v[1]);
                    $event->sheet->getDelegate()->mergeCells('C' . $v[0] . ':' . 'C' . $v[1]);
                    $event->sheet->getDelegate()->mergeCells('D' . $v[0] . ':' . 'D' . $v[1]);
                    $event->sheet->getDelegate()->mergeCells('E' . $v[0] . ':' . 'E' . $v[1]);
                    $event->sheet->getDelegate()->mergeCells('F' . $v[0] . ':' . 'F' . $v[1]);
                }
            },
        ];
    }
}

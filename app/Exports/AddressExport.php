<?php

namespace App\Exports;

use App\Traits\ConstTranslateTrait;
use App\Traits\CountryTrait;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;


// 自动注册事件监听器
// 导出 0 原样显示，不为 null
// 设置工作䈬名称
// 在工作表流程结束时会引发事件

class AddressExport implements FromArray, WithTitle, WithEvents, WithStrictNullComparison, WithHeadings
{
    protected $data;
    protected $title;
    protected $headings;
    private $type;

    public function __construct($data, $headings, $title, $dir)
    {
        $this->data = $data;
        $this->title = $title;
        $this->headings = $headings;
        $this->type = $dir;
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
                for ($i = 2; $i < 100; $i++) {
                    $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(16);
                }
                //设置单元格内容自动转行
                $event->sheet->getDelegate()->getStyle($cell)->getAlignment()->setWrapText(TRUE);
                // 设置单元格内容水平靠右
                $event->sheet->getDelegate()->getStyle($cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                //设置单元格内容垂直居中
                $event->sheet->getDelegate()->getStyle($cell)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                // 定义列宽度
                for ($i = 0, $j = count($this->headings()); $i <= $j; $i++) {
                    $event->sheet->getDelegate()->getColumnDimensionByColumn($i)->setWidth('20');
                }
                //设置字体大小
                $event->sheet->getDelegate()->getStyle('A1:' . $endColumn . '1')->getFont()->setSize(12);
                //下拉
                $arrayList = [
                    'A' => implode(',', array_values(ConstTranslateTrait::addressTypeList())),
                    'D' => implode(',', CountryTrait::getCountryNameList()),
                ];
                foreach ($arrayList as $k => $v) {
                    for ($i = 0; $i < 200; $i++) {
                        $event->sheet->getDelegate()->getcell($k . ($i + 3))->getDataValidation()->setType(DataValidation::TYPE_LIST)
                            ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                            ->setAllowBlank(true)
                            ->setShowInputMessage(true)
                            ->setShowErrorMessage(true)
                            ->setShowDropDown(true)
                            ->setErrorTitle(__('输入的值有误'))
                            ->setError(__('输入的值有误'))
                            ->setPromptTitle('')
                            ->setPrompt('')
                            ->setFormula1('"' . $v . '"');
                    }
                }
            }
        ];
    }

}

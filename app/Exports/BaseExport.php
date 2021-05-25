<?php

namespace App\Exports;

use App\Traits\FactoryInstanceTrait;
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

class BaseExport implements FromArray, WithTitle, WithEvents, WithStrictNullComparison, WithHeadings
{
    use FactoryInstanceTrait;
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
                /*********************************订单导入模板*****************************/
                if ($this->title === 'template') {
                    //冻结单元格
                    $event->sheet->getDelegate()->freezePane('A3');
                    //设置日期格式
                    $event->sheet->getDelegate()->getStyle('G2:G102')->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);
                    //设置字体颜色
                    $event->sheet->getDelegate()->getStyle('A1:G1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                    $event->sheet->getDelegate()->getStyle('M1:N1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                    //设置金额格式
                    $event->sheet->getDelegate()->getStyle('H1:H102')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
                    $event->sheet->getDelegate()->getStyle('I1:I102')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
                    //$countryList=implode(',',collect(self::getInstance(CommonService::class)->getCountryList())->pluck('name')->toArray());
                    $typeList = implode(',', [__('提货'), __('配送')]);
                    $settlementList = implode(',', [__('现付'), __('回单付'),__('周结'),__('月结'),__('免费')]);
                    $deliveryList = implode(',', [__('是'), __('否')]);
                    $itemList = implode(',', [__('包裹'), __('材料')]);
                    for ($i = 0; $i < 100; $i++) {
                        $event->sheet->getDelegate()->getcell('A' . ($i + 2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
                            ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                            ->setAllowBlank(true)
                            ->setShowInputMessage(true)
                            ->setShowErrorMessage(true)
                            ->setShowDropDown(true)
                            ->setErrorTitle(__('输入的值有误'))
                            ->setError(__('输入的值有误'))
                            ->setPromptTitle('')
                            ->setPrompt('')
                            ->setFormula1('"' . $typeList . '"');
                        /*                        $event->sheet->getDelegate()->getcell('D'.($i+2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
                                                    ->setErrorStyle(DataValidation::STYLE_INFORMATION )
                                                    ->setAllowBlank(true)
                                                    ->setShowInputMessage(true)
                                                    ->setShowErrorMessage(true)
                                                    ->setShowDropDown(true)
                                                    ->setErrorTitle(__('输入的值有误'))
                                                    ->setError(__('输入的值有误'))
                                                    ->setPromptTitle('')
                                                    ->setPrompt('')
                                                    ->setFormula1('"' . $countryList . '"');*/
                        $event->sheet->getDelegate()->getcell('G' . ($i + 2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
                            ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                            ->setAllowBlank(true)
                            ->setShowInputMessage(true)
                            ->setShowErrorMessage(true)
                            ->setShowDropDown(true)
                            ->setErrorTitle(__('输入的值有误'))
                            ->setError(__('输入的值有误'))
                            ->setPromptTitle('')
                            ->setPrompt('')
                            ->setFormula1('"' . $settlementList . '"');
                        $event->sheet->getDelegate()->getcell('k' . ($i + 2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
                            ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                            ->setAllowBlank(true)
                            ->setShowInputMessage(true)
                            ->setShowErrorMessage(true)
                            ->setShowDropDown(true)
                            ->setErrorTitle(__('输入的值有误'))
                            ->setError(__('输入的值有误'))
                            ->setPromptTitle('')
                            ->setPrompt('')
                            ->setFormula1('"' . $deliveryList . '"');
                        $event->sheet->getDelegate()->getcell('M' . ($i + 2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
                            ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                            ->setAllowBlank(true)
                            ->setShowInputMessage(true)
                            ->setShowErrorMessage(true)
                            ->setShowDropDown(true)
                            ->setErrorTitle(__('输入的值有误'))
                            ->setError(__('输入的值有误'))
                            ->setPromptTitle('')
                            ->setPrompt('')
                            ->setFormula1('"' . $itemList . '"');
                        $event->sheet->getDelegate()->getcell('R' . ($i + 2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
                            ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                            ->setAllowBlank(true)
                            ->setShowInputMessage(true)
                            ->setShowErrorMessage(true)
                            ->setShowDropDown(true)
                            ->setErrorTitle(__('输入的值有误'))
                            ->setError(__('输入的值有误'))
                            ->setPromptTitle('')
                            ->setPrompt('')
                            ->setFormula1('"' . $itemList . '"');
                        $event->sheet->getDelegate()->getcell('W' . ($i + 2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
                            ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                            ->setAllowBlank(true)
                            ->setShowInputMessage(true)
                            ->setShowErrorMessage(true)
                            ->setShowDropDown(true)
                            ->setErrorTitle(__('输入的值有误'))
                            ->setError(__('输入的值有误'))
                            ->setPromptTitle('')
                            ->setPrompt('')
                            ->setFormula1('"' . $itemList . '"');
                        $event->sheet->getDelegate()->getcell('AB' . ($i + 2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
                            ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                            ->setAllowBlank(true)
                            ->setShowInputMessage(true)
                            ->setShowErrorMessage(true)
                            ->setShowDropDown(true)
                            ->setErrorTitle(__('输入的值有误'))
                            ->setError(__('输入的值有误'))
                            ->setPromptTitle('')
                            ->setPrompt('')
                            ->setFormula1('"' . $itemList . '"');
                        $event->sheet->getDelegate()->getcell('AG' . ($i + 2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
                            ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                            ->setAllowBlank(true)
                            ->setShowInputMessage(true)
                            ->setShowErrorMessage(true)
                            ->setShowDropDown(true)
                            ->setErrorTitle(__('输入的值有误'))
                            ->setError(__('输入的值有误'))
                            ->setPromptTitle('')
                            ->setPrompt('')
                            ->setFormula1('"' . $itemList . '"');
                    }
                    //设置表头背景色为灰色
                    $event->sheet->getDelegate()->getStyle('A1:' . $endColumn . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFAAAAAA');

                    /*                    $event->sheet->getDelegate()->getComment('A1')
                                            ->getText()->createTextRun(__('1-取件，2-派件'));*/
                }
                /*********************************取件报告导出*****************************/
                if ($this->type === 'tour') {
                    $column = [
                        'A' => 15,
                        'B' => 15,
                        'C' => 10,
                        'D' => 10,
                        'E' => 10,
                        'F' => 10,
                        'G' => 10,
                        'H' => 10,
                        'I' => 10,
                        'J' => 10,
                        'K' => 10,
                        'L' => 15,
                        'M' => 10,
                        'N' => 30,
                        'O' => 10,
                        'P' => 10,
                        'Q' => 10,
                        'R' => 10,
                        'S' => 10,
                        'T' => 10,
                        'U' => 10,
                        'V' => 10,
                        'W' => 10,
                        'X' => 10,
                        'Y' => 10,
                        'Z' => 20,
                        'AA' => 20,
                        'AB' => 25,
                        'AC' => 25,

                    ];
                    foreach ($column as $k => $v) {
                        $event->sheet->getDelegate()->getColumnDimension($k)->setWidth($v);
                    }
                }
                /*********************************取件报告导出*****************************/
                if ($this->type == 'batchCount') {
                    $column = [
                        'A' => 20,
                        'B' => 30,
                        'C' => 20,
                        'D' => 20,
                        'E' => 20,
                        'F' => 20,
                        'G' => 20,
                        'H' => 20,
                        'I' => 20,
                    ];
                    // 合并单元格
                    $event->sheet->getDelegate()->MergeCells('A1:' . $endColumn . '1');
                    foreach ($column as $k => $v) {
                        $event->sheet->getDelegate()->getColumnDimension($k)->setWidth($v);
                    }
                    $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }
                /*                if($this->type === 'orderOut'){
                                    for ($i = 0, $j = count($this->headings()); $i <= $j; $i++) {
                                        $event->sheet->getDelegate()->getColumnDimensionByColumn($i)->setWidth('15');
                                    }
                                }*/
                if ($this->type == 'trackingOrderOut') {
                    $column = [
                        'A' => 15,
                        'B' => 10,
                        'C' => 15,
                        'D' => 10,
                        'E' => 10,
                        'F' => 10,
                        'G' => 15,
                        'H' => 10,
                        'I' => 10,
                        'J' => 15,
                        'K' => 10,
                        'L' => 20,
                        'M' => 15,
                        'N' => 10,
                        'O' => 20
                    ];
                    foreach ($column as $k => $v) {
                        $event->sheet->getDelegate()->getColumnDimension($k)->setWidth($v);
                    }
                }
                if ($this->type == 'orderOut') {
                    $event->sheet->getDelegate()->getStyle('R2:T200')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
                    $column = [
                        'A' => 15,
                        'B' => 10,
                        'C' => 10,
                        'D' => 15,
                        'E' => 10,
                        'F' => 10,
                        'G' => 15,
                        'H' => 10,
                        'I' => 5,
                        'J' => 10,
                        'K' => 10,
                        'L' => 5,
                        'M' => 10,
                        'N' => 15,
                        'O' => 5,
                        'P' => 15,
                        'Q' => 5,
                        'R' => 10,
                        'S' => 10,
                        'T' => 10,
                        'U' => 20,
                    ];
                    foreach ($column as $k => $v) {
                        $event->sheet->getDelegate()->getColumnDimension($k)->setWidth($v);
                    }
                }
            }
        ];
    }
}

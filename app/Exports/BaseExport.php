<?php

namespace App\Exports;

use App\Services\CommonService;
use App\Traits\FactoryInstanceTrait;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;     // 自动注册事件监听器
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;    // 导出 0 原样显示，不为 null
use Maatwebsite\Excel\Concerns\WithTitle;    // 设置工作䈬名称
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Facades\Excel;    // 在工作表流程结束时会引发事件
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class BaseExport implements FromArray, WithTitle, WithEvents, WithStrictNullComparison,WithHeadings
{
    use FactoryInstanceTrait;
    protected $data;
    protected $title;
    protected $headings;

    public function __construct($data,$headings,$title)
    {
        $this->data =$data;
        $this->title =$title;
        $this->headings =$headings;
    }


    /**
     * 表格数据
     * @return array
     */
    public function array(): array
    {
        $data = $this->data;
        return $data;
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
            AfterSheet::class => function(AfterSheet $event) {
            $endColumn=$event->sheet->getDelegate()->getHighestColumn();
            $endRow=$event->sheet->getDelegate()->getHighestRow();
            $cell='A1:'.$endColumn.$endRow;
                // 合并单元格
                //$event->sheet->getDelegate()->setMergeCells(['A1:'.$endColumn.'1']);
                //设置单元格内容自动转行
                $event->sheet->getDelegate()->getStyle($cell)->getAlignment()->setWrapText(TRUE);
                // 设置单元格内容水平靠右
                $event->sheet->getDelegate()->getStyle($cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                //设置单元格内容垂直居中
                $event->sheet->getDelegate()->getStyle($cell)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                // 定义列宽度
                for($i=0,$j=count($this->headings());$i<=$j;$i++){
                    $event->sheet->getDelegate()->getColumnDimensionByColumn($i)->setWidth('20');
                }
                //设置字体大小
                $event->sheet->getDelegate()->getStyle('A1:'.$endColumn.'1')->getFont()->setSize(12);
                if($this->title ==='template'){
                    //冻结单元格
                    $event->sheet->getDelegate()->freezePane('A3');
                   //设置日期格式
                    $event->sheet->getDelegate()->getStyle('H2:H101')->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD2);
                    //设置字体颜色
                    $event->sheet->getDelegate()->getStyle('A1:H1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                    $event->sheet->getDelegate()->getStyle('N1:O1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                    //设置金额格式
                    $event->sheet->getDelegate()->getStyle('I1:I102')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
                    $event->sheet->getDelegate()->getStyle('J1:J102')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
                    //$countryList=implode(',',collect(self::getInstance(CommonService::class)->getCountryList())->pluck('name')->toArray());
                    $typeList=implode(',',[__('取件'),__('派件')]);
                    $settlementList=implode(',',[__('寄付'),__('到付')]);
                    $deliveryList=implode(',',[__('是'),__('否')]);
                    $itemList=implode(',',[__('包裹'),__('材料')]);
                    for ($i=0;$i<100;$i++){
                        $event->sheet->getDelegate()->getcell('A'.($i+2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
                            ->setErrorStyle(DataValidation::STYLE_INFORMATION )
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
                        $event->sheet->getDelegate()->getcell('H'.($i+2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
                            ->setErrorStyle(DataValidation::STYLE_INFORMATION )
                            ->setAllowBlank(true)
                            ->setShowInputMessage(true)
                            ->setShowErrorMessage(true)
                            ->setShowDropDown(true)
                            ->setErrorTitle(__('输入的值有误'))
                            ->setError(__('输入的值有误'))
                            ->setPromptTitle('')
                            ->setPrompt('')
                            ->setFormula1('"' . $settlementList . '"');
                        $event->sheet->getDelegate()->getcell('L'.($i+2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
                            ->setErrorStyle(DataValidation::STYLE_INFORMATION )
                            ->setAllowBlank(true)
                            ->setShowInputMessage(true)
                            ->setShowErrorMessage(true)
                            ->setShowDropDown(true)
                            ->setErrorTitle(__('输入的值有误'))
                            ->setError(__('输入的值有误'))
                            ->setPromptTitle('')
                            ->setPrompt('')
                            ->setFormula1('"' . $deliveryList . '"');
                        $event->sheet->getDelegate()->getcell('N'.($i+2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
                            ->setErrorStyle(DataValidation::STYLE_INFORMATION )
                            ->setAllowBlank(true)
                            ->setShowInputMessage(true)
                            ->setShowErrorMessage(true)
                            ->setShowDropDown(true)
                            ->setErrorTitle(__('输入的值有误'))
                            ->setError(__('输入的值有误'))
                            ->setPromptTitle('')
                            ->setPrompt('')
                            ->setFormula1('"' . $itemList . '"');
                        $event->sheet->getDelegate()->getcell('S'.($i+2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
                            ->setErrorStyle(DataValidation::STYLE_INFORMATION )
                            ->setAllowBlank(true)
                            ->setShowInputMessage(true)
                            ->setShowErrorMessage(true)
                            ->setShowDropDown(true)
                            ->setErrorTitle(__('输入的值有误'))
                            ->setError(__('输入的值有误'))
                            ->setPromptTitle('')
                            ->setPrompt('')
                            ->setFormula1('"' . $itemList . '"');
                        $event->sheet->getDelegate()->getcell('X'.($i+2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
                            ->setErrorStyle(DataValidation::STYLE_INFORMATION )
                            ->setAllowBlank(true)
                            ->setShowInputMessage(true)
                            ->setShowErrorMessage(true)
                            ->setShowDropDown(true)
                            ->setErrorTitle(__('输入的值有误'))
                            ->setError(__('输入的值有误'))
                            ->setPromptTitle('')
                            ->setPrompt('')
                            ->setFormula1('"' . $itemList . '"');
                        $event->sheet->getDelegate()->getcell('AC'.($i+2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
                            ->setErrorStyle(DataValidation::STYLE_INFORMATION )
                            ->setAllowBlank(true)
                            ->setShowInputMessage(true)
                            ->setShowErrorMessage(true)
                            ->setShowDropDown(true)
                            ->setErrorTitle(__('输入的值有误'))
                            ->setError(__('输入的值有误'))
                            ->setPromptTitle('')
                            ->setPrompt('')
                            ->setFormula1('"' . $itemList . '"');
                        $event->sheet->getDelegate()->getcell('AH'.($i+2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
                            ->setErrorStyle(DataValidation::STYLE_INFORMATION )
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
                    $event->sheet->getDelegate()->getStyle('A1:'.$endColumn.'1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFAAAAAA');

                    /*                    $event->sheet->getDelegate()->getComment('A1')
                                            ->getText()->createTextRun(__('1-取件，2-派件'));*/
                }
            },
        ];
    }
}

<?php

namespace App\Exports;

use App\Models\Merchant;
use App\Traits\ConstTranslateTrait;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
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

class OrderExport implements FromArray, WithTitle, WithEvents, WithStrictNullComparison, WithHeadings, WithColumnWidths
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
                //合并单元格
                $event->sheet->getDelegate()->mergeCells('A1:E1');
                $event->sheet->getDelegate()->mergeCells('F1:L1');
                $event->sheet->getDelegate()->mergeCells('M1:S1');
                $event->sheet->getDelegate()->mergeCells('T1:AD1');
                $event->sheet->getDelegate()->mergeCells('AE1:AF1');
                $event->sheet->getDelegate()->mergeCells('AG1:AK1');
                $event->sheet->getDelegate()->mergeCells('AL1:AR1');
                $event->sheet->getDelegate()->mergeCells('AS1:AY1');
                $event->sheet->getDelegate()->mergeCells('AZ1:BF1');
                $event->sheet->getDelegate()->mergeCells('BG1:BM1');
                $event->sheet->getDelegate()->mergeCells('BN1:BT1');
                $event->sheet->getDelegate()->mergeCells('BU1:CD1');
                $event->sheet->getDelegate()->mergeCells('CE1:CN1');
                $event->sheet->getDelegate()->mergeCells('CO1:CX1');
                $event->sheet->getDelegate()->mergeCells('CY1:DH1');
                $event->sheet->getDelegate()->mergeCells('DI1:DR1');

                $endColumn = $event->sheet->getDelegate()->getHighestColumn();
                $endRow = $event->sheet->getDelegate()->getHighestRow();
                $cell = 'A1:' . $endColumn . $endRow;

                //设置行高
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(32);
                $event->sheet->getDelegate()->getRowDimension(2)->setRowHeight(24);
                //设置单元格内容自动转行
                $event->sheet->getDelegate()->getStyle($cell)->getAlignment()->setWrapText(TRUE);
                // 设置单元格内容水平靠右
                $event->sheet->getDelegate()->getStyle($cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                //设置单元格内容垂直居中
                $event->sheet->getDelegate()->getStyle('A1:' . $endColumn . '2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                //设置字体大小
                $event->sheet->getDelegate()->getStyle('A1:' . $endColumn . '1')->getFont()->setSize(16);
                $event->sheet->getDelegate()->getStyle('A2:' . $endColumn . '2')->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle('A1:' . $endColumn . '1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1:' . $endColumn . '2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_NONE)->getStartColor()->setARGB('FF00FF00');
                /*********************************订单导入模板*****************************/
                //冻结单元格
                $event->sheet->getDelegate()->freezePane('A3');
                //设置日期格式
                $date = ['A', 'L', 'S', 'AQ', 'AX', 'BE', 'BL', 'BS'];
                foreach ($date as $k => $v) {
                    $event->sheet->getDelegate()->getStyle($v . '3:' . $v . '200')->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);
                }
                //设置金额格式
                $decimal = ['T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE',
                    'AN', 'AU', 'BB', 'BI', 'BP',
                    'BX', 'BY', 'CB',
                    'CG', 'CH', 'CL',
                    'CR', 'CS', 'CV',
                    'DB', 'DC', 'DF',
                    'DL', 'DM', 'DP'];
                foreach ($decimal as $k => $v) {
                    $event->sheet->getDelegate()->getStyle($v . '3:' . $v . '200')->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
                }

                //设置字体颜色
                $event->sheet->getDelegate()->getStyle('A2:C2')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                $event->sheet->getDelegate()->getStyle('F2:S2')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                $event->sheet->getDelegate()->getStyle('AL2')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                $event->sheet->getDelegate()->getStyle('BU2')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

                //下拉
                $arrayList = [
                    'B' => implode(',', array_values(ConstTranslateTrait::orderTypeList())),
                    'C' => implode(',', Merchant::query()->get()->pluck('name')->toArray()),
                    'AG' => implode(',', array_values(ConstTranslateTrait::orderControlModeList())),
                    'AH' => implode(',', array_values(ConstTranslateTrait::orderReceiptTypeList())),
                    'AF' => implode(',', array_values(ConstTranslateTrait::orderSettlementTypeList())),

                    'AO' => implode(',', array_values(ConstTranslateTrait::packageFeatureList())),
                    'AV' => implode(',', array_values(ConstTranslateTrait::packageFeatureList())),
                    'BC' => implode(',', array_values(ConstTranslateTrait::packageFeatureList())),
                    'BJ' => implode(',', array_values(ConstTranslateTrait::packageFeatureList())),
                    'BQ' => implode(',', array_values(ConstTranslateTrait::packageFeatureList())),

                    'BZ' => implode(',', array_values(ConstTranslateTrait::materialTypeList())),
                    'CJ' => implode(',', array_values(ConstTranslateTrait::materialTypeList())),
                    'BT' => implode(',', array_values(ConstTranslateTrait::materialTypeList())),
                    'DD' => implode(',', array_values(ConstTranslateTrait::materialTypeList())),
                    'DN' => implode(',', array_values(ConstTranslateTrait::materialTypeList())),

                    'CA' => implode(',', array_values(ConstTranslateTrait::materialPackTypeList())),
                    'CK' => implode(',', array_values(ConstTranslateTrait::materialPackTypeList())),
                    'CU' => implode(',', array_values(ConstTranslateTrait::materialPackTypeList())),
                    'DE' => implode(',', array_values(ConstTranslateTrait::materialPackTypeList())),
                    'DO' => implode(',', array_values(ConstTranslateTrait::materialPackTypeList())),

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

//                $countryList=implode(',',collect(self::getInstance(CommonService::class)->getCountryList())->pluck('name')->toArray());
//                $typeList = implode(',', [__('提货'), __('配送')]);
//                $settlementList = implode(',', [__('现付'), __('回单付'), __('周结'), __('月结'), __('免费')]);
//                $deliveryList = implode(',', [__('是'), __('否')]);
//                $itemList = implode(',', [__('包裹'), __('材料')]);
//                for ($i = 0; $i < 100; $i++) {
//                    $event->sheet->getDelegate()->getcell('A' . ($i + 2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
//                        ->setErrorStyle(DataValidation::STYLE_INFORMATION)
//                        ->setAllowBlank(true)
//                        ->setShowInputMessage(true)
//                        ->setShowErrorMessage(true)
//                        ->setShowDropDown(true)
//                        ->setErrorTitle(__('输入的值有误'))
//                        ->setError(__('输入的值有误'))
//                        ->setPromptTitle('')
//                        ->setPrompt('')
//                        ->setFormula1('"' . $typeList . '"');
//                    $event->sheet->getDelegate()->getcell('D' . ($i + 2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
//                        ->setErrorStyle(DataValidation::STYLE_INFORMATION)
//                        ->setAllowBlank(true)
//                        ->setShowInputMessage(true)
//                        ->setShowErrorMessage(true)
//                        ->setShowDropDown(true)
//                        ->setErrorTitle(__('输入的值有误'))
//                        ->setError(__('输入的值有误'))
//                        ->setPromptTitle('')
//                        ->setPrompt('')
//                        ->setFormula1('"' . $countryList . '"');
//                    $event->sheet->getDelegate()->getcell('G' . ($i + 2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
//                        ->setErrorStyle(DataValidation::STYLE_INFORMATION)
//                        ->setAllowBlank(true)
//                        ->setShowInputMessage(true)
//                        ->setShowErrorMessage(true)
//                        ->setShowDropDown(true)
//                        ->setErrorTitle(__('输入的值有误'))
//                        ->setError(__('输入的值有误'))
//                        ->setPromptTitle('')
//                        ->setPrompt('')
//                        ->setFormula1('"' . $settlementList . '"');
//                    $event->sheet->getDelegate()->getcell('k' . ($i + 2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
//                        ->setErrorStyle(DataValidation::STYLE_INFORMATION)
//                        ->setAllowBlank(true)
//                        ->setShowInputMessage(true)
//                        ->setShowErrorMessage(true)
//                        ->setShowDropDown(true)
//                        ->setErrorTitle(__('输入的值有误'))
//                        ->setError(__('输入的值有误'))
//                        ->setPromptTitle('')
//                        ->setPrompt('')
//                        ->setFormula1('"' . $deliveryList . '"');
//                    $event->sheet->getDelegate()->getcell('M' . ($i + 2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
//                        ->setErrorStyle(DataValidation::STYLE_INFORMATION)
//                        ->setAllowBlank(true)
//                        ->setShowInputMessage(true)
//                        ->setShowErrorMessage(true)
//                        ->setShowDropDown(true)
//                        ->setErrorTitle(__('输入的值有误'))
//                        ->setError(__('输入的值有误'))
//                        ->setPromptTitle('')
//                        ->setPrompt('')
//                        ->setFormula1('"' . $itemList . '"');
//                    $event->sheet->getDelegate()->getcell('R' . ($i + 2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
//                        ->setErrorStyle(DataValidation::STYLE_INFORMATION)
//                        ->setAllowBlank(true)
//                        ->setShowInputMessage(true)
//                        ->setShowErrorMessage(true)
//                        ->setShowDropDown(true)
//                        ->setErrorTitle(__('输入的值有误'))
//                        ->setError(__('输入的值有误'))
//                        ->setPromptTitle('')
//                        ->setPrompt('')
//                        ->setFormula1('"' . $itemList . '"');
//                    $event->sheet->getDelegate()->getcell('W' . ($i + 2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
//                        ->setErrorStyle(DataValidation::STYLE_INFORMATION)
//                        ->setAllowBlank(true)
//                        ->setShowInputMessage(true)
//                        ->setShowErrorMessage(true)
//                        ->setShowDropDown(true)
//                        ->setErrorTitle(__('输入的值有误'))
//                        ->setError(__('输入的值有误'))
//                        ->setPromptTitle('')
//                        ->setPrompt('')
//                        ->setFormula1('"' . $itemList . '"');
//                    $event->sheet->getDelegate()->getcell('AB' . ($i + 2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
//                        ->setErrorStyle(DataValidation::STYLE_INFORMATION)
//                        ->setAllowBlank(true)
//                        ->setShowInputMessage(true)
//                        ->setShowErrorMessage(true)
//                        ->setShowDropDown(true)
//                        ->setErrorTitle(__('输入的值有误'))
//                        ->setError(__('输入的值有误'))
//                        ->setPromptTitle('')
//                        ->setPrompt('')
//                        ->setFormula1('"' . $itemList . '"');
//                    $event->sheet->getDelegate()->getcell('AG' . ($i + 2))->getDataValidation()->setType(DataValidation::TYPE_LIST)
//                        ->setErrorStyle(DataValidation::STYLE_INFORMATION)
//                        ->setAllowBlank(true)
//                        ->setShowInputMessage(true)
//                        ->setShowErrorMessage(true)
//                        ->setShowDropDown(true)
//                        ->setErrorTitle(__('输入的值有误'))
//                        ->setError(__('输入的值有误'))
//                        ->setPromptTitle('')
//                        ->setPrompt('')
//                        ->setFormula1('"' . $itemList . '"');
//                }
                //设置表头背景色为灰色

//                $event->sheet->getDelegate()->getComment('A1')
//                    ->getText()->createTextRun(__('1-取件，2-派件'));
            }
        ];
    }

    /**
     * 列宽
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'E' => 15,
            'AD' => 15,
            'AQ' => 15,
            'AX' => 15,
            'BE' => 15,
            'BN' => 15,
            'BS' => 15,

            'T' => 15,
            'U' => 15,
            'V' => 15,
            'W' => 15,
            'X' => 15,
            'Y' => 15,
            'Z' => 15,
            'AA' => 15,
            'AB' => 15,
            'AC' => 15,
            'AE' => 15,

        ];
    }

}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Carbon;
use DB;

class ExportSbobet implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    use Exportable;
    public $data = '';
    public function __construct($data = null)
    {
        $this->data = $data->toArray();
    }

    public function collection()
    {
      $columnTable = DB::getSchemaBuilder()->getColumnListing('bet_history_sbobet');
      $data = $this->data;
      
      
      $arr = [];
      
      foreach ($data as $key => $v){
        $result = [];
        foreach($columnTable as $column){
          $result[] = $v[$column];
        }
        $arr[] = $result;
      }
      return (collect($arr));
    }

    public function headings(): array
    {
      $result = [];
      $columnTable = DB::getSchemaBuilder()->getColumnListing('bet_history_sbobet');
      foreach($columnTable as $item){
        array_push($result, $item);
      }
      return $result;
    }
}

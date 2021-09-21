<?php

namespace App\DataTables;

use App\Wording;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class WordingDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()->of($query)
            ->addColumn('action', function ($data) {
                return '<a class="btn btn-sm btn-primary" href="'.route('wording.edit',$data['id']).'"><i class="material-icons">edit</i></a>';
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Wording $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Wording $model)
    {
        $data = Wording::all()->toArray();
        return $data;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('wording-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->responsive('true')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->addClass('text-center'),
            Column::make('id'),
            Column::make('type'),
            Column::make('flag'),
            Column::make('wording')
                    ->data('id')
                    ->render('function(){
                        return "<button type='."'button'".' class='."'btn btn-primary'".' onclick='."'openModal(".'"+data+"'.")'".'>Preview</button>"
                    }'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Wording_' . date('YmdHis');
    }
}

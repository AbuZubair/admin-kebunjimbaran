<?php

namespace App\DataTables;

use App\Soal;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class QuizDataTable extends DataTable
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
                    return '<a class="btn btn-sm btn-primary" href="'.route('quiz.edit',$data['id']).'"><i class="material-icons">edit</i></a>
                    <button class="btn btn-sm deepPink-bgcolor delete" onClick="deleteData('."'".route('quiz.delete')."'".','.$data['id'].')"><i class="material-icons">delete</i></button>';
                });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Soal $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Soal $model)
    {
        $data = Soal::where(['is_active' => 'Y', ])
                    ->join('quiz_detail', function ($join) {
                        $join->on('quiz.id', '=', 'quiz_detail.quiz_id')
                            ->on('quiz.kunci_jawaban', '=', 'quiz_detail.index');
                        })
                    ->select('quiz.*','quiz_detail.jawaban')->get()->toArray();
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
                    ->setTableId('quiz-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->responsive('true')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('create')
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
            Column::make('time'),
            Column::make('soal'),
            Column::make('jawaban'),
            Column::make('is_active'),
            
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Quiz_' . date('YmdHis');
    }
}

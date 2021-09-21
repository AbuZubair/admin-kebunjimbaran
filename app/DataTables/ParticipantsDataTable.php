<?php

namespace App\DataTables;

use App\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ParticipantsDataTable extends DataTable
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
            return '<button class="btn btn-sm deepPink-bgcolor delete" onClick="deleteData('."'".route('participants.delete')."'".','.$data['user_id'].')"><i class="material-icons">delete</i></button>
            <a class="btn btn-sm btn-primary" href="'.route('participants.edit',$data['user_id']).'"><i class="material-icons">edit</i></a>';
        });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        $data = User::where(['is_deleted' => 'N','is_admin' => 0 ])->whereNotNull('category')
                ->get()->toArray();
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
                    ->setTableId('participants-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('excel')
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
            Column::make('category'),
            Column::make('nama')
                    ->data('username'),
            Column::make('nama sekolah')
                    ->data('asal_sekolah'),
            Column::make('nomor HP')
                    ->data('phone'),
            Column::make('email'), 
            Column::make('instagram')
                    ->data('ig')
                    ->render('function(){
                        return "<a href='."'https://instagram.com/".'"+data+"'."'".' target='."'_blank'".'>"'."+data+".'"</a>"
                    }'), 
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
        return 'Participants_' . date('YmdHis');
    }
}

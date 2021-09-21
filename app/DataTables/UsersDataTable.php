<?php

namespace App\DataTables;

use App\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    protected $actions = ['myCustomAction'];
    public function dataTable($query)
    {
        return datatables()->of($query)
            ->addColumn('', function ($data) {
                return '<input type="checkbox" name="registrations[]" value="'.$data['rowID'].'"/>';
            })
            ->addColumn('action', function ($data) {
                return '<a class="btn btn-sm btn-primary" href="'.route('user.edit',$data['rowID']).'"><i class="material-icons">edit</i></a>
                <button class="btn btn-sm deepPink-bgcolor delete" onClick="deleteData('."'".route('user.delete')."'".','.$data['rowID'].')"><i class="material-icons">delete</i></button>';
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
        $data = User::get()->toArray();
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
                    ->setTableId('users-table')
                    ->columns($this->getColumns())                    
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->responsive('true')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('create'),
                        Button::make([
                            'text' => '<i class="material-icons">delete</i>',
                            'className' => 'delete-btn',
                            'action' => 'function(e, dt, node, config) { 
                                var table = $("#users-table").DataTable();
                                console.log(table.rows().data().toArray()) 
                            }',               
                        ])
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
            Column::computed('')
                  ->exportable(false)
                  ->printable(false)
                  ->addClass('text-center'),
            Column::make('username'),
            Column::make('phoneNumber'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Users_' . date('YmdHis');
    }
}

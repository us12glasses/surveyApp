<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ResponseRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ResponseCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ResponseCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Response::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/response');
        CRUD::setEntityNameStrings('response', 'responses');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // Show related question text
        CRUD::addColumn([
            'name' => 'question.question_text',
            'label' => 'Question',
            'type' => 'text'
        ]);

        CRUD::addColumn([
            'name' => 'answer',
            'label' => 'Answer',
            'type' => 'text'
        ]);

        CRUD::addColumn([
            'name' => 'created_at',
            'label' => 'Submitted At',
            'type' => 'datetime'
        ]);

        // Add export button
        $this->crud->addButtonFromView('top', 'export', 'export-responses', 'end');

        // Add export button
        $this->crud->addButtonFromView('top', 'export', 'export-responses', 'end');

        // Question text search
        CRUD::column('question.question_text')
        ->searchLogic(function ($query, $column, $searchTerm) {
            $query->orWhereHas('question', function($q) use ($searchTerm) {
                $q->where('question_text', 'like', "%{$searchTerm}%");
            });
        });

        // Answer search
        CRUD::column('answer')
        ->searchLogic(function ($query, $column, $searchTerm) {
            $query->orWhere('answer', 'like', "%{$searchTerm}%");
        });

        CRUD::column('created_at')
        ->searchLogic(function ($query, $column, $searchTerm) {
            $query->orWhere('created_at', 'like', "%$searchTerm%")
                  ->orWhereRaw("DATE_FORMAT(created_at, '%M %d %Y') LIKE ?", ["%$searchTerm%"]);
        });
        
        $this->crud->set('search.placeholder', 'Search responses by question, answer or date...');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }
}

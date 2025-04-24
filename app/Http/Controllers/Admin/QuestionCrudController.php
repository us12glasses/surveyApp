<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\QuestionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class QuestionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class QuestionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Question::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/question');
        CRUD::setEntityNameStrings('question', 'questions');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('question_text');
        CRUD::column('type');
        CRUD::column('options');
        CRUD::column('created_at');
        CRUD::column('updated_at');

        CRUD::column('question_text')
        ->searchLogic(function ($query, $column, $searchTerm) {
          $query->orWhere('question_text', 'like', "%{$searchTerm}%");
        });
    
        CRUD::column('type')
        ->searchLogic(function ($query, $column, $searchTerm) {
            $query->orWhere('type', 'like', "%{$searchTerm}%");
        });
        
        CRUD::column('options')
        ->searchLogic(function ($query, $column, $searchTerm) {
            $query->orWhere('options', 'like', "%{$searchTerm}%");
        });
        
        CRUD::column('created_at')
        ->searchLogic(function ($query, $column, $searchTerm) {
            $query->orWhere('created_at', 'like', "%$searchTerm%")
                  ->orWhereRaw("DATE_FORMAT(created_at, '%M %d %Y') LIKE ?", ["%$searchTerm%"]);
        });

        CRUD::column('updated_at')
        ->searchLogic(function ($query, $column, $searchTerm) {
            $query->orWhere('updated_at', 'like', "%$searchTerm%")
                  ->orWhereRaw("DATE_FORMAT(updated_at, '%M %d %Y') LIKE ?", ["%$searchTerm%"]);
        });

            $this->crud->set('search.placeholder', 'Search questions...');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation([
            // 'name' => 'required|min:2',
            'question_text' => 'required|min:5',
            'type' => 'required|in:text,multiple_choice,rating',
            'options' => 'nullable|json|required_if:type,multiple_choice',
            'labels' => 'nullable|json|required_if:type,rating',
        ]);

        CRUD::addField([
            'name' => 'question_text',
            'type' => 'textarea',
            'label' => 'Question Text'
          ]);
        CRUD::addField([
            'name' => 'type',
            'type' => 'select_from_array',
            'label' => 'Question Type',
            'options' => ['text' => 'Text', 'multiple_choice' => 'Multiple Choice', 'rating' => 'Rating (1-5)'],
            'wrapperAttributes' => ['id' => 'type-field']
          ]);
        CRUD::addField([
            'name' => 'labels_script',
            'type' => 'custom_html',
            'value' => '
                <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const typeField = document.querySelector("#type-field select");
                    const labelsField = document.querySelector("#labels-field");

                    function toggleLabelsField() {
                    labelsField.style.display = typeField.value === "rating" ? "block" : "none";
                    }

                    typeField.addEventListener("change", toggleLabelsField);
                    toggleLabelsField(); // Initial check
                });
                </script>
            '
          ]);
        CRUD::addField([
            'name' => 'options',
            'type' => 'textarea',
            'label' => 'Options (JSON array for multiple choice)',
            'hint' => 'Example: ["Male","Female","Other"]'
          ]);
        // Add labels field (only shown for rating questions)
        CRUD::addField([
            'name' => 'labels',
            'type' => 'textarea',
            'label' => 'Rating Labels (JSON key-value pairs)',
            'hint' => 'Example: {"1": "Disappointing", "5": "Exceptional"}',
            'wrapper' => [
            'class' => 'form-group col-md-12',
            'id' => 'labels-field',
            'style' => 'display: none;' // Hidden by default
            ],
          ]);
        // Show labels field only when type is "rating"
        CRUD::field('labels')->showOn(['create', 'update'], function ($entry) {
            return isset($entry->type) && $entry->type === 'rating';
          });


        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}

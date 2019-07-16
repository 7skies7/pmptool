<?php

namespace App\Imports;

use App\Task;
use App\TaskHierarchy;
use App\TaskType;
use App\Priority;
use App\Userstory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;



class TasksImport implements ToModel, WithValidation, WithHeadingRow
{
    use Importable;

    public $tasks_story_points = 0.0;

    public $parent_id = 0;
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {   
        // Sum of story points for all tasks should be less than the approved points for the userstory
        
        $this->validateStoryPoints($row);

        
        $lastTask = Task::orderBy('id', 'desc')->limit(1)->pluck('id');
        $lastTaskId = isset($lastTask[0]) ? $lastTask[0] : 0;
        $task_id = $row['task_level'].'_'.request('scope').'_'.request('userstory').'_'.($lastTaskId + 1);
        
        //Below logic is to add parent task - subtask relationship based on type level
        if($row['task_level'] == 'ST')
        {               
            if($this->parent_id == 0)
            {
                $this->parent_id = $lastTaskId;
            }
        }else{
            $this->parent_id = 0;
        }

        return  new Task([
            'task_id' => $task_id,
            'task_heirarchy' => TaskHierarchy::where('level_initial',$row['task_level'])->pluck('id')[0],
            'task_desc'  => $row['task_desc'],
            'task_type'  => TaskType::where('type',$row['task_type'])->pluck('id')[0],
            'task_priority' => Priority::where('priority_type',$row['task_priority'])->pluck('id')[0],
            'task_status' => 6,
            'task_point'  => $row['task_point'],
            'parent_id' => $this->parent_id,
            'project_id' => request('project'),
            'cr_id' => request('scope'),
            'userstory_id' => request('userstory'),
            'created_by' => auth()->user()->id,
            'modified_by' => auth()->user()->id,
        ]);
    }

    /**
    * @return array
    */
    public function rules(): array
    {
        return [
            '*.task_level' => 'required',
            '*.task_desc' => 'required',
            '*.task_type' => 'required',
            '*.task_priority' => 'required',
            '*.task_point' => 'required',
            'task_level' => Rule::in(['T','ST']),
            'task_priority' => Rule::in(['High','Low','Medium']),
            'task_type' => Rule::in(['Development','Testing','UI','Design']), 
            'task_point' => 'numeric'  
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'required' => 'Please check you are missing :attribute .',
            'task_level.in' => 'Task Level should be either "T" or "ST"',
            'task_priority.in' => 'Task Priority should have one of these values.(1-> High, 2-> Medium, 3->Low)',
            'task_type.in' => 'Task Type should have one of these values.(1-> Development, 2-> UI, 3->Design, 4->Testing)',
        ];
    }

    public function validateStoryPoints($row)
    {

        if($row['task_level'] == 'T')
        {

            $this->tasks_story_points += $row['task_point'];
            $userstory_point = Userstory::find(request('userstory'))->pluck('userstory_point')[0];
            if($userstory_point < $this->tasks_story_points)
            {
                $error = \Illuminate\Validation\ValidationException::withMessages([
                           'task_point' => ['Total story points of all the tasks('.$this->tasks_story_points.') in the excelsheet exceeds the points approved for the userstory('.$userstory_point.')'],
                        ]);
                throw $error;
            }
        }
    }

    // /**
    //  * @return int row start with the mentioned row
    //  */
    // public function headingRow(): int
    // {
    //     return 2;
    // }

}
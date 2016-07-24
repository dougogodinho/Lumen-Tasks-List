<?php

namespace App\Http\Controllers;

use App\Task;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TasksController extends Controller
{
    /**
     * @param Task $task
     * @return Response|array
     */
    public function index(Task $task)
    {
        $return = ['tasks' => $task->orderBy('sort_order')->orderBy('date_created')->get()];

        if (!$return['tasks']->count()) {
            $return['message'] = $this->trans('message.no-tasks');
        }

        return $return;
    }

    /**
     * @param Task $task
     * @param Request $request
     * @return array|Response
     */
    public function sort(Task $task, Request $request)
    {
        $sort = array_map(function ($uuid) {
            if (!preg_match('/[0-9a-f\-]{36}/', $uuid)) {
                abort(500, 'validation fail for sorting keys');
            }
            return $uuid;
        }, $request->input('sort'));

        $task->whereIn('uuid', $sort)->update([
            'sort_order' => app('db')->raw("FIELD(uuid,'" . join("','", $sort) . "')")
        ]);

        return $this->index($task);
    }

    /**
     * @param Request $request
     * @param Task $task
     * @return Task|Response
     */
    public function store(Request $request, Task $task)
    {
        $this->validate($request, $task->rules(), [
            'content.*' => $this->trans('message.empty-task'),
            'type.*' => $this->trans('message.invalid-task-type')
        ]);

        $task->fill($request->all())->save();

        return $task->fresh();
    }

    /**
     * @param $id
     * @param Request $request
     * @param Task $task
     * @return Task|Response|array
     */
    public function update($id, Request $request, Task $task)
    {
        try {
            $task = $task->findOrFail($id);
        } catch (\Exception $e) {
            return ['task' => null, 'message' => $this->trans('message.no-task-to-update')];
        }

        $this->validate($request, $task->rules(), [
            'content.*' => $this->trans('message.empty-task'),
            'type.*' => $this->trans('message.invalid-task-type')
        ]);

        $task->fill($request->all())->save();

        return $task->fresh();
    }

    /**
     * @param Task $task
     * @return array|Response
     */
    public function destroy($id, Task $task)
    {
        try {
            $task = $task->findOrFail($id);
        } catch (\Exception $e) {
            return ['task' => null, 'message' => $this->trans('message.no-task-to-delete')];
        }

        $task->delete();
        return ['task' => null];
    }
}

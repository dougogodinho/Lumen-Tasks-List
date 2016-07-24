<?php

use App\Task;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TaskTest extends TestCase
{
    use DatabaseTransactions;

    public function testList()
    {
        $this->json('get', 'api/tasks');

        $this->assertResponseOk();

        if (Task::count()) {
            // with tasks
            $this->seeJsonStructure([
                'tasks' => [['uuid', 'content', 'done', 'date_created', 'type', 'sort_order']]
            ]);
        } else {
            // without tasks
            $this->seeJson([
                'message' => app('translator')->get('message.no-tasks')
            ]);
        }
    }

    public function testStore()
    {
        $this->json('post', 'api/tasks', [
            'content' => 'Yes, I am testing...',
            'type' => ['work', 'shopping'][rand(0, 1)]
        ]);

        $this->assertResponseOk();

        $this->seeJsonStructure([
            'task' => ['uuid', 'content', 'done', 'date_created', 'type', 'sort_order']
        ]);
    }

    public function testNegativeStore1()
    {
        $this->json('post', 'api/tasks', [
            'type' => ['work', 'shopping'][rand(0, 1)]
        ]);

        $this->assertResponseStatus(422);

        $this->seeJsonStructure([
            'errors' => ['content' => []]
        ]);
        $this->seeJson([app('translator')->get('message.empty-task')]);
    }

    public function testNegativeStore2()
    {
        $this->json('post', 'api/tasks', [
            'content' => 'Yes, I am testing...'
        ]);

        $this->assertResponseStatus(422);

        $this->seeJsonStructure([
            'errors' => ['type' => []]
        ]);
        $this->seeJson([app('translator')->get('message.invalid-task-type')]);
    }

    public function testDestroy1()
    {
        $task = Task::create([
            'content' => 'Yes, I am testing...',
            'type' => ['work', 'shopping'][rand(0, 1)]
        ]);

        $this->json('delete', 'api/tasks/' . $task->uuid);

        $this->assertResponseOk();

        $this->assertNull(Task::find($task->uuid));
    }

    public function testDestroy2()
    {
        $task = Task::create([
            'content' => 'Yes, I am testing...',
            'type' => ['work', 'shopping'][rand(0, 1)]
        ]);
        $task->delete();

        $this->json('delete', 'api/tasks/' . $task->uuid);

        $this->assertResponseOk();

        $this->seeJson(['message' => app('translator')->get('message.no-task-to-delete')]);
    }

    public function testUpdate()
    {
        $task = Task::create([
            'content' => 'Yes, I am testing...',
            'type' => ['work', 'shopping'][rand(0, 1)]
        ]);

        $this->json('put', 'api/tasks/' . $task->uuid, [
            'content' => 'Yes, I am editing...',
            'type' => ['work', 'shopping'][rand(0, 1)]
        ]);

        $this->assertResponseOk();

        $this->seeJson(['content' => 'Yes, I am editing...']);
    }

    public function testNegativeUpdate()
    {
        $this->json('put', 'api/tasks/non-existing-uuid', [
            'content' => 'Yes, I am editing...',
            'type' => ['work', 'shopping'][rand(0, 1)]
        ]);

        $this->assertResponseOk();

        $this->seeJson(['message' => app('translator')->get('message.no-task-to-update')]);
    }

    public function testSort()
    {
        for ($i = 4; $i > 0; $i--) {
            Task::create([
                'content' => 'Yes, I am testing...',
                'type' => ['work', 'shopping'][rand(0, 1)]
            ]);
        }

        $this->json('put', 'api/tasks/sort', [
            'sort' => $sort = Task::all()->pluck('uuid')->shuffle()->toArray()
        ]);

        $this->assertResponseOk();

        $this->assertEquals($sort, Task::orderBy('sort_order')->orderBy('date_created')->get()->pluck('uuid')->toArray());
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Worker\IndexRequest;
use App\Http\Requests\Worker\StoreRequest;
use App\Http\Requests\Worker\UpdateRequest;
use App\Models\Worker;
use Illuminate\Http\Request;
use App\Services\WorkerService;

class WorkerController extends Controller
{
    protected $workerService;

    public function __construct(WorkerService $workerService)
    {
        $this->workerService = $workerService;
    }

    public function index(IndexRequest $request)
    {
        $data = $request->validated();

        $workerQuery = Worker::query();

        if (isset($data['name'])) {
            $workerQuery->where('name', 'like', "%{$data['name']}%");
        }
        if (isset($data['surname'])) {
            $workerQuery->where('surname', 'like', "%{$data['surname']}%");
        }
        if (isset($data['email'])) {
            $workerQuery->where('email', 'like', "%{$data['email']}%");
        }
        if (isset($data['ageFrom'])) {
            $workerQuery->where('age', '>', $data['ageFrom']);
        }
        if (isset($data['ageTo'])) {
            $workerQuery->where('age', '<', $data['ageTo']);
        }
        if (isset($data['description'])) {
            $workerQuery->where('description', 'like', "%{$data['description']}%");
        }
        if (isset($data['is_married'])) {
            $workerQuery->where('is_married', true);
        }

        $workers = $workerQuery->paginate(4);
        return view('worker.index', compact('workers'));
    }

    public function show(Worker $worker)
    {
        return view('worker.show', compact('worker'));
    }

    public function create()
    {
        return view('worker.create');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $worker = $this->workerService->createWorker($data);
        return redirect()->route('worker.index');
    }

    public function edit(Worker $worker)
    {
        return view('worker.edit', compact('worker'));
    }

    public function update(UpdateRequest $request, Worker $worker)
    {
        $data = $request->validated();
        $data['is_married'] = isset($data['is_married']);

        $worker->update($data);

        return redirect()->route('worker.show', $worker->id);
    }

    public function destroy(Worker $worker)
    {
        $worker->delete();
        return redirect()->route('worker.index');
    }
}

<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function create()
    {
        return view('todos.create');
    }

    public function index()
    {
        $user = Auth::user();
        $todos = Todo::query();

        $status = request('status');
        $search = request('search');

        if ($status) {
            $todos = $todos->where('status', $status);
        }
        // For sql injection
        $pattern = '/^[a-zA-Z\s]+$/';

        if ($search  && preg_match($pattern, $search)) {
            $todos = $todos->where(function ($query) use ($search) {
                $query->where('task', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $todos = $todos->orderBy('status', 'asc')->orderBy('id', 'desc');

        if ($user->is_admin) {
            $todos = $todos->with('user')->paginate(10);
        } else {
            $todos = $todos->where('user_id', $user->id)->paginate(10);
        }

        return view('todos.index', compact('todos'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'task' => 'required|string|max:255',
            'status' => 'required|in:' . implode(',', array_keys(Todo::statusOptions())),
        ]);

        Todo::create([
            'task' => $request->task,
            'user_id' => $request->user()->id,
            'status' => $request->status,
        ]);

        return redirect()->route('todos.index')->with('success', 'Todo item created.');
    }

    public function edit(Todo $todo)
    {
        return view('todos.create', compact('todo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'task' => 'required|string|max:255',
            'status' => 'required|in:' . implode(',', array_keys(Todo::statusOptions())),
        ]);

        $todo->update([
            'task' => $request->task,
            'status' => $request->status,
        ]);

        return redirect()->route('todos.index')->with('success', 'Todo item updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();

        return redirect()->back()->with('success', 'Todo item deleted successfully.');
    }
}

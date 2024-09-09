@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>{{ isset($todo) ? 'Edit' : 'Create' }} To-Do</h1>
    <form method="POST" action="{{ isset($todo) ? route('todos.update', $todo->id) : route('todos.store') }}">
        @csrf
        @if (isset($todo))
            @method('PUT')
        @endif
        <div class="form-group">
            <label for="task">Title</label>
            <input id="task" type="text" class="form-control @error('task') is-invalid @enderror" name="task" value="{{ old('task', $todo->task ?? '') }}" required>
            @error('task')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" class="form-control @error('status') is-invalid @enderror" name="status" required>
                <option value="" disabled>Select Status</option>
                @foreach(App\Models\Todo::statusOptions() as $key => $value)
                    <option value="{{ $key }}" {{ old('status', $todo->status ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @error('status')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">{{ isset($todo) ? 'Update' : 'Create' }} To-Do</button>
    </form>
</div>
@endsection

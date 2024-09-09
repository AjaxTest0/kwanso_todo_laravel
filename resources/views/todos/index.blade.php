@extends('layouts.app')

@section('content')
<div class="container m-4">
    <h1>To-Do List</h1>
    <a href="{{ route('todos.create') }}" class="btn btn-primary mb-3">Add New To-Do</a>
    @if(auth()->user()->is_admin)
    <form action="{{ route('todos.index') }}" method="GET">
        <div class="container m-2">
            <div class="row align-items-center">
                <div class="col-5">
                    <div class="form-group">
                        <select id="status" name="status" class="form-control">
                            <option value="">Select Status</option>
                            <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Completed
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-5">
                    <div class="form-group">
                        <input type="text" id="search" name="search" class="form-control"
                            placeholder="Search user by name" value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </div>
    </form>


    @endif
    <div class="container m-2">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Sr</th>
                    <th>Title</th>
                    <th>Status</th>
                    @if(auth()->user()->is_admin)
                    <th>User</th>
                    @endif
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($todos as $key => $todo)
                <tr class="{{ $todo->status == 'COMPLETED' ? 'table-success' : '' }}">
                    <td>{{ ++$key }}</td>
                    <td>{{ $todo->task }}</td>
                    <td>{{ $todo->status }}</td>

                    {{-- <td class='{{$todo->status == `completed` ? `primary` }}'>{{ $todo->status }}</td> --}}
                    @if(auth()->user()->is_admin)
                    <td>{{ $todo->user->name }} <div class="badge badge-secondary"> {{$todo->user->role}}</div>
                    </td>
                    @endif
                    <td>
                        @if($todo->user->id == auth()->user()->id)
                        <a href="{{ route('todos.edit', $todo->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ route('todos.delete', $todo->id) }}" class="btn btn-danger btn-sm">Delete</a>
                        @else
                        <a class="btn btn-secondary btn-sm" disabled>Edit</a>
                        <a class="btn btn-secondary btn-sm" disabled>Delete</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                @if ($todos->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">Previous</span>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link" href="{{ $todos->previousPageUrl() }}">Previous</a>
                </li>
                @endif
                @for ($i = 1; $i <= $todos->lastPage(); $i++)
                    <li class="page-item {{ $i == $todos->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $todos->url($i) }}">{{ $i }}</a>
                    </li>
                    @endfor

                    @if ($todos->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $todos->nextPageUrl() }}">Next</a>
                    </li>
                    @else
                    <li class="page-item disabled">
                        <span class="page-link">Next</span>
                    </li>
                    @endif
            </ul>
        </nav>
    </div>
</div>
@endsection

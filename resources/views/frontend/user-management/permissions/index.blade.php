@extends('frontend.layouts.app')

@section('title','Permissions')

@section('content')
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Permissions</h2>
    <a href="{{ route('permissions.create') }}" class="btn btn-primary">Add Permission</a>
  </div>

  <table class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th>Name</th>
        <th style="width:150px">Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($permissions as $perm)
      <tr>
        <td>{{ $perm->name }}</td>
        <td>
          <a href="{{ route('permissions.edit',$perm) }}"
             class="btn btn-sm btn-outline-primary">Edit</a>
          <form action="{{ route('permissions.destroy',$perm) }}"
                method="POST"
                class="d-inline"
                onsubmit="return confirm('Delete this permission?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Shifts')

@section('content')

<h1>Shift List</h1>

<br>

@if (session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif

<a
    href="/shifts/create"
    class="btn btn-primary"
>
    + Add Shift
</a>

<br><br>


<table>

    <thead>

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Late After</th>
            <th>Action</th>
        </tr>

    </thead>


    <tbody>

        @forelse ($shifts as $shift)

            <tr>

                <td>{{ $shift->id }}</td>

                <td>{{ $shift->name }}</td>

                <td>{{ $shift->start_time }}</td>

                <td>{{ $shift->end_time }}</td>

                <td>{{ $shift->late_after }}</td>

                <td>

                    <a
                        href="/shifts/{{ $shift->id }}/edit"
                        class="btn btn-warning"
                    >
                        Edit
                    </a>


                    <form
                        action="/shifts/{{ $shift->id }}"
                        method="POST"
                        style="display:inline"
                        onsubmit="return confirm('Delete this shift?')"
                    >

                        @csrf

                        @method('DELETE')

                        <button
                            class="btn btn-danger"
                            type="submit"
                        >
                            Delete
                        </button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>
                <td colspan="6">
                    No shifts found.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

@endsection
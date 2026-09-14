@extends('layouts.app')

@section('title', 'Edit Shift')

@section('content')

<h1>Edit Shift</h1>

<br>

@if ($errors->any())

    <div class="alert alert-danger">

        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach

    </div>

@endif


<form
    action="/shifts/{{ $shift->id }}"
    method="POST"
>

    @csrf

    @method('PUT')


    <div class="form-group">

        <label>Shift Name</label>

        <input
            type="text"
            name="name"
            value="{{ old('name', $shift->name) }}"
        >

    </div>


    <div class="form-group">

        <label>Start Time</label>

        <input
            type="time"
            name="start_time"
            value="{{ old('start_time', $shift->start_time) }}"
        >

    </div>


    <div class="form-group">

        <label>End Time</label>

        <input
            type="time"
            name="end_time"
            value="{{ old('end_time', $shift->end_time) }}"
        >

    </div>


    <div class="form-group">

        <label>Late After</label>

        <input
            type="time"
            name="late_after"
            value="{{ old('late_after', $shift->late_after) }}"
        >

    </div>


    <button
        type="submit"
        class="btn btn-primary"
    >
        Update Shift
    </button>


    <a
        href="/shifts"
        class="btn"
    >
        Cancel
    </a>

</form>

@endsection
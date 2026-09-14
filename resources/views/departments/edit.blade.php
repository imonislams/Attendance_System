@extends('layouts.app')

@section('title', 'Edit Department')

@section('content')

    <h1>Edit Department</h1>

    <br>


    {{-- Validation Errors --}}

    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul>

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Edit Form --}}

    <form
        action="/departments/{{ $department->id }}"
        method="POST"
    >

        @csrf

        @method('PUT')


        {{-- Department Name --}}

        <div class="form-group">

            <label>
                Department Name:
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name', $department->name) }}"
            >

            @error('name')

                <p class="error">
                    {{ $message }}
                </p>

            @enderror

        </div>


        {{-- Description --}}

        <div class="form-group">

            <label>
                Description:
            </label>

            <textarea
                name="description"
                rows="5"
                style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;"
            >{{ old('description', $department->description) }}</textarea>

            @error('description')

                <p class="error">
                    {{ $message }}
                </p>

            @enderror

        </div>


        {{-- Buttons --}}

        <button
            type="submit"
            class="btn btn-primary"
        >
            Update Department
        </button>


        <a
            href="/departments"
            class="btn"
        >
            Cancel
        </a>

    </form>

@endsection
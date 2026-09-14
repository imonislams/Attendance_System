@extends('layouts.app')

@section('title', 'Add Department')

@section('content')

    <h1>Add Department</h1>

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


    {{-- Create Form --}}

    <form
        action="/departments"
        method="POST"
    >

        @csrf


        {{-- Department Name --}}

        <div class="form-group">

            <label>
                Department Name:
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="Enter department name"
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
                placeholder="Enter department description"
            >{{ old('description') }}</textarea>

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
            Save Department
        </button>


        <a
            href="/departments"
            class="btn"
        >
            Back
        </a>

    </form>

@endsection

    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 20px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            background-color: #f5f5f5;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Form Element Styles */
        div {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="date"],
        input[type="file"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 16px;
        }

        img {
            display: block;
            margin-top: 10px;
            max-width: 100px;
            height: auto;
        }

        button[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            font-size: 16px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Success Message Styles */
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 3px;
            margin-bottom: 20px;
        }
    </style>


@extends('layouts.app')

@section('content')
<h1>Edit Profile</h1>

@if (session('success'))
    <p>{{ session('success') }}</p>
@endif

<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="{{ old('name', $profile->name) }}">
    </div>
    <div>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="{{ old('address', $profile->address) }}">
    </div>
    <div>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone', $profile->phone) }}">
    </div>
    <div>
        <label for="birthdate">Birthdate:</label>
        <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate', $profile->birthdate) }}">
    </div>
    <div>
        <label for="avatar">Avatar:</label>
        <input type="file" id="avatar" name="avatar">
        @if ($profile->avatar)
            <img src="{{ asset('storage/' . $profile->avatar) }}" alt="Avatar" style="width: 100px; height: 100px;">
        @else
            <img src="{{ asset('images/default-avatar.png') }}" alt="Default Avatar" style="width: 100px; height: 100px;">
        @endif
    </div>
    <div>
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password">
    </div>
    <div>
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password">
    </div>
    <div>
        <label for="new_password_confirmation">Confirm New Password:</label>
        <input type="password" id="new_password_confirmation" name="new_password_confirmation">
    </div>
    <button type="submit">Update Profile</button>
</form>
@endsection

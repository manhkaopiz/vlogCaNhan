
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .profile-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            align-items: center;
        }

        .profile-card h1 {
            margin-top: 0;
            font-size: 28px;
        }

        .profile-card .avatar {
            margin-right: 20px;
        }

        .profile-card .avatar img {
            width: 250px;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
        }

        .profile-card .info p {
            margin: 10px 0;
        }

        .profile-card a {
            display: inline-block;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            margin-top: 20px;
        }

        .profile-card a:hover {
            background-color: #0056b3;
        }
    </style>

    @extends('layouts.app')

    @section('content')
<div class="container">
    <div class="profile-card">
        <div class="avatar">
            @if ($profile->avatar)
                <img src="{{ asset('storage/' . $profile->avatar) }}" alt="Avatar">
            @else
                <img src="{{ asset('images/default-avatar.png') }}" alt="Default Avatar">
            @endif
        </div>
        <div class="info">
            <h1>Your Profile</h1>

            <p>Email: {{ $profile->customer->email }}</p>
            <p>Name: {{ $profile->name }}</p>
            <p>Address: {{ $profile->address }}</p>
            <p>Phone: {{ $profile->phone }}</p>
            <p>Birthdate: {{ $profile->birthdate }}</p>
            <a href="{{ route('profile.edit') }}">Edit Profile</a>
        </div>
    </div>
</div>
    @endsection

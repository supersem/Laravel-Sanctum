<!DOCTYPE html>
<html>
    <head>
        <title>Blog Feed</title>
    </head>
    <body>
        <h1>Blog Feed</h1>

        <form action="{{ route('home') }}" method="GET">
            <label for="user_id">Filter by User:</label>
            <select name="user_id" id="user_id">
                <option value="">All Users</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            <button type="submit">Filter</button>
        </form>

        <ul>
            @foreach ($publications as $publication)
                <li>
                    <h2>{{ $publication->title }}</h2>
                    <p>{{ $publication->content }}</p>
                    <p>Author: {{ $publication->user->name }}</p>
                </li>
            @endforeach
        </ul>
    </body>
</html>

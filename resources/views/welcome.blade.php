<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vinith's Task Manager</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/home.css">
    <script defer src="/js/home.js"></script>

</head>

<body>
    <div id="app">
        <h1>Vinith's Task Manager</h1>
        <form id="taskForm">
            <input type="text" id="taskName" placeholder="Enter a new task" required>
            <button type="submit">Add</button>
        </form>
        <div class="task-group">
            <h3>Pending Tasks</h3>
            <ul id="pendingTasks"></ul>
        </div>
        <div class="task-group">
            <h3>Completed Tasks</h3>
            <ul id="completedTasks"></ul>
        </div>
    </div>
</body>

</html>

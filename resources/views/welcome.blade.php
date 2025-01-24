<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vinith's Task Manager</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* General Reset */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        #app {
            max-width: 700px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #4caf50;
        }

        form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            flex: 1;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background: #4caf50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }

        button:hover {
            background: #45a049;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        li {
            background: #f9f9f9;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        li.completed {
            text-decoration: line-through;
            color: #aaa;
        }

        li:hover {
            background: #f0f0f0;
        }

        .actions button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-left: 10px;
            padding: 5px;
        }

        .actions .delete-btn {
            color: #f44336;
        }

        .actions .edit-btn {
            color: #ff9800;
        }

        .actions .delete-btn:hover {
            color: #e53935;
        }

        .actions .edit-btn:hover {
            color: #fb8c00;
        }

        .checkbox {
            margin-right: 10px;
            transform: scale(1.2);
        }

        .task-group {
            margin-top: 20px;
        }

        .task-group h3 {
            margin: 10px 0;
            color: #666;
        }
    </style>
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

    <script>
        const taskForm = document.getElementById('taskForm');
        const pendingTasks = document.getElementById('pendingTasks');
        const completedTasks = document.getElementById('completedTasks');

        // Fetch tasks from the server
        async function fetchTasks() {
            const response = await axios.get('/tasks');
            pendingTasks.innerHTML = '';
            completedTasks.innerHTML = '';

            response.data.forEach(task => {
                const li = document.createElement('li');
                li.className = task.completed ? 'completed' : '';
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.className = 'checkbox';
                checkbox.checked = task.completed;
                checkbox.onchange = async () => toggleComplete(task.id, checkbox.checked);

                const taskName = document.createElement('span');
                taskName.textContent = task.name;

                const actions = document.createElement('div');
                actions.className = 'actions';

                const editBtn = document.createElement('button');
                editBtn.innerHTML = '<i class="fas fa-edit"></i>';
                editBtn.className = 'edit-btn';
                editBtn.onclick = () => editTask(task.id, task.name);

                const deleteBtn = document.createElement('button');
                deleteBtn.innerHTML = '<i class="fas fa-trash-alt"></i>';
                deleteBtn.className = 'delete-btn';
                deleteBtn.onclick = async () => {
                    await deleteTask(task.id);
                    fetchTasks();
                };

                actions.appendChild(editBtn);
                actions.appendChild(deleteBtn);

                li.appendChild(checkbox);
                li.appendChild(taskName);
                li.appendChild(actions);

                if (task.completed) {
                    completedTasks.appendChild(li);
                } else {
                    pendingTasks.appendChild(li);
                }
            });
        }

        // Add a new task
        taskForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const taskName = document.getElementById('taskName').value;

            try {
                await axios.post('/tasks', {
                    name: taskName
                });
                fetchTasks();
                taskForm.reset();
            } catch (err) {
                alert('Error adding task.');
            }
        });

        // Delete a task
        async function deleteTask(taskId) {
            try {
                await axios.delete(`/tasks/${taskId}`);
            } catch (err) {
                alert('Error deleting task.');
            }
        }

        // Mark task as complete/incomplete
        async function toggleComplete(taskId, isCompleted) {
            try {
                await axios.patch(`/tasks/${taskId}`, {
                    completed: isCompleted
                });
                fetchTasks();
            } catch (err) {
                console.log(err);
                alert('Error updating task status.');
            }
        }

        // Edit a task
        async function editTask(taskId, currentName) {
            const newName = prompt('Edit Task Name:', currentName);
            if (newName && newName !== currentName) {
                try {
                    await axios.put(`/tasks/${taskId}`, {
                        name: newName
                    });
                    fetchTasks();
                } catch (err) {
                    alert('Error updating task.');
                }
            }
        }

        // Initialize the task list
        fetchTasks();
    </script>
</body>

</html>

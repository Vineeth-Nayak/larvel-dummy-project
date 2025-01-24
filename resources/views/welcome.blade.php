<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
            max-width: 600px;
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

        li:hover {
            background: #f0f0f0;
        }

        .delete-btn {
            background: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background: #e53935;
        }
    </style>
</head>

<body>
    <div id="app">
        <h1>Vinith Task Manager</h1>
        <form id="taskForm">
            <input type="text" id="taskName" placeholder="Enter a new task" required>
            <button type="submit">Add</button>
        </form>
        <ul id="taskList"></ul>
    </div>

    <script>
        const taskForm = document.getElementById('taskForm');
        const taskList = document.getElementById('taskList');

        // Fetch all tasks from the server
        async function fetchTasks() {
            const response = await axios.get('/tasks');
            taskList.innerHTML = '';
            response.data.forEach(task => {
                const li = document.createElement('li');
                li.textContent = task.name;

                // Create delete button
                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = 'Delete';
                deleteBtn.className = 'delete-btn';
                deleteBtn.onclick = async () => {
                    await deleteTask(task.id);
                    fetchTasks(); // Refresh the list after deletion
                };

                li.appendChild(deleteBtn);
                taskList.appendChild(li);
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

        // Initialize the task list
        fetchTasks();
    </script>
</body>

</html>

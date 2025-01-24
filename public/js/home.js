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

// Check task
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

fetchTasks();

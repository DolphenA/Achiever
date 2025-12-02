@include('partials.header', ['title' => 'Home Page'])
@include('partials.navbar')
        <main class="container-fluid mt-4">
            <div class="row justify-content-center">
                <!-- Task Section -->
                <div class="col-lg-8 col-md-10 mb-4">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body text-center d-flex flex-column justify-content-center align-items-center" style="min-height: 150px;">
                            <button class="btn btn-light btn-lg add-task-button" type="button" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                                <i class="bi"></i> New Task 
                            </button>
                        </div>
                    </div>

                    <!-- Task List -->
                    <div id="taskList" class="task-list-container">
                        <!-- Tasks will be displayed here -->
                    </div>
                </div>
            </div>
        </main>

        <!-- Add Task Modal -->
        <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-light text-dark">
                        <h5 class="modal-title" id="addTaskModalLabel">Create New Task</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addTaskForm">
                            <!-- Task Name -->
                            <div class="mb-3">
                                <label for="taskName" class="form-label">Task Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="taskName" name="taskName" placeholder="task name" required>
                            </div>

                            
                            <!-- Date and Time Row -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="taskDate" class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="taskDate" name="taskDate" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="taskTime" class="form-label">Time</label>
                                    <input type="time" class="form-control" id="taskTime" name="taskTime">
                                </div>
                            </div>

                            <!-- Priority -->
                            <div class="mb-3">
                                <label for="taskPriority" class="form-label">Priority</label>
                                <select class="form-select" id="taskPriority" name="taskPriority">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>

                           <!-- Task Description -->
                            <div class="mb-3">
                                <label for="taskDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="taskDescription" name="taskDescription" rows="3" placeholder="(optional)"></textarea>
                            </div>

                            <!-- File Upload -->
                            <div class="mb-3">
                                <label for="taskFile" class="form-label">Attach File (Optional)</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="taskFile" name="taskFile" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                    <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('taskFile').click()">
                                        <i class="bi bi-paperclip"></i> Browse
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-1">Accepted formats: PDF, Word (.doc, .docx) - Max size: 5GB</small>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer ccenter bg-light text-dark">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-secondary" id="saveTaskBtn">Save Task</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Edit Task Modal -->
        <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-light text-dark">
                        <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editTaskForm">
                            <input type="hidden" id="editTaskId">
                            <!-- Task Name -->
                            <div class="mb-3">
                                <label for="editTaskName" class="form-label">Task Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editTaskName" placeholder="task name" required>
                            </div>

                            <!-- Date and Time Row -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="editTaskDate" class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="editTaskDate" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="editTaskTime" class="form-label">Time</label>
                                    <input type="time" class="form-control" id="editTaskTime">
                                </div>
                            </div>

                            <!-- Priority -->
                            <div class="mb-3">
                                <label for="editTaskPriority" class="form-label">Priority</label>
                                <select class="form-select" id="editTaskPriority">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>

                            <!-- Task Description -->
                            <div class="mb-3">
                                <label for="editTaskDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="editTaskDescription" rows="3" placeholder="(optional)"></textarea>
                            </div>

                            <!-- File Upload -->
                            <div class="mb-3">
                                <label for="editTaskFile" class="form-label">Attach File (Optional)</label>
                                <div id="currentFileDisplay" class="mb-2"></div>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="editTaskFile" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                    <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('editTaskFile').click()">
                                        <i class="bi bi-paperclip"></i> Browse
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-1">Accepted formats: PDF, Word (.doc, .docx) - Max size: 5GB</small>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer ccenter bg-light text-dark">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-secondary" id="updateTaskBtn">Update Task</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fireworks Container -->
        <div id="fireworks-container"></div>

        <!-- Success Toast Notification -->
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <strong>Success!</strong> Task created successfully!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Task management
            let tasks = [];

            // Load tasks on page load
            document.addEventListener('DOMContentLoaded', function() {
                loadTasks();

                // Set today's date as default
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('taskDate').value = today;
            });

            // Function to load tasks dynamically from API
            function loadTasks() {
                fetch('{{ route("tasks.api") }}', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        tasks = data.tasks;
                        displayTasks();
                    }
                })
                .catch(error => {
                    console.error('Error loading tasks:', error);
                });
            }

            // Save task button click handler
            document.getElementById('saveTaskBtn').addEventListener('click', function() {
                saveTask();
            });

            // Update task button click handler
            document.getElementById('updateTaskBtn').addEventListener('click', function() {
                updateTask();
            });

            // Allow Enter key to submit in task name field
            document.getElementById('taskName').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    saveTask();
                }
            });

            function saveTask() {
                // Get form values
                const taskName = document.getElementById('taskName').value.trim();
                const taskDescription = document.getElementById('taskDescription').value.trim();
                let taskDate = document.getElementById('taskDate').value;
                const taskTime = document.getElementById('taskTime').value;
                const taskPriority = document.getElementById('taskPriority').value;
                const taskFileInput = document.getElementById('taskFile');
                const taskFile = taskFileInput.files[0];

                // Validate required fields
                if (!taskName) {
                    alert('Please enter a task name');
                    return;
                }
                if (!taskDate) {
                    alert('Please select a date');
                    return;
                }

                // Create FormData for file upload
                const formData = new FormData();
                formData.append('name', taskName);
                formData.append('description', taskDescription);
                formData.append('date', taskDate);
                formData.append('time', taskTime);
                formData.append('priority', taskPriority);
                if (taskFile) {
                    formData.append('file', taskFile);
                }
                
                formData.append('_token', '{{ csrf_token() }}');

                // Send AJAX request
                fetch('{{ route("tasks.store") }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload tasks instead of page
                        loadTasks();
                        
                        // Close modal properly
                        const modalElement = document.getElementById('addTaskModal');
                        const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                        modal.hide();
                        
                        // Remove any lingering backdrops
                        setTimeout(() => {
                            const backdrops = document.querySelectorAll('.modal-backdrop');
                            backdrops.forEach(backdrop => backdrop.remove());
                            document.body.classList.remove('modal-open');
                            document.body.style.overflow = '';
                            document.body.style.paddingRight = '';
                        }, 300);
                        
                        // Reset form
                        document.getElementById('addTaskForm').reset();
                        const today = new Date().toISOString().split('T')[0];
                        document.getElementById('taskDate').value = today;
                    } else {
                        alert('Failed to save task');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to save task');
                });
            }

            function updateTask() {
                const taskId = parseInt(document.getElementById('editTaskId').value);

                // Get form values
                const taskName = document.getElementById('editTaskName').value.trim();
                const taskDescription = document.getElementById('editTaskDescription').value.trim();
                let taskDate = document.getElementById('editTaskDate').value;
                const taskTime = document.getElementById('editTaskTime').value;
                const taskPriority = document.getElementById('editTaskPriority').value;
                const taskFileInput = document.getElementById('editTaskFile');
                const taskFile = taskFileInput.files[0];

                // Validate required fields
                if (!taskName) {
                    alert('Please enter a task name');
                    return;
                }
                if (!taskDate) {
                    alert('Please select a date');
                    return;
                }

                // Create FormData for file upload
                const formData = new FormData();
                formData.append('name', taskName);
                formData.append('description', taskDescription);
                formData.append('date', taskDate);
                formData.append('time', taskTime);
                formData.append('priority', taskPriority);
                if (taskFile) {
                    formData.append('file', taskFile);
                }
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('_method', 'PUT');

                // Send AJAX request
                fetch(`{{ url('/tasks') }}/${taskId}`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload tasks instead of page
                        loadTasks();
                        
                        // Close modal properly
                        const modalElement = document.getElementById('editTaskModal');
                        const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                        modal.hide();
                        
                        // Remove any lingering backdrops
                        setTimeout(() => {
                            const backdrops = document.querySelectorAll('.modal-backdrop');
                            backdrops.forEach(backdrop => backdrop.remove());
                            document.body.classList.remove('modal-open');
                            document.body.style.overflow = '';
                            document.body.style.paddingRight = '';
                        }, 300);
                    } else {
                        alert('Failed to update task');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update task');
                });
            }

            function displayTasks() {
                const taskList = document.getElementById('taskList');
                
                if (tasks.length === 0) {
                    taskList.innerHTML = '<p class="text-center text-muted mt-3">No tasks yet. Click "New Task" to add one!</p>';
                    return;
                }

                // Sort tasks by date
                tasks.sort((a, b) => new Date(a.date) - new Date(b.date));

                let html = '';
                tasks.forEach(task => {
                    const priorityClass = getPriorityClass(task.priority);
                    const dateFormatted = formatDate(task.date);
                    const timeDisplay = task.time ? `at ${task.time}` : '';
                    const fileName = task.file_path ? task.file_path.split('/').pop() : null;
                    
                    html += `
                        <div class="task-card card mb-2 ${task.completed ? 'task-completed' : ''}" data-task-id="${task.id}">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-1">
                                            <input type="checkbox" class="form-check-input me-2 task-checkbox" 
                                                   ${task.completed ? 'checked' : ''} 
                                                   onchange="toggleTask(${task.id})">
                                            <h6 class="mb-0 task-name ${task.completed ? 'text-decoration-line-through' : ''}">
                                                ${task.name}
                                            </h6>
                                        </div>
                                        ${task.description ? `<p class="text-muted small mb-2 ms-4">${task.description}</p>` : ''}
                                        <div class="ms-4">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar"></i> ${dateFormatted} ${timeDisplay}
                                            </small>
                                            <span class="badge ${priorityClass} ms-2">${task.priority}</span>
                                            ${fileName ? `<br><small class="text-info"><i class="bi bi-paperclip"></i> <a href="/storage/${task.file_path}" download="${fileName}" class="text-info">${fileName}</a></small>` : ''}
                                        </div>
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" onclick="editTask(${task.id})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteTask(${task.id})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                taskList.innerHTML = html;
            }

        function toggleTask(taskId) {
            // Find the task to check if it's being completed
            const task = tasks.find(t => t.id === taskId);
            const wasCompleted = task ? task.completed : false;
            
            console.log('Toggle task:', taskId, 'Was completed:', wasCompleted);
            
            fetch(`{{ url('/tasks') }}/${taskId}/toggle`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Toggle response:', data);
                if (data.success) {
                    // Trigger fireworks if task was just completed (not uncompleted)
                    const isNowCompleted = data.task && data.task.completed;
                    console.log('Was completed:', wasCompleted, 'Is now completed:', isNowCompleted);
                    
                    if (!wasCompleted && isNowCompleted) {
                        console.log('TRIGGERING FIREWORKS!');
                        triggerFireworks();
                    }
                    loadTasks();
                }
            })
            .catch(error => console.error('Error:', error));
        }            function editTask(taskId) {
                const task = tasks.find(t => t.id === taskId);
                if (!task) return;

                // Populate edit form
                document.getElementById('editTaskId').value = task.id;
                document.getElementById('editTaskName').value = task.name;
                document.getElementById('editTaskDescription').value = task.description || '';
                document.getElementById('editTaskDate').value = task.date;
                document.getElementById('editTaskTime').value = task.time || '';
                document.getElementById('editTaskPriority').value = task.priority;
                
                // Show current file if exists
                const currentFileDisplay = document.getElementById('currentFileDisplay');
                if (task.file_path) {
                    const fileName = task.file_path.split('/').pop();
                    currentFileDisplay.innerHTML = `
                        <div class="alert alert-info py-2 px-3">
                            <i class="bi bi-paperclip"></i> Current file: <strong>${fileName}</strong>
                        </div>
                    `;
                } else {
                    currentFileDisplay.innerHTML = '';
                }

                // Clear file input
                document.getElementById('editTaskFile').value = '';

                // Open modal
                const modal = new bootstrap.Modal(document.getElementById('editTaskModal'));
                modal.show();
            }

            let removeFile = false;
            function removeTaskFile() {
                removeFile = true;
                document.getElementById('currentFileDisplay').innerHTML = '<div class="alert alert-warning py-2 px-3">File will be removed when you update the task</div>';
            }

            function deleteTask(taskId) {
                if (confirm('Are you sure you want to delete this task?')) {
                    fetch(`{{ url('/tasks') }}/${taskId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadTasks();
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            }

            function getPriorityClass(priority) {
                switch(priority) {
                    case 'high': return 'bg-danger';
                    case 'medium': return 'bg-warning text-dark';
                    case 'low': return 'bg-info';
                    default: return 'bg-secondary';
                }
            }

            function formatDate(dateString) {
                const date = new Date(dateString);
                const options = { month: 'short', day: 'numeric', year: 'numeric' };
                return date.toLocaleDateString('en-US', options);
            }
        </script>
@include('partials.footer')
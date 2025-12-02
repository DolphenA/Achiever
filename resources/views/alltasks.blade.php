@include('partials.header', ['title' => 'All Tasks - Achiever'])
@include('partials.navbar')

    <main class="container-fluid mt-4">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">All Tasks</h4>
                    </div>
                    <div>
                        <span class="badge bg-primary" id="taskCount">0 Tasks</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Sort Bar -->
        <div class="row mb-3">
            <div class="col-md-6 mb-2">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="searchTasks" placeholder="Search tasks...">
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <select class="form-select" id="filterPriority">
                    <option value="all">All Priorities</option>
                    <option value="high">High Priority</option>
                    <option value="medium">Medium Priority</option>
                    <option value="low">Low Priority</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <select class="form-select" id="sortTasks">
                    <option value="date-asc">Date (Oldest First)</option>
                    <option value="date-desc">Date (Newest First)</option>
                    <option value="priority">Priority</option>
                    <option value="name">Name (A-Z)</option>
                </select>
            </div>
        </div>

        <!-- Tasks Grid -->
        <div class="row" id="allTasksGrid">
            <!-- Tasks will be displayed here -->
        </div>

        <!-- Empty State -->
        <div class="row d-none" id="emptyState">
            <div class="col-12 text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                <h4 class="mt-3 text-muted">No tasks found</h4>
                <p class="text-muted">Start by creating a new task from the home page</p>
            </div>
        </div>
    </main>

    <!-- Fireworks Container -->
    <div id="fireworks-container"></div>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let tasks = [];
        let filteredTasks = [];

        // Load tasks on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadTasks();
            
            // Add event listeners
            document.getElementById('searchTasks').addEventListener('input', filterAndDisplay);
            document.getElementById('filterPriority').addEventListener('change', filterAndDisplay);
            document.getElementById('sortTasks').addEventListener('change', filterAndDisplay);
            document.getElementById('updateTaskBtn').addEventListener('click', updateTask);
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
                    console.log('Alltasks - Tasks loaded:', tasks);
                    console.log('Alltasks - Number of tasks:', tasks.length);
                    filterAndDisplay();
                }
            })
            .catch(error => {
                console.error('Error loading tasks:', error);
            });
        }

        function filterAndDisplay() {
            const searchTerm = document.getElementById('searchTasks').value.toLowerCase();
            const priorityFilter = document.getElementById('filterPriority').value;
            const sortOption = document.getElementById('sortTasks').value;

            // Filter tasks
            filteredTasks = tasks.filter(task => {
                const matchesSearch = task.name.toLowerCase().includes(searchTerm) || 
                                    (task.description && task.description.toLowerCase().includes(searchTerm));
                const matchesPriority = priorityFilter === 'all' || task.priority === priorityFilter;
                return matchesSearch && matchesPriority;
            });

            // Sort tasks
            sortTasks(sortOption);
            
            // Display
            displayTasks();
        }

        function sortTasks(option) {
            switch(option) {
                case 'date-asc':
                    filteredTasks.sort((a, b) => new Date(a.date) - new Date(b.date));
                    break;
                case 'date-desc':
                    filteredTasks.sort((a, b) => new Date(b.date) - new Date(a.date));
                    break;
                case 'priority':
                    const priorityOrder = { high: 1, medium: 2, low: 3 };
                    filteredTasks.sort((a, b) => priorityOrder[a.priority] - priorityOrder[b.priority]);
                    break;
                case 'name':
                    filteredTasks.sort((a, b) => a.name.localeCompare(b.name));
                    break;
            }
        }

        function displayTasks() {
            const grid = document.getElementById('allTasksGrid');
            const emptyState = document.getElementById('emptyState');
            
            // Use filtered tasks if filters are active, otherwise use all tasks
            const tasksToDisplay = filteredTasks.length > 0 || 
                                   document.getElementById('searchTasks').value || 
                                   document.getElementById('filterPriority').value !== 'all' 
                                   ? filteredTasks : tasks;

            // Update count
            document.getElementById('taskCount').textContent = `${tasksToDisplay.length} Task${tasksToDisplay.length !== 1 ? 's' : ''}`;

            if (tasksToDisplay.length === 0) {
                grid.innerHTML = '';
                emptyState.classList.remove('d-none');
                return;
            }

            emptyState.classList.add('d-none');

            let html = '';
            tasksToDisplay.forEach(task => {
                const priorityClass = getPriorityClass(task.priority);
                const priorityBadgeClass = getPriorityBadgeClass(task.priority);
                const dateFormatted = formatDate(task.date);
                const timeDisplay = task.time ? `at ${task.time}` : '';
                const fileName = task.file_path ? task.file_path.split('/').pop() : null;
                
                html += `
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card h-100 task-card-view ${task.completed ? 'task-completed' : ''}" data-task-id="${task.id}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input task-checkbox" 
                                               ${task.completed ? 'checked' : ''} 
                                               onchange="toggleTask(${task.id})">
                                        <label class="form-check-label"></label>
                                    </div>
                                    <span class="badge ${priorityBadgeClass}">${task.priority}</span>
                                </div>
                                
                                <h5 class="card-title ${task.completed ? 'text-decoration-line-through text-muted' : ''}">
                                    ${task.name}
                                </h5>
                                
                                ${task.description ? `<p class="card-text text-muted small">${task.description}</p>` : ''}
                                
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-event"></i> ${dateFormatted} ${timeDisplay}
                                    </small>
                                    ${fileName ? `<br><small class="text-info mt-1"><i class="bi bi-paperclip"></i> <a href="/storage/${task.file_path}" download="${fileName}" class="text-info text-decoration-none">${fileName}</a></small>` : ''}
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <div class="btn-group w-100" role="group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editTask(${task.id})">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteTask(${task.id})">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            grid.innerHTML = html;
        }

        function toggleTask(taskId) {
            // Find the task to check if it's being completed
            const task = tasks.find(t => t.id === taskId);
            const wasCompleted = task ? task.completed : false;
            
            console.log('[AllTasks] Toggle task:', taskId, 'Was completed:', wasCompleted);
            
            fetch(`{{ url('/tasks') }}/${taskId}/toggle`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('[AllTasks] Toggle response:', data);
                if (data.success) {
                    // Trigger fireworks if task was just completed (not uncompleted)
                    const isNowCompleted = data.task && data.task.completed;
                    console.log('[AllTasks] Was completed:', wasCompleted, 'Is now completed:', isNowCompleted);
                    
                    if (!wasCompleted && isNowCompleted) {
                        console.log('[AllTasks] TRIGGERING FIREWORKS!');
                        triggerFireworks();
                    }
                    loadTasks();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function editTask(taskId) {
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
                case 'high': return 'border-danger';
                case 'medium': return 'border-warning';
                case 'low': return 'border-info';
                default: return 'border-secondary';
            }
        }

        function getPriorityBadgeClass(priority) {
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
    <script src="{{ asset('js/fireworks.js') }}"></script>
@include('partials.footer')
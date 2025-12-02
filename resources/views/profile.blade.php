@include('partials.header', ['title' => 'User Profile - Achiever'])
@include('partials.navbar')
    <!-- Login/Signup View (shown when not logged in) -->
    <div class="container-fluid mt-4" id="authView">
        <div class="row">
            <!-- Login Form -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-light text-dark">
                        <h4 class="mb-0"><i class="bi bi-box-arrow-in-right"></i> Login</h4>
                    </div>
                    <div class="card-body">
                        <form id="loginForm">
                            <div class="mb-3">
                                <label for="loginEmail" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="loginEmail" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3">
                                <label for="loginPassword" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="loginPassword" placeholder="Enter your password" required>
                            </div>
                            <button type="submit" class="btn btn-light w-100">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </form>
                        <div id="loginError" class="alert alert-danger mt-3 d-none"></div>
                    </div>
                </div>
            </div>

            <!-- Signup Form -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-light text-dark">
                        <h4 class="mb-0"><i class="bi bi-person-plus"></i> Sign Up</h4>
                    </div>
                    <div class="card-body">
                        <form id="signupForm">
                            <div class="mb-3">
                                <label for="signupName" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="signupName" placeholder="Enter a username" required>
                            </div>
                            <div class="mb-3">
                                <label for="signupEmail" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="signupEmail" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3">
                                <label for="signupPassword" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="signupPassword" placeholder="Create a password" required>
                            </div>
                            <div class="mb-3">
                                <label for="signupConfirmPassword" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="signupConfirmPassword" placeholder="Confirm your password" required>
                            </div>
                            <button type="submit" class="btn btn-light w-100">
                                <i class="bi bi-person-plus"></i> Sign Up
                            </button>
                        </form>
                        <div id="signupError" class="alert alert-danger mt-3 d-none"></div>
                        <div id="signupSuccess" class="alert alert-success mt-3 d-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile View (shown when logged in) -->
    <div class="container-fluid mt-4 d-none" id="profileView">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light text-dark d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="bi bi-person-circle"></i> My Profile</h4>
                        <button class="btn btn-light btn-sm" onclick="logout()">
                            <i class="bi "></i> Logout
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <h3 id="profileName" class="mb-3"></h3>
                                <div class="mb-3">
                                    <strong><i class="bi"></i> Email:</strong>
                                    <span id="profileEmail" class="ms-2"></span>
                                </div>
                                <div class="mb-3">
                                    <strong><i class="bi text-dark"></i> Total Tasks:</strong>
                                    <span id="profileTaskCount" class="ms-2 badge-dark">0</span>

                                </div>
                                <div class="mb-3">
                                    <strong><i class="bi"></i> Completed Tasks:</strong>
                                    <span id="profileCompletedCount" class="ms-2 badge-dark">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentUser = null;

        document.addEventListener('DOMContentLoaded', function() {
            checkAuth();
            
            // Login form handler
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                e.preventDefault();
                handleLogin();
            });

            // Signup form handler
            document.getElementById('signupForm').addEventListener('submit', function(e) {
                e.preventDefault();
                handleSignup();
            });
        });

        function checkAuth() {
            console.log('Checking authentication...');
            fetch('{{ route("check.auth") }}', {
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    console.log('Auth check response:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Auth check data:', data);
                    if (data.authenticated) {
                        currentUser = data.user;
                        console.log('User is authenticated:', currentUser);
                        showProfile();
                    } else {
                        console.log('User is NOT authenticated');
                        showAuth();
                    }
                })
                .catch(error => {
                    console.error('Error checking auth:', error);
                    showAuth();
                });
        }

        function handleLogin() {
            const email = document.getElementById('loginEmail').value.trim();
            const password = document.getElementById('loginPassword').value;
            const errorDiv = document.getElementById('loginError');

            fetch('{{ route("login") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    email: email,
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentUser = data.user;
                    hideError(errorDiv);
                    showProfile();
                } else {
                    showError(errorDiv, data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError(errorDiv, 'Login failed. Please try again.');
            });
        }

        function handleSignup() {
            const name = document.getElementById('signupName').value.trim();
            const email = document.getElementById('signupEmail').value.trim();
            const password = document.getElementById('signupPassword').value;
            const confirmPassword = document.getElementById('signupConfirmPassword').value;
            const errorDiv = document.getElementById('signupError');
            const successDiv = document.getElementById('signupSuccess');

            if (!name || !email || !password) {
                showError(errorDiv, 'Please fill in all fields');
                return;
            }

            if (password !== confirmPassword) {
                showError(errorDiv, 'Passwords do not match');
                return;
            }

            if (password.length < 6) {
                showError(errorDiv, 'Password must be at least 6 characters');
                return;
            }
            
            fetch('{{ route("signup") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    name: name,
                    email: email,
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    hideError(errorDiv);
                    successDiv.textContent = data.message;
                    successDiv.classList.remove('d-none');
                    document.getElementById('signupForm').reset();
                    
                    setTimeout(() => {
                        successDiv.classList.add('d-none');
                        // Switch to login tab
                        document.querySelector('[data-bs-target="#login"]').click();
                    }, 2000);
                } else {
                    showError(errorDiv, data.message || 'Signup failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError(errorDiv, 'Signup failed. Please try again.');
            });
        }

        function showProfile() {
            document.getElementById('authView').classList.add('d-none');
            document.getElementById('profileView').classList.remove('d-none');

            // Populate profile data
            document.getElementById('profileName').textContent = currentUser.name;
            document.getElementById('profileEmail').textContent = currentUser.email;

            // Get task statistics from database
            fetch('{{ route("tasks.stats") }}', {
                credentials: 'same-origin'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('profileTaskCount').textContent = data.total;
                        document.getElementById('profileCompletedCount').textContent = data.completed;
                    } else {
                        document.getElementById('profileTaskCount').textContent = '0';
                        document.getElementById('profileCompletedCount').textContent = '0';
                    }
                })
                .catch(error => {
                    console.error('Error fetching stats:', error);
                    document.getElementById('profileTaskCount').textContent = '0';
                    document.getElementById('profileCompletedCount').textContent = '0';
                });
        }

        function showAuth() {
            document.getElementById('authView').classList.remove('d-none');
            document.getElementById('profileView').classList.add('d-none');
        }

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '{{ route("logout") }}';
            }
        }

        function showError(element, message) {
            element.textContent = message;
            element.classList.remove('d-none');
        }

        function hideError(element) {
            element.classList.add('d-none');
        }
    </script>
@include('partials.footer')
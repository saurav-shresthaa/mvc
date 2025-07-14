<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Posts Management - Simple MVC</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .post-card {
            transition: transform 0.2s;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .post-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }

        .btn-action {
            margin: 0 2px;
        }

        .post-meta {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .post-content {
            color: #495057;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <!-- Header Section -->
    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 mb-2">
                        <i class="fas fa-blog me-3"></i>Posts Management
                    </h1>
                    <p class="lead mb-0">Simple MVC Blog System</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <button class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus me-2"></i>Add New Post
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Posts Grid -->
        <div class="row" id="postsContainer">
            @forelse ($data['posts'] as $post)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card post-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="post-meta">
                                <i class="fas fa-calendar me-1"></i>{{ $post->created_at->format('M d, Y') }}
                            </p>
                            <p class="card-text post-content">
                                {{ $post->description }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <button class="btn btn-sm btn-outline-primary btn-action"
                                        onclick="viewPost({{ $post->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning btn-action"
                                        onclick="editPost({{ $post->id }}, '{{ $post->title }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger btn-action"
                                        onclick="deletePost({{ $post->id }}, '{{ $post->title }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State (shown when no posts) -->
                <div class="text-center py-5" id="emptyState">
                    <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                    <h3 class="text-muted">No Posts Yet</h3>
                    <p class="text-muted">Start by creating your first post!</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus me-2"></i>Create First Post
                    </button>
                </div>
            @endforelse

        </div>

        @empty($data['posts'])
            <!-- Empty State (shown when no posts) -->
            <div class="text-center py-5" id="emptyState">
                <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                <h3 class="text-muted">No Posts Yet</h3>
                <p class="text-muted">Start by creating your first post!</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus me-2"></i>Create First Post
                </button>
            </div>
        @endempty
    </div>

    <!-- Create Post Modal -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>Create New Post
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="createForm" action="{{ route('post.create') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="createTitle" class="form-label">Post Title</label>
                            <input type="text" name="title" class="form-control" id="createTitle"
                                placeholder="Enter title of the post..." required>
                        </div>
                        <textarea name="description" id="" placeholder="Enter description of the post..." cols="75"
                            rows="10"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Post Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Edit Post
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm">
                    <div class="modal-body">
                        <input type="hidden" id="editPostId">
                        <div class="mb-3">
                            <label for="editTitle" class="form-label">Post Title</label>
                            <input type="text" class="form-control" id="editTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="editAuthor" class="form-label">Author</label>
                            <input type="text" class="form-control" id="editAuthor" required>
                        </div>
                        <div class="mb-3">
                            <label for="editContent" class="form-label">Content</label>
                            <textarea class="form-control" id="editContent" rows="5" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editTags" class="form-label">Tags (comma separated)</label>
                            <input type="text" class="form-control" id="editTags" placeholder="php, mvc, tutorial">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-2"></i>Update Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this post?</p>
                    <div class="alert alert-warning">
                        <strong>Post:</strong> <span id="deletePostTitle"></span>
                    </div>
                    <p class="text-muted">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Delete Post
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Post Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="viewPostTitle">
                        <i class="fas fa-eye me-2"></i>View Post
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <h4 id="viewTitle"></h4>
                        <p class="text-muted" id="viewMeta"></p>
                    </div>
                    <div class="mb-3">
                        <p id="viewContent"></p>
                    </div>
                    <div id="viewTags"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Global variables to store posts (In real MVC, this would come from the model/database)
        let posts = @json($data['posts']);


        // Edit Post Form Handler
        document.getElementById('editForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const postId = parseInt(document.getElementById('editPostId').value);
            const title = document.getElementById('editTitle').value;
            const author = document.getElementById('editAuthor').value;
            const content = document.getElementById('editContent').value;
            const tags = document.getElementById('editTags').value.split(',').map(tag => tag.trim()).filter(tag => tag);

            // Find and update post
            const postIndex = posts.findIndex(post => post.id === postId);
            if (postIndex !== -1) {
                posts[postIndex] = {
                    ...posts[postIndex],
                    title: title,
                    author: author,
                    content: content,
                    tags: tags
                };

                renderPosts();
                bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                showAlert('Post updated successfully!', 'success');
            }
        });

        // Functions for CRUD operations
        function viewPost(postId) {
            const post = posts.find(p => p.id === postId);
            if (post) {
                document.getElementById('viewTitle').textContent = post.title;
                document.getElementById('viewMeta').innerHTML = `
                    <i class="fas fa-calendar me-1"></i>${post.date}
                    <i class="fas fa-user ms-3 me-1"></i>${post.author}
                    <i class="fas fa-eye ms-3 me-1"></i>${post.views} views
                `;
                document.getElementById('viewContent').textContent = post.content;

                const tagsHtml = post.tags.map(tag => `<span class="badge bg-secondary me-1">${tag}</span>`).join('');
                document.getElementById('viewTags').innerHTML = tagsHtml ? `<strong>Tags:</strong> ${tagsHtml}` : '';

                new bootstrap.Modal(document.getElementById('viewModal')).show();
            }
        }

        function editPost(postId, title, content) {
            const post = posts.find(p => p.id === postId);
            if (post) {
                document.getElementById('editPostId').value = postId;
                document.getElementById('editTitle').value = post.title;
                document.getElementById('editAuthor').value = post.author;
                document.getElementById('editContent').value = post.content;
                document.getElementById('editTags').value = post.tags.join(', ');

                new bootstrap.Modal(document.getElementById('editModal')).show();
            }
        }

        function deletePost(postId, title) {
            document.getElementById('deletePostTitle').textContent = title;

            document.getElementById('confirmDeleteBtn').onclick = function () {
                fetch(`/delete/${postId}`, {
                    method: 'POST', // or 'POST' if your route is post
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to delete');
                        return response.json();
                    })
                    .then(data => {
                        window.location.reload(); // ✅ just reload on success
                    })
                    .catch(error => {
                        console.error(error);
                        showAlert('Error deleting post.', 'danger');
                    });
            };

            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }


        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(alertDiv);

            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }

    </script>
</body>

</html>
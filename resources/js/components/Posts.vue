<template>
    <div class="my-3">
        <div class="card card-body w-25 float-left">
            <h2>Posts</h2>

            <form class="mb-3" @submit.prevent="addPost">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Post Title" v-model="post.title" required>
                </div>

                <div class="form-group">
                    <textarea class="form-control" placeholder="Post Description" v-model="post.description" required></textarea>
                </div>

                <button class="btn btn-success btn-block" type="submit">Save</button>
            </form>
        </div>

        <div class="w-50 float-right">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li v-bind:class="[{disabled:!pagination.prev_page_url}]" class="page-item">
                        <a href="javascript:;" class="page-link" @click="fetchPosts(pagination.prev_page_url)">Previous</a>
                    </li>

                    <li class="page-item disabled">
                        <a href="javascript:;" class="page-link text-dark">
                            Page {{ pagination.current_page }} of {{ pagination.last_page }}
                        </a>
                    </li>

                    <li v-bind:class="[{disabled:!pagination.next_page_url}]" class="page-item">
                        <a href="javascript:;" class="page-link" @click="fetchPosts(pagination.next_page_url)">Next</a>
                    </li>
                </ul>
            </nav>

            <div class="card card-body mb-2" v-for="post in posts" v-bind:key="post.id">
                <h3>{{ post.title }}</h3>
                <p>{{ post.description }}</p>
                <hr>
                <button class="btn btn-warning" @click="editPost(post)">Edit</button>
                <button class="btn btn-danger" @click="deletePost(post.id)">Delete</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            posts: [],
            post: {
                id : '',
                title : '',
                description : ''
            },
            post_id: '',
            pagination: {},
            edit: false
        }
    },

    created() {
        this.fetchPosts();
    },

    methods: {
        fetchPosts(page_url) {
            let vm = this;
            page_url = page_url || 'api/posts';
            fetch(page_url)
            .then(res => res.json())
            .then(res => {
                console.log(res.data);
                this.posts = res.data;
                vm.makePagination(res);
            })
            .catch(err => console.log(err));
        },

        makePagination(res) {
            let pagination = {
                current_page : res.current_page,
                last_page : res.last_page,
                next_page_url : res.next_page_url,
                prev_page_url : res.prev_page_url
            }

            this.pagination = pagination;
        },

        deletePost(id) {
            if(confirm('Are you Sure?')) {
                fetch(`api/posts/${id}`, {
                    method : 'DELETE'
                })
                .then(res => res.json)
                .then(data => {
                    alert('Post Removed!!!');
                    this.fetchPosts();
                })
                .catch(err=>console.log(err));
            }
        },

        addPost() {
            if(this.edit === false) {
                //Add Post
                fetch('api/posts', {
                    method : 'post',
                    body : JSON.stringify(this.post),
                    headers : {
                        'content-type' : 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.post.title = '';
                    this.post.description = '';
                    alert("Post Added");
                    this.fetchPosts();
                })
                .catch(err => console.log(err));
            }

            else{
                //Update Post
                fetch(`api/posts/${this.post.id}`, {
                    method : 'put',
                    body : JSON.stringify(this.post),
                    headers : {
                        'content-type' : 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.post.title = '';
                    this.post.description = '';
                    alert("Post Updated");
                    this.edit = false;
                    this.fetchPosts();
                })
                .catch(err => console.log(err));
            }
        },

        editPost(post) {
            this.edit = true;
            this.post.id = post.id;
            this.post.post_id = post.id;
            this.post.title = post.title;
            this.post.description = post.description;
        }
    }
}
</script>
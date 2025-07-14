@extends('version-platform-manager::layouts.admin')

@section('page-title', 'Users Management')

@section('title', 'Users Management')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id="users-app">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Users Management</h1>
                <p class="mt-2 text-sm text-gray-600">Manage platform users and their version status</p>
            </div>
            <div class="flex items-center space-x-3">
                <button @click="exportUsers" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export
                </button>
                <button @click="showAddUserModal = true" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add User
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Search</label>
                    <input v-model="filters.search" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Search users...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Version</label>
                    <select v-model="filters.version" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">All Versions</option>
                        <option v-for="version in versions" :key="version.id" :value="version.version">@{{ version.version }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select v-model="filters.status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">All Status</option>
                        <option value="up_to_date">Up to Date</option>
                        <option value="needs_update">Needs Update</option>
                        <option value="outdated">Outdated</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button @click="resetFilters" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Users</h3>
            <p class="mt-1 text-sm text-gray-500">@{{ filteredUsers.length }} users found</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <button @click="sortBy('name')" class="flex items-center space-x-1 hover:text-gray-700">
                                <span>User</span>
                                <svg v-if="sortKey === 'name'" class="w-4 h-4" :class="sortOrder === 'asc' ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <button @click="sortBy('email')" class="flex items-center space-x-1 hover:text-gray-700">
                                <span>Email</span>
                                <svg v-if="sortKey === 'email'" class="w-4 h-4" :class="sortOrder === 'asc' ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <button @click="sortBy('current_version')" class="flex items-center space-x-1 hover:text-gray-700">
                                <span>Current Version</span>
                                <svg v-if="sortKey === 'current_version'" class="w-4 h-4" :class="sortOrder === 'asc' ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="user in paginatedUsers" :key="user.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">@{{ user.name.charAt(0).toUpperCase() }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">@{{ user.name }}</div>
                                    <div class="text-sm text-gray-500">@{{ user.role }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">@{{ user.email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                @{{ user.current_version }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="getStatusClass(user.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                @{{ user.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">@{{ formatDate(user.last_login) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button @click="editUser(user)" class="text-blue-600 hover:text-blue-900">Edit</button>
                                <button @click="sendUpdateNotification(user)" class="text-green-600 hover:text-green-900">Notify</button>
                                <button @click="deleteUser(user)" class="text-red-600 hover:text-red-900">Delete</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    <button @click="previousPage" :disabled="currentPage === 1" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        Previous
                    </button>
                    <button @click="nextPage" :disabled="currentPage >= totalPages" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        Next
                    </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">@{{ startIndex + 1 }}</span> to <span class="font-medium">@{{ endIndex }}</span> of <span class="font-medium">@{{ filteredUsers.length }}</span> results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                            <button @click="previousPage" :disabled="currentPage === 1" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <button v-for="page in visiblePages" :key="page" @click="goToPage(page)" :class="page === currentPage ? 'bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'" class="relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                @{{ page }}
                            </button>
                            <button @click="nextPage" :disabled="currentPage >= totalPages" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            users: [
                { id: 1, name: 'John Doe', email: 'john@example.com', role: 'Admin', current_version: '2.1.0', status: 'up_to_date', last_login: '2024-01-15T10:30:00Z' },
                { id: 2, name: 'Jane Smith', email: 'jane@example.com', role: 'User', current_version: '2.0.0', status: 'needs_update', last_login: '2024-01-14T15:45:00Z' },
                { id: 3, name: 'Bob Johnson', email: 'bob@example.com', role: 'User', current_version: '1.9.0', status: 'outdated', last_login: '2024-01-13T09:20:00Z' },
                { id: 4, name: 'Alice Brown', email: 'alice@example.com', role: 'User', current_version: '2.1.0', status: 'up_to_date', last_login: '2024-01-15T14:15:00Z' },
                { id: 5, name: 'Charlie Wilson', email: 'charlie@example.com', role: 'User', current_version: '2.0.0', status: 'needs_update', last_login: '2024-01-12T11:30:00Z' }
            ],
            versions: [
                { id: 1, version: '2.1.0' },
                { id: 2, version: '2.0.0' },
                { id: 3, version: '1.9.0' }
            ],
            filters: {
                search: '',
                version: '',
                status: ''
            },
            sortKey: 'name',
            sortOrder: 'asc',
            currentPage: 1,
            itemsPerPage: 10,
            showAddUserModal: false
        }
    },
    computed: {
        filteredUsers() {
            let filtered = this.users;
            
            if (this.filters.search) {
                filtered = filtered.filter(user => 
                    user.name.toLowerCase().includes(this.filters.search.toLowerCase()) ||
                    user.email.toLowerCase().includes(this.filters.search.toLowerCase())
                );
            }
            
            if (this.filters.version) {
                filtered = filtered.filter(user => user.current_version === this.filters.version);
            }
            
            if (this.filters.status) {
                filtered = filtered.filter(user => user.status === this.filters.status);
            }
            
            // Sort
            filtered.sort((a, b) => {
                let aVal = a[this.sortKey];
                let bVal = b[this.sortKey];
                
                if (this.sortOrder === 'desc') {
                    [aVal, bVal] = [bVal, aVal];
                }
                
                if (typeof aVal === 'string') {
                    return aVal.localeCompare(bVal);
                }
                return aVal - bVal;
            });
            
            return filtered;
        },
        totalPages() {
            return Math.ceil(this.filteredUsers.length / this.itemsPerPage);
        },
        startIndex() {
            return (this.currentPage - 1) * this.itemsPerPage;
        },
        endIndex() {
            return Math.min(this.startIndex + this.itemsPerPage, this.filteredUsers.length);
        },
        paginatedUsers() {
            return this.filteredUsers.slice(this.startIndex, this.endIndex);
        },
        visiblePages() {
            const pages = [];
            const start = Math.max(1, this.currentPage - 2);
            const end = Math.min(this.totalPages, this.currentPage + 2);
            
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            
            return pages;
        }
    },
    methods: {
        sortBy(key) {
            if (this.sortKey === key) {
                this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortKey = key;
                this.sortOrder = 'asc';
            }
            this.currentPage = 1;
        },
        resetFilters() {
            this.filters = {
                search: '',
                version: '',
                status: ''
            };
            this.currentPage = 1;
        },
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
        goToPage(page) {
            this.currentPage = page;
        },
        getStatusClass(status) {
            const classes = {
                'up_to_date': 'bg-green-100 text-green-800',
                'needs_update': 'bg-yellow-100 text-yellow-800',
                'outdated': 'bg-red-100 text-red-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        },
        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString();
        },
        editUser(user) {
            alert(`Edit user: ${user.name}`);
        },
        sendUpdateNotification(user) {
            alert(`Sending update notification to: ${user.email}`);
        },
        deleteUser(user) {
            if (confirm(`Are you sure you want to delete ${user.name}?`)) {
                this.users = this.users.filter(u => u.id !== user.id);
            }
        },
        exportUsers() {
            alert('Exporting users data...');
        }
    }
}).mount('#users-app');
</script>
@endsection 
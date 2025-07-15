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
                <button @click="exportUsers" class="inline-flex items-center px-4 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export
                </button>
                <button @click="showAddUserModal = true" class="inline-flex items-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add User
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-lg rounded-xl border border-gray-200 mb-6">
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Search</label>
                    <input v-model="filters.search" type="text" class="block w-full px-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200 bg-white hover:bg-gray-50" placeholder="Search users...">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Version</label>
                    <select v-model="filters.version" class="block w-full px-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200 bg-white hover:bg-gray-50">
                        <option value="">All Versions</option>
                        <option v-for="version in versions" :key="version.id" :value="version.version">@{{ version.version }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Status</label>
                    <select v-model="filters.status" class="block w-full px-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200 bg-white hover:bg-gray-50">
                        <option value="">All Status</option>
                        <option value="up_to_date">Up to Date</option>
                        <option value="needs_update">Needs Update</option>
                        <option value="outdated">Outdated</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button @click="resetFilters" class="w-full inline-flex justify-center items-center px-4 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900">Users</h3>
            <p class="mt-1 text-sm text-gray-600">{{ $users->total() }} users found</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $user->role ?? ($user->getRoleNames()->first() ?? 'N/A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('version-manager.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">Edit</a>
                                <form action="{{ route('version-manager.users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium ml-3 transition-colors duration-200" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    <p class="text-sm font-medium">No users found</p>
                                    <p class="text-xs text-gray-400 mt-1">Try adjusting your filters</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $users->links('pagination::tailwind') }}
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
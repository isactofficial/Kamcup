<div class="nav-links">
                    <a href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.articles.index') }}"
                        class="{{ request()->routeIs('admin.articles.index') || request()->routeIs('admin.articles.create') || request()->routeIs('admin.articles.edit') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i> Manage Articles
                    </a>
                    <a href="{{ route('admin.articles.approval') }}"
                        class="{{ request()->routeIs('admin.articles.approval') ? 'active' : '' }}">
                        <i class="fas fa-check-circle"></i> Article Approval
                    </a>
                    <a href="{{ route('admin.galleries.index') }}"
                        class="{{ request()->routeIs('admin.galleries.index') || request()->routeIs('admin.galleries.create') || request()->routeIs('admin.galleries.edit') ? 'active' : '' }}">
                        <i class="fas fa-image"></i> Manage Galleries
                    </a>
                    <a href="{{ route('admin.galleries.approval') }}"
                        class="{{ request()->routeIs('admin.galleries.approval') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-check"></i> Gallery Approval
                    </a>
                    <a href="{{ route('admin.tournaments.index') }}"
                        class="{{ request()->routeIs('admin.tournaments.index') || request()->routeIs('admin.tournaments.create') || request()->routeIs('admin.tournaments.edit') ? 'active' : '' }}">
                        <i class="fas fa-trophy"></i> Manage Tournaments
                    </a>

                    <a href="{{ route('admin.teams.index') }}"
                        class="{{ request()->routeIs('admin.teams.*') ? 'active' : '' }}">
                        <i class="fas fa-users fa-fw"></i> Manage Team
                    </a>
                    
                    <a href="{{ route('admin.matches.index') }}"
                        class="{{ request()->routeIs('admin.matches.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar"></i>Tournament Schedule
                    </a>
                    <a href="{{ route('admin.host-requests.index') }}"
                        class="{{ request()->routeIs('admin.host-requests.index') || request()->routeIs('admin.host-requests.show') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list"></i> Tournament Host Requests
                    </a>
                    <a href="{{ route('admin.sponsors.index') }}"
                        class="{{ request()->routeIs('admin.sponsors.index') || request()->routeIs('admin.sponsors.create') || request()->routeIs('admin.sponsors.edit') ? 'active' : '' }}">
                        <i class="fas fa-plus"></i> Manage Sponsors
                    </a>
                    <a href="{{ route('admin.donations.index') }}"
                        class="{{ request()->routeIs('admin.donations.index') || request()->routeIs('admin.donations.show') ? 'active' : '' }}">
                        <i class="fas fa-donate"></i>Sponsors/Donations
                    </a>
                    <a href="{{ route('admin.messages.index') }}"
                        class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                        <i class="fas fa-envelope"></i> Manajemen Kontak
                    </a>

                    <a href="{{ route('admin.userpages.komunitas') }}" class="{{ request()->routeIs('admin.userpages.komunitas') ? 'active' : '' }}">
                        <i class="fas fa-users me-2"></i> Komunitas
                    </a>
                    <a href="{{ route('admin.userpages.teman') }}" class="{{ request()->routeIs('admin.userpages.teman') ? 'active' : '' }}">
                        <i class="fas fa-user-friends me-2"></i> Teman
                    </a>
                    <a href="{{ route('admin.feeds.index') }}" class="{{ request()->routeIs('admin.feeds.index') ? 'active' : '' }}">
                        <i class="fas fa-rss me-2"></i> Feeds
                    </a>
                </div>

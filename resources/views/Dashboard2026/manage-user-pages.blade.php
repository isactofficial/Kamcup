<div class="card border-0 rounded-3 shadow-sm mb-4" style="border: 1px solid rgba(203, 39, 134, 0.1);">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold m-0" style="color: #cb2786;">User Pages Management</h4>
            <a href="#" class="btn btn-sm px-3" style="background-color: #cb27861A; color: #cb2786; border-radius: 8px; font-weight: 600;">View All</a>
        </div>
        
        <div class="row g-3">
            @foreach([
                ['Komunitas', 0, '#cb2786', 'fas fa-users'],
                ['Teman', 0, '#f4b704', 'fas fa-user-friends'],
                ['Feeds', 0, '#00617a', 'fas fa-rss']
            ] as $page)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center p-4" style="border: 1px solid {{ $page[2] }}1A; border-radius: 12px;">
                    <div class="rounded-circle mx-auto mb-3 p-3 d-inline-block" style="background-color: {{ $page[2] }}20; width: 60px; height: 60px;">
                        <i class="{{ $page[3] }}" style="color: {{ $page[2] }}; font-size: 1.5rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: {{ $page[2] }}">{{ $page[0] }}</h5>
                    <h3 class="fw-bold mb-3" style="color: {{ $page[2] }}">{{ $page[1] }}</h3>
                    <p class="text-muted small mb-3">Views this month</p>
                    <a href="{{ route('admin.userpages.' . strtolower($page[0])) }}" class="btn btn-sm w-100" style="background-color: {{ $page[2] }}; color: #fff; border-radius: 8px; font-weight: 600;">
                        Kelola {{ $page[0] }} <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>


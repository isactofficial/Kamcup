<div class="col-md-6 col-lg-4 scroll-reveal" style="transition-delay: {{ $index * 0.05 }}s;">
    <div class="card h-100 profile-info-card border-0 community-item shadow-sm position-relative">
        <div class="card-body p-4 d-flex flex-column" style="z-index: 2;">
            @if($community->is_official)
                <div class="position-absolute top-0 end-0 mt-3 me-3">
                    <span class="badge px-3" style="background-color: #00617a; color: #fff; font-size: 0.7rem; border-radius: 8px;">
                        Official
                    </span>
                </div>
            @elseif($community->status == 'private')
                 <div class="position-absolute top-0 end-0 mt-3 me-3">
                    <span class="badge px-3" style="background-color: #f4b704; color: #212529; font-size: 0.7rem; border-radius: 8px;">
                        Private
                    </span>
                </div>
            @endif

            <div class="d-flex align-items-center mb-4">
                <div class="community-logo d-flex align-items-center justify-content-center shadow-sm text-center" 
                     style="background-color: #cb278615; width: 65px; height: 65px; border-radius: 8px; overflow: hidden;">
                    @if($community->image)
                        <img src="{{ asset('storage/' . $community->image) }}" class="w-100 h-100" style="object-fit: contain;">
                    @else
                        <i class="fas fa-users" style="color: #cb2786; font-size: 1.5rem;"></i>
                    @endif
                </div>
                <div class="ms-3">
                    <span class="text-uppercase small fw-bold tracking-wider" style="color: #cb2786; letter-spacing: 1px; font-size: 0.75rem;">
                        {{ $community->category }}
                    </span>
                    <h5 class="fw-bold mb-0 text-dark">{{ $community->name }} {{ $community->is_official ? '[Official]' : '' }}</h5>
                    <small class="text-muted">by {{ $community->creator->name }}</small>
                </div>
            </div>
            
            <div class="mb-4">
                <p class="text-muted small mb-0 line-clamp-2">{{ $community->description ?? 'Deskripsi singkat mengenai komunitas ' . $community->name . ' yang inspiratif and aktif.' }}</p>
            </div>

            <div class="mt-auto pt-3 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="avatar-stack">
                            <div class="avatar-pill" style="background-color: #cb278630;"></div>
                            <div class="avatar-pill" style="background-color: #00617a30;"></div>
                            <div class="avatar-pill" style="background-color: #f4b70430;"></div>
                        </div>
                        <span class="ms-2 text-muted fw-semibold small">{{ $community->members_count }} Member</span>
                    </div>
                    
                    @if($isJoined)
                        <span class="badge py-2 px-3 bg-light text-muted border" style="border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-check-circle text-success me-1"></i> Member
                        </span>
                    @else
                        <button class="btn join-btn px-4 py-2" style="background-color: #cb2786; color: #fff; border-radius: 10px; font-weight: 600; font-size: 0.9rem;">
                            Join <i class="fas fa-plus ms-1" style="font-size: 0.7rem;"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

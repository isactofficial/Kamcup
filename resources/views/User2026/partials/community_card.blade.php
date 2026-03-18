{{-- Discord-style Community Card --}}
<div class="community-card-item" 
     data-name="{{ strtolower($community->name) }}" 
     data-category="{{ strtolower($community->category) }}">
    <a href="{{ route('user2026.komunitas.show', $community->slug) }}" class="card-link">
        <div class="dc-card {{ $isJoined ? 'is-joined' : '' }}">

            <!-- Banner / Cover Area -->
            <div class="dc-card-banner">
                @if($community->image)
                    <img src="{{ asset('storage/' . $community->image) }}" 
                         alt="{{ $community->name }}" 
                         class="banner-bg">
                @else
                    <div class="banner-gradient"></div>
                @endif

                <!-- Badges top-right -->
                <div class="dc-badges">
                    @if($community->is_official)
                        <span class="dc-badge official">
                            <i class="fas fa-shield-halved"></i> Official
                        </span>
                    @elseif($community->status == 'private')
                        <span class="dc-badge private">
                            <i class="fas fa-lock"></i> Private
                        </span>
                    @endif

                    @if($isJoined)
                        <span class="dc-badge joined">
                            <i class="fas fa-check"></i>
                        </span>
                    @endif
                </div>

                <!-- Avatar / Icon overlapping banner -->
                <div class="dc-avatar">
                    @if($community->image)
                        <img src="{{ asset('storage/' . $community->image) }}" 
                             alt="{{ $community->name }}">
                    @else
                        <div class="dc-avatar-fallback">
                            <i class="fas fa-users"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Body -->
            <div class="dc-card-body">
                <!-- Name & Category -->
                <div class="dc-name-row">
                    <h5 class="dc-name">{{ $community->name }}</h5>
                    <span class="dc-category">{{ $community->category }}</span>
                </div>

                <p class="dc-desc">{{ Str::limit($community->description ?? 'Bergabung dan jadilah bagian dari komunitas ' . $community->name . ' yang aktif dan inspiratif.', 80) }}</p>

                <!-- Footer row -->
                <div class="dc-card-footer">
                    <div class="dc-meta">
                        <span class="dc-meta-item">
                            <span class="dot dot-green"></span>
                            {{ $community->members_count }} Member
                        </span>
                        <span class="dc-meta-item muted">
                            by {{ $community->creator->name }}
                        </span>
                    </div>

                    <div class="dc-action">
                        @if($isJoined)
                            <span class="dc-btn joined-btn">Bergabung</span>
                        @else
                            <span class="dc-btn join-btn">Lihat</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </a>
</div>

<style>
/* ── Card Link ───────────────────────────────────────────── */
.community-card-item {
    /* wrapper, no styles needed */
}

.card-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

/* ── Discord Card ────────────────────────────────────────── */
.dc-card {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e8eaed;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    position: relative;
}

.dc-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    border-color: #cb278640;
}

.dc-card.is-joined {
    border-color: #2ecc7130;
}

.dc-card.is-joined:hover {
    border-color: #2ecc7170;
    box-shadow: 0 8px 24px rgba(46,204,113,0.1);
}

/* ── Banner ──────────────────────────────────────────────── */
.dc-card-banner {
    position: relative;
    height: 72px;
    background: #f0f2f5;
    overflow: visible; /* allow avatar to overflow */
}

.banner-bg {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(0.6) saturate(1.2);
}

.banner-gradient {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #cb278625 0%, #00617a25 100%);
}

/* ── Badges ──────────────────────────────────────────────── */
.dc-badges {
    position: absolute;
    top: 8px;
    right: 8px;
    display: flex;
    gap: 4px;
    z-index: 2;
}

.dc-badge {
    font-size: 0.65rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 4px;
    letter-spacing: 0.3px;
}

.dc-badge.official {
    background: #00617a;
    color: #fff;
}

.dc-badge.private {
    background: #f4b704;
    color: #212529;
}

.dc-badge.joined {
    background: #2ecc71;
    color: #fff;
    width: 22px;
    height: 22px;
    padding: 0;
    border-radius: 50%;
    justify-content: center;
    font-size: 0.6rem;
}

/* ── Avatar ──────────────────────────────────────────────── */
.dc-avatar {
    position: absolute;
    bottom: -20px;
    left: 16px;
    width: 48px;
    height: 48px;
    border-radius: 10px;
    border: 3px solid #ffffff;
    overflow: hidden;
    background: #e8eaed;
    z-index: 3;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.dc-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.dc-avatar-fallback {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #cb278620, #00617a20);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #cb2786;
    font-size: 1.1rem;
}

/* ── Body ────────────────────────────────────────────────── */
.dc-card-body {
    padding: 28px 16px 14px;
}

.dc-name-row {
    display: flex;
    align-items: baseline;
    gap: 8px;
    margin-bottom: 6px;
    flex-wrap: wrap;
}

.dc-name {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0;
    line-height: 1.3;
    transition: color 0.2s;
}

.dc-card:hover .dc-name {
    color: #cb2786;
}

.dc-category {
    font-size: 0.7rem;
    font-weight: 600;
    color: #cb2786;
    background: #cb278612;
    padding: 2px 7px;
    border-radius: 4px;
    white-space: nowrap;
}

.dc-desc {
    font-size: 0.8rem;
    color: #777;
    margin: 0 0 12px;
    line-height: 1.5;
    min-height: 2.4rem;
}

/* ── Footer ──────────────────────────────────────────────── */
.dc-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-top: 1px solid #f0f2f5;
    padding-top: 10px;
    gap: 8px;
}

.dc-meta {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.dc-meta-item {
    font-size: 0.75rem;
    font-weight: 600;
    color: #555;
    display: flex;
    align-items: center;
    gap: 5px;
}

.dc-meta-item.muted {
    color: #aaa;
    font-weight: 400;
}

.dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    flex-shrink: 0;
}

.dot-green { background: #2ecc71; }

/* ── Action Button ───────────────────────────────────────── */
.dc-btn {
    font-size: 0.75rem;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 8px;
    white-space: nowrap;
    letter-spacing: 0.2px;
}

.join-btn {
    background: #cb2786;
    color: #fff;
    transition: background 0.2s;
}

.dc-card:hover .join-btn {
    background: #a81e6e;
}

.joined-btn {
    background: #f0faf4;
    color: #2ecc71;
    border: 1px solid #2ecc7140;
}
</style>
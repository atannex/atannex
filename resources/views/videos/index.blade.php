@extends('videos.layouts.app')

@section('app')

<div class="filter-strip">
    <div style="max-width:1440px;margin:0 auto;padding:0 16px">
        <div id="fscroll">
            <button class="cat-pill active" onclick="filterCat(this)">All Videos</button>
            <button class="cat-pill" onclick="filterCat(this)" data-cat="World">World</button>
            <button class="cat-pill" onclick="filterCat(this)" data-cat="Politics">Politics</button>
            <button class="cat-pill" onclick="filterCat(this)" data-cat="Business">Business</button>
            <button class="cat-pill" onclick="filterCat(this)" data-cat="Technology">Technology</button>
            <button class="cat-pill" onclick="filterCat(this)" data-cat="Sport">Sport</button>
            <button class="cat-pill" onclick="filterCat(this)" data-cat="Science">Science</button>
            <button class="cat-pill" onclick="filterCat(this)" data-cat="Health">Health</button>
            <button class="cat-pill" onclick="filterCat(this)" data-cat="exclusive">★ Exclusive</button>
        </div>
    </div>
</div>

<main style="max-width:1440px;margin:0 auto;padding:24px 16px 64px">

    @include('videos.sections.carousel')

    <div style="display:flex;gap:24px;align-items:flex-start">
        <div style="flex:1;min-width:0">
            <div class="live-strip fa1" style="margin-bottom:30px">
                <div style="display:flex;align-items:center;gap:10px">
                    <div style="display:flex;align-items:center;gap:5px;background:var(--red);border-radius:4px;padding:4px 10px;flex-shrink:0">
                        <span class="live-dot"></span>
                        <span style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:11px;letter-spacing:2px;color:#fff">LIVE
                            NOW</span>
                    </div>
                    <div>
                        <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:15px;color:#fff">
                            Markets React: Federal Reserve Emergency Press Conference</p>
                        <p style="font-family:'Barlow Condensed',sans-serif;font-size:11px;color:var(--txt2);margin-top:2px">
                            31,400 watching &nbsp;·&nbsp; Business &nbsp;·&nbsp; Grace Mensah</p>
                    </div>
                </div>
                <a href="video-show.html" class="live-watch-btn" style="text-decoration:none"><svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z" />
                    </svg>Watch Live</a>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px" class="fa2">
                <div class="sec-hd" style="margin-bottom:0;flex:1;min-width:0">
                    <div class="sec-bar"></div>
                    <span style="font-family:'Bebas Neue',sans-serif;font-size:20px;letter-spacing:3px;color:#fff;white-space:nowrap">Latest
                        Videos</span>
                </div>
                <div style="display:flex;align-items:center;gap:8px;flex-shrink:0">
                    <button class="vbtn on" id="gBtn" onclick="setView('grid')" title="Grid"><svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 3h8v8H3zm10 0h8v8h-8zm0 10h8v8h-8zM3 13h8v8H3z" />
                        </svg></button>
                    <button class="vbtn" id="lBtn" onclick="setView('list')" title="List"><svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 4h18v2H3zm0 7h18v2H3zm0 7h18v2H3z" />
                        </svg></button>
                    <div style="width:1px;height:18px;background:var(--border2)"></div>
                    <span style="font-family:'Barlow Condensed',sans-serif;font-size:11px;color:var(--txt3);letter-spacing:1px">124
                        videos</span>
                </div>
            </div>

            <div id="videoGrid" class="fa2">

                <a href="video-show.html" class="v-card" data-cat="World" data-excl="true">
                    <div class="v-thumb">
                        <div class="v-thumb-inner"><img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=640&auto=format&fit=crop&q=75" alt="Climate Summit" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block;position:absolute;inset:0">
                        </div>
                        <div class="v-play-ov">
                            <div class="v-play-circle"><svg width="16" height="16" fill="white" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z" />
                                </svg></div>
                        </div>
                        <div class="v-excl-pill">Exclusive</div><span class="v-dur">14:32</span>
                    </div>
                    <div class="v-body">
                        <div style="display:flex;align-items:center;justify-content:space-between"><span class="cat-badge" style="background:#D9001B;color:#fff">World</span><span class="v-meta">Mar 11, 2026</span></div>
                        <p class="v-title">Global Leaders Convene for Historic Climate Summit in Geneva — What's at
                            Stake</p>
                        <div class="v-reporter-row">
                            <div class="r-av" style="overflow:hidden;padding:0"><img src="https://images.unsplash.com/photo-1531123897727-8f129e1688ce?w=40&auto=format&fit=crop&q=80" alt="SA" style="width:100%;height:100%;object-fit:cover;display:block"></div>
                            <span class="v-meta">Sarah Acheampong</span><span class="v-meta v-views"><svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                </svg>2.4M</span>
                        </div>
                    </div>
                </a>

                <a href="video-show.html" class="v-card" data-cat="Politics">
                    <div class="v-thumb">
                        <div class="v-thumb-inner"><img src="https://images.unsplash.com/photo-1541872703-74c5e44368f9?w=640&auto=format&fit=crop&q=75" alt="US Capitol" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block;position:absolute;inset:0">
                        </div>
                        <div class="v-play-ov">
                            <div class="v-play-circle"><svg width="16" height="16" fill="white" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z" />
                                </svg></div>
                        </div><span class="v-dur">8:47</span>
                    </div>
                    <div class="v-body">
                        <div style="display:flex;align-items:center;justify-content:space-between"><span class="cat-badge" style="background:#1d4ed8;color:#fff">Politics</span><span class="v-meta">Mar 11, 2026</span></div>
                        <p class="v-title">Senate Votes on Landmark Infrastructure Package — Full Analysis</p>
                        <div class="v-reporter-row">
                            <div class="r-av" style="overflow:hidden;padding:0"><img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=40&auto=format&fit=crop&q=80" alt="DO" style="width:100%;height:100%;object-fit:cover;display:block"></div>
                            <span class="v-meta">David Osei</span><span class="v-meta v-views"><svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                </svg>1.1M</span>
                        </div>
                    </div>
                </a>

                <a href="video-show.html" class="v-card" data-cat="Business">
                    <div class="v-thumb">
                        <div class="v-thumb-inner"><img src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=640&auto=format&fit=crop&q=75" alt="Stock Market" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block;position:absolute;inset:0">
                        </div>
                        <div class="v-play-ov">
                            <div class="v-play-circle"><svg width="16" height="16" fill="white" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z" />
                                </svg></div>
                        </div>
                        <div class="v-live-pill"><span class="live-dot" style="width:5px;height:5px"></span><span style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:9px;letter-spacing:1.5px;color:#fff">LIVE</span>
                        </div><span class="v-dur">LIVE</span>
                    </div>
                    <div class="v-body">
                        <div style="display:flex;align-items:center;justify-content:space-between"><span class="cat-badge" style="background:#c2410c;color:#fff">Business</span><span class="v-meta">Mar 11, 2026</span></div>
                        <p class="v-title">Markets React: Federal Reserve Emergency Press Conference</p>
                        <div class="v-reporter-row">
                            <div class="r-av" style="overflow:hidden;padding:0"><img src="https://images.unsplash.com/photo-1589156288859-f0cb0d82b065?w=40&auto=format&fit=crop&q=80" alt="GM" style="width:100%;height:100%;object-fit:cover;display:block"></div>
                            <span class="v-meta">Grace Mensah</span><span class="v-meta v-views"><svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                </svg>31.4K</span>
                        </div>
                    </div>
                </a>

                <a href="video-show.html" class="v-card" data-cat="Technology">
                    <div class="v-thumb">
                        <div class="v-thumb-inner"><img src="https://images.unsplash.com/photo-1677442135703-1787eea5ce01?w=640&auto=format&fit=crop&q=75" alt="AI Technology" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block;position:absolute;inset:0">
                        </div>
                        <div class="v-play-ov">
                            <div class="v-play-circle"><svg width="16" height="16" fill="white" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z" />
                                </svg></div>
                        </div><span class="v-dur">11:08</span>
                    </div>
                    <div class="v-body">
                        <div style="display:flex;align-items:center;justify-content:space-between"><span class="cat-badge" style="background:#7c3aed;color:#fff">Technology</span><span class="v-meta">Mar 10, 2026</span></div>
                        <p class="v-title">AI Regulation Crisis: EU Targets Big Tech with Sweeping New Laws</p>
                        <div class="v-reporter-row">
                            <div class="r-av" style="overflow:hidden;padding:0"><img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=40&auto=format&fit=crop&q=80" alt="KA" style="width:100%;height:100%;object-fit:cover;display:block"></div>
                            <span class="v-meta">Kwame Asante</span><span class="v-meta v-views"><svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                </svg>743K</span>
                        </div>
                    </div>
                </a>

                <a href="video-show.html" class="v-card" data-cat="Business">
                    <div class="v-thumb">
                        <div class="v-thumb-inner"><img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=640&auto=format&fit=crop&q=75" alt="Oil Refinery" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block;position:absolute;inset:0">
                        </div>
                        <div class="v-play-ov">
                            <div class="v-play-circle"><svg width="16" height="16" fill="white" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z" />
                                </svg></div>
                        </div><span class="v-dur">6:55</span>
                    </div>
                    <div class="v-body">
                        <div style="display:flex;align-items:center;justify-content:space-between"><span class="cat-badge" style="background:#c2410c;color:#fff">Business</span><span class="v-meta">Mar 10, 2026</span></div>
                        <p class="v-title">Oil Futures Spike as OPEC+ Announces Surprise Production Cuts</p>
                        <div class="v-reporter-row">
                            <div class="r-av" style="overflow:hidden;padding:0"><img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=40&auto=format&fit=crop&q=80" alt="NA" style="width:100%;height:100%;object-fit:cover;display:block"></div>
                            <span class="v-meta">Nana Ama</span><span class="v-meta v-views"><svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                </svg>512K</span>
                        </div>
                    </div>
                </a>

                <a href="video-show.html" class="v-card" data-cat="Science" data-excl="true">
                    <div class="v-thumb">
                        <div class="v-thumb-inner"><img src="https://images.unsplash.com/photo-1579154204601-01588f351e67?w=640&auto=format&fit=crop&q=75" alt="Science Lab" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block;position:absolute;inset:0">
                        </div>
                        <div class="v-play-ov">
                            <div class="v-play-circle"><svg width="16" height="16" fill="white" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z" />
                                </svg></div>
                        </div>
                        <div class="v-excl-pill">Exclusive</div><span class="v-dur">18:30</span>
                    </div>
                    <div class="v-body">
                        <div style="display:flex;align-items:center;justify-content:space-between"><span class="cat-badge" style="background:#0f766e;color:#fff">Science</span><span class="v-meta">Mar 9, 2026</span></div>
                        <p class="v-title">Scientists Identify Groundbreaking Cancer Treatment Pathway</p>
                        <div class="v-reporter-row">
                            <div class="r-av" style="overflow:hidden;padding:0"><img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=40&auto=format&fit=crop&q=80" alt="EB" style="width:100%;height:100%;object-fit:cover;display:block"></div>
                            <span class="v-meta">Dr. Esi Brew</span><span class="v-meta v-views"><svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                </svg>2.1M</span>
                        </div>
                    </div>
                </a>

                <a href="video-show.html" class="v-card" data-cat="World">
                    <div class="v-thumb">
                        <div class="v-thumb-inner"><img src="https://images.unsplash.com/photo-1541872703-74c5e44368f9?w=640&auto=format&fit=crop&q=75" alt="United Nations" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block;position:absolute;inset:0">
                        </div>
                        <div class="v-play-ov">
                            <div class="v-play-circle"><svg width="16" height="16" fill="white" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z" />
                                </svg></div>
                        </div><span class="v-dur">9:14</span>
                    </div>
                    <div class="v-body">
                        <div style="display:flex;align-items:center;justify-content:space-between"><span class="cat-badge" style="background:#D9001B;color:#fff">World</span><span class="v-meta">Mar 9, 2026</span></div>
                        <p class="v-title">Diplomatic Crisis Unfolds at UN Security Council Emergency Session</p>
                        <div class="v-reporter-row">
                            <div class="r-av" style="overflow:hidden;padding:0"><img src="https://images.unsplash.com/photo-1541872703-74c5e44368f9?w=640&auto=format&fit=crop&q=75" alt="JA" style="width:100%;height:100%;object-fit:cover;display:block"></div>
                            <span class="v-meta">James Agyei</span><span class="v-meta v-views"><svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                </svg>3.8M</span>
                        </div>
                    </div>
                </a>

                <a href="video-show.html" class="v-card" data-cat="Politics" data-excl="true">
                    <div class="v-thumb">
                        <div class="v-thumb-inner"><img src="https://images.unsplash.com/photo-1540910419892-4a36d2c3266c?w=640&auto=format&fit=crop&q=75" alt="Election Night" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block;position:absolute;inset:0">
                        </div>
                        <div class="v-play-ov">
                            <div class="v-play-circle"><svg width="16" height="16" fill="white" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z" />
                                </svg></div>
                        </div>
                        <div class="v-excl-pill">Exclusive</div><span class="v-dur">22:05</span>
                    </div>
                    <div class="v-body">
                        <div style="display:flex;align-items:center;justify-content:space-between"><span class="cat-badge" style="background:#1d4ed8;color:#fff">Politics</span><span class="v-meta">Mar 8, 2026</span></div>
                        <p class="v-title">Election Night Special: Full Results from 12 Key Districts</p>
                        <div class="v-reporter-row">
                            <div class="r-av" style="overflow:hidden;padding:0"><img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?w=40&auto=format&fit=crop&q=80" alt="AD" style="width:100%;height:100%;object-fit:cover;display:block"></div>
                            <span class="v-meta">Abena Doku</span><span class="v-meta v-views"><svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                </svg>2.9M</span>
                        </div>
                    </div>
                </a>

                <a href="video-show.html" class="v-card" data-cat="Technology">
                    <div class="v-thumb">
                        <div class="v-thumb-inner"><img src="https://images.unsplash.com/photo-1518770660439-4636190af475?w=640&auto=format&fit=crop&q=75" alt="Silicon Valley Tech" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block;position:absolute;inset:0">
                        </div>
                        <div class="v-play-ov">
                            <div class="v-play-circle"><svg width="16" height="16" fill="white" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z" />
                                </svg></div>
                        </div><span class="v-dur">16:40</span>
                    </div>
                    <div class="v-body">
                        <div style="display:flex;align-items:center;justify-content:space-between"><span class="cat-badge" style="background:#7c3aed;color:#fff">Technology</span><span class="v-meta">Mar 7, 2026</span></div>
                        <p class="v-title">Inside Silicon Valley Collapse: What Went Wrong at TerraScale</p>
                        <div class="v-reporter-row">
                            <div class="r-av" style="overflow:hidden;padding:0"><img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=40&auto=format&fit=crop&q=80" alt="KA" style="width:100%;height:100%;object-fit:cover;display:block"></div>
                            <span class="v-meta">Kwame Asante</span><span class="v-meta v-views"><svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                </svg>1.7M</span>
                        </div>
                    </div>
                </a>

            </div>

            <button class="load-more" id="loadMoreBtn">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3" />
                </svg>
                Load More Videos
            </button>

            <!-- EDITORS' PICKS -->
            <section style="margin-top:48px" class="fa3">
                <div class="sec-hd">
                    <div class="sec-bar"></div><span style="font-family:'Bebas Neue',sans-serif;font-size:20px;letter-spacing:3px;color:#fff">Editors'
                        Picks</span>
                </div>
                <div class="sp-grid">

                    <a href="video-show.html" class="sp-card">
                        <div class="sp-inner" style="background:#0a0a0c"><img src="https://images.unsplash.com/photo-1579154204601-01588f351e67?w=600&auto=format&fit=crop&q=75" alt="Science Lab" loading="lazy" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover"></div>
                        <div class="sp-ov"></div>
                        <div class="sp-content">
                            <div style="display:flex;gap:6px;align-items:center;margin-bottom:8px"><span class="cat-badge" style="background:#D9001B;color:#fff">World</span></div>
                            <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:15px;color:#fff;line-height:1.3;margin-bottom:10px;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden">
                                Diplomatic Crisis Unfolds at UN Security Council Emergency Session</p>
                            <div style="display:flex;align-items:center;justify-content:space-between"><span style="font-family:'Barlow Condensed',sans-serif;font-size:11px;color:rgba(255,255,255,.45)">3.8M
                                    views</span>
                                <div class="sp-play"><svg width="12" height="12" fill="white" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg></div>
                            </div>
                        </div>
                    </a>

                    <a href="video-show.html" class="sp-card">
                        <div class="sp-inner" style="background:#0a0a0c"><img src="https://images.unsplash.com/photo-1579154204601-01588f351e67?w=600&auto=format&fit=crop&q=75" alt="Science Lab" loading="lazy" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover"></div>
                        <div class="sp-ov"></div>
                        <div class="sp-content">
                            <div style="display:flex;gap:6px;align-items:center;margin-bottom:8px"><span class="cat-badge" style="background:#0f766e;color:#fff">Science</span><span style="font-family:'Barlow Condensed',sans-serif;font-size:9px;font-weight:700;color:var(--gold);letter-spacing:1px">★
                                    EXCL</span></div>
                            <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:15px;color:#fff;line-height:1.3;margin-bottom:10px;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden">
                                Scientists Identify Groundbreaking Cancer Treatment Pathway</p>
                            <div style="display:flex;align-items:center;justify-content:space-between"><span style="font-family:'Barlow Condensed',sans-serif;font-size:11px;color:rgba(255,255,255,.45)">2.1M
                                    views</span>
                                <div class="sp-play"><svg width="12" height="12" fill="white" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg></div>
                            </div>
                        </div>
                    </a>

                    <a href="video-show.html" class="sp-card">
                        <div class="sp-inner" style="background:#0a0a0c"><img src="https://images.unsplash.com/photo-1579154204601-01588f351e67?w=600&auto=format&fit=crop&q=75" alt="Science Lab" loading="lazy" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover"></div>
                        <div class="sp-ov"></div>
                        <div class="sp-content">
                            <div style="display:flex;gap:6px;align-items:center;margin-bottom:8px"><span class="cat-badge" style="background:#1d4ed8;color:#fff">Politics</span><span style="font-family:'Barlow Condensed',sans-serif;font-size:9px;font-weight:700;color:var(--gold);letter-spacing:1px">★
                                    EXCL</span></div>
                            <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:15px;color:#fff;line-height:1.3;margin-bottom:10px;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden">
                                Election Night Special: Full Results from 12 Key Districts</p>
                            <div style="display:flex;align-items:center;justify-content:space-between"><span style="font-family:'Barlow Condensed',sans-serif;font-size:11px;color:rgba(255,255,255,.45)">2.9M
                                    views</span>
                                <div class="sp-play"><svg width="12" height="12" fill="white" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg></div>
                            </div>
                        </div>
                    </a>

                </div>
            </section>

            <!-- WORLD COVERAGE — wide cards -->
            <section style="margin-top:48px" class="fa">
                <div class="sec-hd">
                    <div class="sec-bar"></div><span style="font-family:'Bebas Neue',sans-serif;font-size:20px;letter-spacing:3px;color:#fff">World
                        Coverage</span><a href="world.html" class="cat-badge" style="background:rgba(217,0,27,.12);color:var(--red);text-decoration:none">See All</a>
                </div>
                <div class="world-list">

                    <a href="video-show.html" class="w-card">
                        <div class="w-thumb" style="background:#060e1a;position:relative"><img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=400&auto=format&fit=crop&q=75" alt="Climate Summit" loading="lazy" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">
                            <div class="v-play-ov" style="position:absolute;inset:0">
                                <div class="v-play-circle"><svg width="14" height="14" fill="white" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg></div>
                            </div><span class="v-dur" style="position:absolute">14:32</span>
                        </div>
                        <div class="w-body">
                            <div style="display:flex;gap:8px;align-items:center"><span class="cat-badge" style="background:#D9001B;color:#fff">World</span><span style="font-family:'Barlow Condensed',sans-serif;font-size:10px;font-weight:700;letter-spacing:1px;color:var(--gold)">EXCLUSIVE</span><span class="v-meta">Mar 11, 2026</span></div>
                            <p class="w-title">Global Leaders Convene for Historic Climate Summit in Geneva — What's
                                at Stake</p>
                            <p class="w-desc">World leaders from 140 nations gathered in Geneva for the most
                                significant climate summit since Paris 2015. ATANNEX senior correspondent reports
                                live from the conference floor.</p>
                            <div style="display:flex;align-items:center;gap:12px">
                                <div style="display:flex;align-items:center;gap:5px">
                                    <div class="r-av" style="width:20px;height:20px;font-size:7px;overflow:hidden;padding:0"><img src="https://images.unsplash.com/photo-1531123897727-8f129e1688ce?w=40&auto=format&fit=crop&q=80" alt="SA" style="width:100%;height:100%;object-fit:cover;display:block">
                                    </div><span class="v-meta">Sarah Acheampong</span>
                                </div><span class="v-meta" style="display:flex;align-items:center;gap:3px"><svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                    </svg>2.4M</span>
                            </div>
                        </div>
                    </a>

                    <a href="video-show.html" class="w-card">
                        <div class="w-thumb" style="background:#0a0808;position:relative"><img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=400&auto=format&fit=crop&q=75" alt="Climate Summit" loading="lazy" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">
                            <div class="v-play-ov" style="position:absolute;inset:0">
                                <div class="v-play-circle"><svg width="14" height="14" fill="white" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg></div>
                            </div><span class="v-dur" style="position:absolute">9:14</span>
                        </div>
                        <div class="w-body">
                            <div style="display:flex;gap:8px;align-items:center"><span class="cat-badge" style="background:#D9001B;color:#fff">World</span><span class="v-meta">Mar 9,
                                    2026</span></div>
                            <p class="w-title">Diplomatic Crisis Unfolds at UN Security Council Emergency Session
                            </p>
                            <p class="w-desc">The United Nations holds its 45th emergency session as border tensions
                                between two member states reach a critical and unprecedented tipping point. ATANNEX
                                analysis from Geneva.</p>
                            <div style="display:flex;align-items:center;gap:12px">
                                <div style="display:flex;align-items:center;gap:5px">
                                    <div class="r-av" style="width:20px;height:20px;font-size:7px;overflow:hidden;padding:0"><img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=40&auto=format&fit=crop&q=80" alt="JA" style="width:100%;height:100%;object-fit:cover;display:block">
                                    </div><span class="v-meta">James Agyei</span>
                                </div><span class="v-meta" style="display:flex;align-items:center;gap:3px"><svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                    </svg>3.8M</span>
                            </div>
                        </div>
                    </a>

                </div>
            </section>

        </div>

        @include('videos.sections.aside')

    </div>
</main>
@endsection

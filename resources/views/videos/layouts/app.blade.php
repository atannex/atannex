@extends('videos.layouts.base')
@section('base')
<div id="preloader">
    <div class="pl-logo">
        <div style="width:36px;height:36px;background:var(--red);border-radius:5px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" fill="white" viewBox="0 0 24 24">
                <path d="M2 3a1 1 0 00-1 1v16a1 1 0 001 1h20a1 1 0 001-1V4a1 1 0 00-1-1H2zm18 14H4V6h16v11zM8 8l8 4-8 4V8z" />
            </svg>
        </div>
        <span style="font-family:'Bebas Neue',sans-serif;font-size:28px;letter-spacing:6px;color:#fff">ATANNEX</span>
    </div>
    <div class="pl-ring"></div>
    <div class="pl-bar-wrap">
        <div class="pl-bar-fill" id="plBar"></div>
    </div>
    <span style="font-family:'Barlow Condensed',sans-serif;font-size:11px;letter-spacing:2px;color:var(--txt3);text-transform:uppercase" id="plTxt">Loading</span>
</div>

<button id="scrollTop" aria-label="Scroll to top" onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <div class="stt-progress" id="sttProgress"></div>
    <svg width="16" height="16" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="position:relative;z-index:1">
        <path d="M12 19V5M5 12l7-7 7 7" />
    </svg>
</button>

<div style="background:var(--red);overflow:hidden;padding:6px 0">
    <div style="display:flex;align-items:center">
        <span style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:11px;letter-spacing:3px;color:#fff;background:rgba(0,0,0,.25);padding:2px 14px;flex-shrink:0;text-transform:uppercase">Breaking</span>
        <div style="overflow:hidden;flex:1;margin-left:12px">
            <div style="display:inline-flex;white-space:nowrap;animation:ticker 38s linear infinite;font-family:'Barlow',sans-serif;font-size:12px;color:#fff">
                <span>● UN Security Council holds emergency session on escalating border conflict &nbsp;|&nbsp; ●
                    Markets rally as Fed signals rate pause for Q2 2026 &nbsp;|&nbsp; ● ATANNEX EXCLUSIVE: Leaked
                    documents reveal energy policy shift &nbsp;|&nbsp; ● Scientists announce 40% efficiency
                    breakthrough in solar cells &nbsp;|&nbsp; ● Three nations sign digital trade agreement in Accra
                    &nbsp;|&nbsp; ● Global inflation index drops for third consecutive quarter
                    &nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <span>● UN Security Council holds emergency session on escalating border conflict &nbsp;|&nbsp; ●
                    Markets rally as Fed signals rate pause for Q2 2026 &nbsp;|&nbsp; ● ATANNEX EXCLUSIVE: Leaked
                    documents reveal energy policy shift &nbsp;|&nbsp;&nbsp;&nbsp;</span>
            </div>
        </div>
    </div>
</div>

<header style="position:sticky;top:0;z-index:500;background:rgba(8,8,12,.97);backdrop-filter:blur(12px);border-bottom:1px solid var(--border)">
    <div style="max-width:1440px;margin:0 auto;padding:0 16px">
        <div style="display:flex;align-items:center;justify-content:space-between;height:58px;gap:12px">
            <div style="display:flex;align-items:center;gap:10px;flex-shrink:0">
                <button id="menuBtn" onclick="openDrawer()" style="display:none;background:none;border:none;cursor:pointer;color:var(--txt2);padding:4px;align-items:center">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <a href="index.html" class="logo-link" style="display:flex;align-items:center;gap:10px">
                    <div style="width:32px;height:32px;background:var(--red);border-radius:4px;display:flex;align-items:center;justify-content:center">
                        <svg width="18" height="18" fill="white" viewBox="0 0 24 24">
                            <path d="M2 3a1 1 0 00-1 1v16a1 1 0 001 1h20a1 1 0 001-1V4a1 1 0 00-1-1H2zm18 14H4V6h16v11zM8 8l8 4-8 4V8z" />
                        </svg>
                    </div>
                    <span style="font-family:'Bebas Neue',sans-serif;font-size:24px;letter-spacing:5px;color:#fff">ATANNEX</span>
                </a>
                <div id="hdiv" style="width:1px;height:22px;background:var(--border2)"></div>
                <span id="hlbl" style="font-family:'Barlow Condensed',sans-serif;font-weight:600;font-size:11px;letter-spacing:2px;color:var(--txt2);text-transform:uppercase">Video</span>
            </div>
            <nav id="deskNav" style="display:flex;align-items:center;gap:20px">
                <a href="index.html" class="nav-l">Home</a>
                <a href="videos.html" class="nav-l nav-act">Videos</a>
                <a href="world.html" class="nav-l">World</a>
                <a href="politics.html" class="nav-l">Politics</a>
                <a href="business.html" class="nav-l">Business</a>
                <a href="tech.html" class="nav-l">Tech</a>
                <a href="sport.html" class="nav-l">Sport</a>
            </nav>
            <div style="display:flex;align-items:center;gap:10px;flex-shrink:0">
                <button style="background:none;border:none;cursor:pointer;color:var(--txt2);display:flex;align-items:center;padding:6px;transition:color .2s" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--txt2)'">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                </button>
                <a href="live.html" id="hlive" style="display:flex;align-items:center;gap:5px;background:var(--red);border-radius:4px;padding:4px 10px;cursor:pointer;text-decoration:none">
                    <span class="live-dot"></span>
                    <span style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:11px;letter-spacing:2px;color:#fff">LIVE</span>
                </a>
                <button style="display:flex;align-items:center;gap:6px;background:var(--bg2);border:1px solid var(--border2);cursor:pointer;color:var(--txt);font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:12px;letter-spacing:1.5px;padding:7px 14px;border-radius:6px;text-transform:uppercase;transition:all .2s">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    <span id="hsi">Sign In</span>
                </button>
            </div>
        </div>
    </div>
    <div class="shimmer-bar"></div>
</header>

<div id="drawer">
    <div class="d-overlay" onclick="closeDrawer()"></div>
    <div class="d-panel">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--border)">
            <span style="font-family:'Bebas Neue',sans-serif;font-size:20px;letter-spacing:4px;color:#fff">ATANNEX</span>
            <button onclick="closeDrawer()" style="background:none;border:none;color:var(--txt2);cursor:pointer"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M18 6L6 18M6 6l12 12" />
                </svg></button>
        </div>
        <div style="padding:8px 0">
            <a href="videos.html" class="d-item on" style="display:block;text-decoration:none">Videos</a>
            <a href="index.html" class="d-item" onclick="closeDrawer()" style="display:block;text-decoration:none">Home</a>
            <a href="world.html" class="d-item" onclick="closeDrawer()" style="display:block;text-decoration:none">World</a>
            <a href="politics.html" class="d-item" onclick="closeDrawer()" style="display:block;text-decoration:none">Politics</a>
            <a href="business.html" class="d-item" onclick="closeDrawer()" style="display:block;text-decoration:none">Business</a>
            <a href="tech.html" class="d-item" onclick="closeDrawer()" style="display:block;text-decoration:none">Technology</a>
            <a href="sport.html" class="d-item" onclick="closeDrawer()" style="display:block;text-decoration:none">Sport</a>
        </div>
    </div>
</div>

@yield('app')

<footer style="border-top:1px solid var(--border);background:var(--bg1);padding:40px 16px">
    <div style="max-width:1440px;margin:0 auto">
        <div style="display:flex;flex-wrap:wrap;gap:32px;justify-content:space-between;margin-bottom:28px">
            <div style="max-width:240px">
                <a href="index.html" style="display:flex;align-items:center;gap:10px;margin-bottom:10px;text-decoration:none">
                    <div style="width:28px;height:28px;background:var(--red);border-radius:3px;display:flex;align-items:center;justify-content:center">
                        <svg width="16" height="16" fill="white" viewBox="0 0 24 24">
                            <path d="M2 3a1 1 0 00-1 1v16a1 1 0 001 1h20a1 1 0 001-1V4a1 1 0 00-1-1H2zm18 14H4V6h16v11zM8 8l8 4-8 4V8z" />
                        </svg>
                    </div>
                    <span style="font-family:'Bebas Neue',sans-serif;font-size:20px;letter-spacing:4px;color:#fff">ATANNEX</span>
                </a>
                <p style="color:var(--txt3);font-size:12px;line-height:1.7">Delivering accurate, impactful
                    journalism. Trusted by millions worldwide.</p>
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:32px">
                <div>
                    <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:11px;letter-spacing:2px;color:#fff;text-transform:uppercase;margin-bottom:10px">
                        Coverage</p>
                    <div style="display:flex;flex-direction:column;gap:8px">
                        <a href="world.html" style="color:var(--txt3);font-size:12px;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--txt3)'">World
                            News</a>
                        <a href="politics.html" style="color:var(--txt3);font-size:12px;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--txt3)'">Politics</a>
                        <a href="business.html" style="color:var(--txt3);font-size:12px;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--txt3)'">Business</a>
                        <a href="tech.html" style="color:var(--txt3);font-size:12px;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--txt3)'">Technology</a>
                    </div>
                </div>
                <div>
                    <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:11px;letter-spacing:2px;color:#fff;text-transform:uppercase;margin-bottom:10px">
                        Company</p>
                    <div style="display:flex;flex-direction:column;gap:8px">
                        <a href="about.html" style="color:var(--txt3);font-size:12px;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--txt3)'">About
                            Us</a>
                        <a href="newsroom.html" style="color:var(--txt3);font-size:12px;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--txt3)'">Newsroom</a>
                        <a href="careers.html" style="color:var(--txt3);font-size:12px;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--txt3)'">Careers</a>
                        <a href="contact.html" style="color:var(--txt3);font-size:12px;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--txt3)'">Contact</a>
                    </div>
                </div>
                <div>
                    <p style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:11px;letter-spacing:2px;color:#fff;text-transform:uppercase;margin-bottom:10px">
                        Legal</p>
                    <div style="display:flex;flex-direction:column;gap:8px">
                        <a href="privacy.html" style="color:var(--txt3);font-size:12px;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--txt3)'">Privacy Policy</a>
                        <a href="terms.html" style="color:var(--txt3);font-size:12px;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--txt3)'">Terms
                            of Use</a>
                        <a href="cookies.html" style="color:var(--txt3);font-size:12px;text-decoration:none;transition:color .2s" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--txt3)'">Cookie
                            Policy</a>
                    </div>
                </div>
            </div>
        </div>
        <div style="border-top:1px solid var(--border);padding-top:18px;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:10px">
            <p style="color:var(--txt3);font-size:11px;font-family:'Barlow Condensed',sans-serif">© 2026 ATANNEX
                News Network. All rights reserved.</p>
            <p style="color:var(--txt3);font-size:11px;font-family:'Barlow Condensed',sans-serif">Built for clarity.
                Committed to truth.</p>
        </div>
    </div>
</footer>
@endsection

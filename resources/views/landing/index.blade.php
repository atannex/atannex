@extends('landing.partials.base')

@section('title', 'Breaking News from Lebialem Division, South-West Region of Cameroon')

@section('meta:description', 'Local News & Media Platform for Lebialem Division, Cameroon. Covering Fontem, Alou, and Wabane with in-depth community stories.')

@section('content')

<!-- ==================== HERO SECTION ==================== -->
<section class="px-6 pt-32 pb-24 md:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="grid items-center gap-16 lg:grid-cols-2">
            <div class="space-y-8">
                <div class="fade-up">
                    <div class="badge-primary">
                        <i class="fas fa-newspaper"></i>
                        {{ __(' Local Stories. Community Voice.') }}
                    </div>
                </div>

                <h1 class="delay-100 section-title fade-up">
                    <span>{{ __('Your Window to') }}</span><br>
                    <span class="gradient-text">{{ __('Lebialem Division') }}</span><br>
                    <span>{{ __('Local Affairs') }}</span>
                </h1>

                <p class="max-w-lg text-lg leading-relaxed delay-200 text-slate-300 fade-up">
                    {{ __('Atannex delivers in-depth coverage of community development, grassroots initiatives, and local narratives from Fontem, Alou, and Wabane. Connecting communities. Preserving stories. Building futures.') }}
                </p>

                <div class="flex flex-col gap-4 delay-300 sm:flex-row fade-up">
                    {{-- <a href="{{ route('donate') }}" class="btn-gradient"> --}}
                    <a href="javascript:void(0)" class="btn-gradient">
                        <i class="fas fa-bell"></i> {{ __("Donate") }}
                    </a>
                    <a href="{{ route('home') }}" class="btn-outline">
                        <i class="fas fa-arrow-right"></i> {{ __("Explore") }}
                    </a>
                </div>

                <div class="grid grid-cols-3 gap-6 pt-8 delay-300 fade-up">
                    <div class="text-center">
                        <div class="text-3xl font-black md:text-4xl gradient-text">3</div>
                        <p class="mt-1 text-xs font-medium md:text-sm text-slate-400">{{ __('Municipalities') }}</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-black md:text-4xl gradient-text">100%</div>
                        <p class="mt-1 text-xs font-medium md:text-sm text-slate-400">{{ __('Local Focus') }}</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-black md:text-4xl gradient-text">20K+</div>
                        <p class="mt-1 text-xs font-medium md:text-sm text-slate-400">{{ __('Monthly Readers') }}</p>
                    </div>
                </div>
            </div>

            <div class="justify-center hidden delay-200 lg:flex fade-up">
                <img src="{{ asset('storage/' . $global['logo']?->image) }}" alt="{{ config('app.name') }}" class="shadow-2xl rounded-2xl">
            </div>
        </div>
    </div>
</section>

<!-- ==================== ABOUT SECTION ==================== -->
<section id="about" class="px-6 py-24 md:px-8 bg-slate-900/40">
    <div class="mx-auto max-w-7xl">
        <div class="max-w-3xl mx-auto mb-16 text-center scroll-reveal">
            <div class="justify-center mb-4 badge-primary">
                <i class="fas fa-info-circle"></i> {{ __('About Atannex') }}
            </div>
            <h2 class="mb-4 section-title">{{ __('Who We Are') }}</h2>
            <p class="text-lg text-slate-400">{{ __('A dynamic digital news platform committed to amplifying local voices') }}</p>
        </div>
        <div class="grid gap-8 mb-20 md:grid-cols-3">
            <div class="p-8 rounded-lg card-base scroll-reveal">
                <div class="flex items-center justify-center mb-4 text-2xl text-blue-400 rounded-lg w-14 h-14 bg-blue-500/20">
                    <i class="fas fa-microphone"></i>
                </div>
                <h3 class="mb-3 text-xl font-bold text-white">{{ __('Community Voice') }}</h3>
                <p class="text-sm leading-relaxed text-slate-400">{{ __('Amplifying underrepresented voices and stories often overlooked by mainstream media outlets.') }}</p>
            </div>
            <div class="p-8 delay-100 rounded-lg card-base scroll-reveal">
                <div class="flex items-center justify-center mb-4 text-2xl rounded-lg w-14 h-14 bg-cyan-500/20 text-cyan-400">
                    <i class="fas fa-book"></i>
                </div>
                <h3 class="mb-3 text-xl font-bold text-white">{{ __('Preserve Heritage') }}</h3>
                <p class="text-sm leading-relaxed text-slate-400">{{ __('Thoughtfully documenting and preserving the cultural identity and history of Lebialem communities.') }}</p>
            </div>
            <div class="p-8 delay-200 rounded-lg card-base scroll-reveal">
                <div class="flex items-center justify-center mb-4 text-2xl text-green-400 rounded-lg w-14 h-14 bg-green-500/20">
                    <i class="fas fa-link"></i>
                </div>
                <h3 class="mb-3 text-xl font-bold text-white">{{ __('Bridge Connection') }}</h3>
                <p class="text-sm leading-relaxed text-slate-400">{{ __('Connecting diaspora communities with home, fostering engagement and collaborative development.') }}</p>
            </div>
        </div>

        <div class="grid items-center gap-12 lg:grid-cols-2">
            <div class="scroll-reveal">
                <h3 class="mb-4 text-3xl font-bold text-white">{{ __('Capturing Lebialem\'s Pulse') }}</h3>
                <p class="mb-4 leading-relaxed text-slate-400">
                    {{ __('Atannex is a comprehensive community platform dedicated to in-depth coverage of local affairs across Fontem, Alou, and Wabane. We go beyond breaking news to provide balanced, contextual reporting that enhances understanding while maintaining accessibility.') }}
                </p>
                <p class="leading-relaxed text-slate-400">
                    {{ __('Our reporting spans grassroots initiatives, leadership activities, education, healthcare efforts, and infrastructural development. We serve as a trusted source where local narratives are not only reported but thoughtfully preserved for future generations.') }}
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4 delay-100 scroll-reveal">
                <div class="p-6 text-center rounded-lg card-base">
                    <div class="text-3xl font-bold gradient-text">50+</div>
                    <p class="mt-2 text-xs font-medium text-slate-400">{{ __('Stories Published') }}</p>
                </div>
                <div class="p-6 text-center rounded-lg card-base">
                    <div class="text-3xl font-bold gradient-text">2K+</div>
                    <p class="mt-2 text-xs font-medium text-slate-400">{{ __('Weekly Visitors') }}</p>
                </div>
                <div class="p-6 text-center rounded-lg card-base">
                    <div class="text-3xl font-bold gradient-text">3</div>
                    <p class="mt-2 text-xs font-medium text-slate-400">{{ __('Years of Impact') }}</p>
                </div>
                <div class="p-6 text-center rounded-lg card-base">
                    <div class="text-3xl font-bold gradient-text">100%</div>
                    <p class="mt-2 text-xs font-medium text-slate-400">{{ __('Independent') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== COVERAGE AREAS SECTION ==================== -->
<section id="coverage" class="px-6 py-24 md:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="max-w-3xl mx-auto mb-16 text-center scroll-reveal">
            <div class="justify-center mb-4 badge-primary">
                <i class="fas fa-map"></i> {{__('Our Focus')}}
            </div>
            <h2 class="mb-4 section-title">{{__('Coverage Areas')}}</h2>
            <p class="text-lg text-slate-400">{{__('In-depth reporting from three vibrant municipalities')}}</p>
        </div>
        <div class="grid gap-8 md:grid-cols-3">
            <div class="p-8 rounded-lg card-base scroll-reveal">
                <div class="flex items-center justify-center w-12 h-12 mb-4 text-xl text-blue-400 rounded-lg bg-blue-500/20">
                    <i class="fas fa-city"></i>
                </div>
                <h3 class="mb-3 text-xl font-bold text-white">{{ __("Fontem") }}</h3>
                <p class="mb-4 text-sm text-slate-400">{{ __("The divisional headquarters. Reporting on administrative initiatives, civic projects, and community development efforts shaping the future of Lebialem.") }}</p>
                <div class="space-y-2 text-xs text-slate-300">
                    <p><span class="font-semibold text-blue-400">✓</span>{{__(" Local Government News")}}</p>
                    <p><span class="font-semibold text-blue-400">✓</span> {{ __("Infrastructure Projects") }}</p>
                    <p><span class="font-semibold text-blue-400">✓</span> {{ __("Community Events") }}</p>
                </div>
            </div>

            <div class="p-8 delay-100 rounded-lg card-base scroll-reveal">
                <div class="flex items-center justify-center w-12 h-12 mb-4 text-xl rounded-lg bg-cyan-500/20 text-cyan-400">
                    <i class="fas fa-house"></i>
                </div>
                <h3 class="mb-3 text-xl font-bold text-white">{{ __("Alou") }}</h3>
                <p class="mb-4 text-sm text-slate-400">{{ __("Vibrant community stories and grassroots initiatives. Covering education progress, healthcare advances, and local entrepreneurship transforming the municipality.") }}</p>
                <div class="space-y-2 text-xs text-slate-300">
                    <p><span class="font-semibold text-cyan-400">✓</span> {{ __("Education Updates") }}</p>
                    <p><span class="font-semibold text-cyan-400">✓</span> {{ __("Health Initiatives") }}</p>
                    <p><span class="font-semibold text-cyan-400">✓</span> {{ __("Local Business") }}</p>
                </div>
            </div>

            <div class="p-8 delay-200 rounded-lg card-base scroll-reveal">
                <div class="flex items-center justify-center w-12 h-12 mb-4 text-xl text-green-400 rounded-lg bg-green-500/20">
                    <i class="fas fa-tree"></i>
                </div>
                <h3 class="mb-3 text-xl font-bold text-white">{{ __("Wabane") }}</h3>
                <p class="mb-4 text-sm text-slate-400">{{ __("Rural development and cultural stories. Highlighting agricultural advances, community resilience, and the rich traditions defining this unique municipality.") }}</p>
                <div class="space-y-2 text-xs text-slate-300">
                    <p><span class="font-semibold text-green-400">✓</span> {{ __("Agricultural News") }}</p>
                    <p><span class="font-semibold text-green-400">✓</span> {{ __("Cultural Heritage") }}</p>
                    <p><span class="font-semibold text-green-400">✓</span> {{ __("Rural Development") }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== STORIES SECTION ==================== -->
<section id="stories" class="px-6 py-24 md:px-8 bg-slate-900/40">
    <div class="mx-auto max-w-7xl">
        <div class="max-w-3xl mx-auto mb-16 text-center scroll-reveal">
            <div class="justify-center mb-4 badge-primary">
                <i class="fas fa-newspaper"></i> {{ __("Latest Stories") }}
            </div>
            <h2 class="mb-4 section-title">{{ __("Featured Stories") }}</h2>
            <p class="text-lg text-slate-400">{{ __("In-depth coverage of what matters to Lebialem communities") }}</p>
        </div>

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach($posts as $story)
            <div class="overflow-hidden rounded-lg card-base scroll-reveal group">

                <div class="h-48 overflow-hidden bg-slate-800">
                    <img src="{{ asset('storage/' . $story->image) }}" alt="{{ $story->title }}" class="object-cover w-full h-full transition duration-500 group-hover:scale-110">
                </div>

                <div class="p-6">
                    <div class="mb-2 text-xs font-semibold text-blue-400">
                        {{ $story->category->name }}
                    </div>

                    <h3 class="mb-3 text-lg font-bold text-white">
                        {{ $story->title ? Str::limit($story->title, 60) : __('No title available.') }}
                    </h3>

                    <p class="mb-4 text-sm text-slate-400">
                        {{ $story->description ? Str::limit($story->description, 100) : __('No description available.') }}
                    </p>

                    <a href="{{ route('posts.show', $story->slug_path) }}" class="text-sm font-semibold text-blue-400 transition hover:text-blue-300">
                        {{ __(' Read Story →') }}
                    </a>
                </div>

            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ==================== EDITORIAL APPROACH SECTION ==================== -->
<section class="px-6 py-24 md:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="max-w-3xl mx-auto mb-16 text-center scroll-reveal">
            <div class="justify-center mb-4 badge-primary">
                <i class="fas fa-pen-fancy"></i> {{ __("Our Approach") }}
            </div>
            <h2 class="mb-4 section-title">{{ __("How We Report") }}</h2>
            <p class="text-lg text-slate-400">{{ __("Combining factual reporting with compelling storytelling") }}</p>
        </div>
        <div class="grid gap-8 md:grid-cols-2">
            <div class="p-8 rounded-lg card-base scroll-reveal">
                <div class="flex items-start gap-4 mb-6">
                    <i class="flex-shrink-0 mt-1 text-3xl text-blue-400 fas fa-lightning"></i>
                    <div>
                        <h3 class="mb-3 text-2xl font-bold text-white">{{ __("Breaking News") }}</h3>
                        <p class="leading-relaxed text-slate-400">{{ __("Timely updates on events affecting our communities, delivered with accuracy and context. We ensure you stay informed about developments as they unfold.") }}</p>
                    </div>
                </div>
            </div>

            <div class="p-8 delay-100 rounded-lg card-base scroll-reveal">
                <div class="flex items-start gap-4 mb-6">
                    <i class="flex-shrink-0 mt-1 text-3xl fas fa-book text-cyan-400"></i>
                    <div>
                        <h3 class="mb-3 text-2xl font-bold text-white">{{ __("Feature Articles") }}</h3>
                        <p class="leading-relaxed text-slate-400">{{ __("In-depth investigations and contextual stories that explore the \"why\" behind the news, offering deeper understanding of community issues and opportunities.") }}</p>
                    </div>
                </div>
            </div>

            <div class="p-8 delay-200 rounded-lg card-base scroll-reveal">
                <div class="flex items-start gap-4 mb-6">
                    <i class="flex-shrink-0 mt-1 text-3xl text-green-400 fas fa-microphone"></i>
                    <div>
                        <h3 class="mb-3 text-2xl font-bold text-white">{{ __("Interviews & Voices") }}</h3>
                        <p class="leading-relaxed text-slate-400">{{ __("Direct conversations with community leaders, innovators, and residents. We amplify diverse perspectives that reflect the richness of our communities.") }}</p>
                    </div>
                </div>
            </div>

            <div class="p-8 delay-300 rounded-lg card-base scroll-reveal">
                <div class="flex items-start gap-4 mb-6">
                    <i class="flex-shrink-0 mt-1 text-3xl text-purple-400 fas fa-comments"></i>
                    <div>
                        <h3 class="mb-3 text-2xl font-bold text-white">{{ __("Community Submissions") }}</h3>
                        <p class="leading-relaxed text-slate-400">{{ __("We welcome stories from community members. Your voices matter, and we provide a platform to share experiences, initiatives, and perspectives.") }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== DIASPORA BRIDGE SECTION ==================== -->
<section class="px-6 py-24 md:px-8 bg-gradient-to-r from-blue-600 to-cyan-600">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="mb-6 text-4xl font-bold text-white md:text-5xl fade-up">Connecting Diaspora to Home</h2>
        <p class="max-w-2xl mx-auto mb-8 text-lg text-blue-100 delay-100 fade-up">
            Atannex bridges the gap between Lebialem communities and the diaspora living across the globe. Stay connected with your homeland, support local initiatives, and participate in the ongoing narrative of growth and development.
        </p>

        <div class="grid gap-8 mb-12 delay-200 md:grid-cols-3 fade-up">
            <div class="p-6 rounded-lg bg-white/10 backdrop-blur">
                <div class="mb-2 text-3xl font-bold text-white">Global Reach</div>
                <p class="text-sm text-blue-100">Readers in 50+ countries stay updated on home developments</p>
            </div>
            <div class="p-6 rounded-lg bg-white/10 backdrop-blur">
                <div class="mb-2 text-3xl font-bold text-white">Investment Gateway</div>
                <p class="text-sm text-blue-100">Discover opportunities to invest in your community's future</p>
            </div>
            <div class="p-6 rounded-lg bg-white/10 backdrop-blur">
                <div class="mb-2 text-3xl font-bold text-white">Collaboration Hub</div>
                <p class="text-sm text-blue-100">Connect with development initiatives and support causes you believe in</p>
            </div>
        </div>

        <div class="flex flex-col justify-center gap-4 delay-300 sm:flex-row fade-up">
            <a href="/diaspora" class="px-10 py-4 font-bold text-blue-600 transition bg-white rounded-lg hover:bg-blue-50">
                <i class="fas fa-globe"></i> Diaspora Program
            </a>
            <a href="/subscribe" class="px-10 py-4 font-bold text-white transition border-2 border-white rounded-lg hover:bg-white/10">
                <i class="fas fa-envelope"></i> Subscribe
            </a>
        </div>
    </div>
</section>

@include('landing.sections.testimonial')

<!-- ==================== GLOBAL REACH SECTION ==================== -->
<section class="px-6 py-24 md:px-8">
    <div class="mx-auto max-w-7xl">
        <!-- Section Header -->
        <div class="max-w-3xl mx-auto mb-16 text-center scroll-reveal">
            <div class="justify-center mb-4 badge-primary">
                <i class="fas fa-globe"></i> Reach
            </div>
            <h2 class="mb-4 section-title">Our Global Reach</h2>
            <p class="text-lg text-slate-400">Readers across continents staying connected to Lebialem</p>
        </div>

        <!-- Reach Stats Grid -->
        <div class="grid gap-8 mb-12 md:grid-cols-2 lg:grid-cols-4">
            <div class="p-8 text-center rounded-lg card-base scroll-reveal">
                <div class="mb-2 text-4xl font-bold gradient-text">50+</div>
                <p class="font-semibold text-white">Countries</p>
                <p class="mt-2 text-xs text-slate-400">Diaspora readers worldwide</p>
            </div>

            <div class="p-8 text-center delay-100 rounded-lg card-base scroll-reveal">
                <div class="mb-2 text-4xl font-bold gradient-text">50K+</div>
                <p class="font-semibold text-white">Monthly Readers</p>
                <p class="mt-2 text-xs text-slate-400">Growing community engagement</p>
            </div>

            <div class="p-8 text-center delay-200 rounded-lg card-base scroll-reveal">
                <div class="mb-2 text-4xl font-bold gradient-text">100%</div>
                <p class="font-semibold text-white">Local Focus</p>
                <p class="mt-2 text-xs text-slate-400">Dedicated to Lebialem stories</p>
            </div>

            <div class="p-8 text-center delay-300 rounded-lg card-base scroll-reveal">
                <div class="mb-2 text-4xl font-bold gradient-text">7</div>
                <p class="font-semibold text-white">Languages</p>
                <p class="mt-2 text-xs text-slate-400">Accessible to diverse audiences</p>
            </div>
        </div>

        <!-- Regional Breakdown -->
        <div class="grid gap-8 md:grid-cols-3">
            <div class="p-8 rounded-lg card-base scroll-reveal">
                <div class="flex items-center justify-center w-12 h-12 mb-4 text-xl text-blue-400 rounded-lg bg-blue-500/20">
                    <i class="fas fa-map-location-dot"></i>
                </div>
                <h3 class="mb-3 text-xl font-bold text-white">West Africa</h3>
                <p class="mb-4 text-sm text-slate-400">Strongest readership from Cameroon, Nigeria, and neighboring countries. Active engagement from regional communities.</p>
                <div class="text-xs font-semibold text-blue-400">35% of traffic</div>
            </div>

            <div class="p-8 delay-100 rounded-lg card-base scroll-reveal">
                <div class="flex items-center justify-center w-12 h-12 mb-4 text-xl rounded-lg bg-cyan-500/20 text-cyan-400">
                    <i class="fas fa-plane"></i>
                </div>
                <h3 class="mb-3 text-xl font-bold text-white">European Diaspora</h3>
                <p class="mb-4 text-sm text-slate-400">Growing readership from UK, France, Germany, and other European countries. High engagement from diaspora investors.</p>
                <div class="text-xs font-semibold text-cyan-400">30% of traffic</div>
            </div>

            <div class="p-8 delay-200 rounded-lg card-base scroll-reveal">
                <div class="flex items-center justify-center w-12 h-12 mb-4 text-xl text-green-400 rounded-lg bg-green-500/20">
                    <i class="fas fa-earth-americas"></i>
                </div>
                <h3 class="mb-3 text-xl font-bold text-white">Americas & Others</h3>
                <p class="mb-4 text-sm text-slate-400">Readers in USA, Canada, and other continents. Building community connections across the globe.</p>
                <div class="text-xs font-semibold text-green-400">35% of traffic</div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== LEADERSHIP SECTION ==================== -->
<section class="px-6 py-24 md:px-8 bg-slate-900/40">
    <div class="mx-auto max-w-7xl">
        <!-- Section Header -->
        <div class="max-w-3xl mx-auto mb-16 text-center scroll-reveal">
            <div class="justify-center mb-4 badge-primary">
                <i class="fas fa-people-group"></i> Leadership
            </div>
            <h2 class="mb-4 section-title">Meet Our Team</h2>
            <p class="text-lg text-slate-400">Dedicated journalists and professionals committed to quality local reporting</p>
        </div>

        <!-- Team Grid -->
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            <!-- Team Member 1 -->
            <div class="overflow-hidden rounded-lg card-base scroll-reveal-bottom">
                <div class="flex items-start gap-4 p-6">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=300&fit=crop" alt="Anteneh Njikam" class="flex-shrink-0 object-cover w-24 h-24 border-2 rounded-full border-blue-400/30">
                    <div class="flex-grow">
                        <h3 class="mb-1 text-lg font-bold text-white">Anteneh Njikam</h3>
                        <p class="mb-3 text-sm font-semibold text-blue-400">Editor-in-Chief & Founder</p>
                        <p class="mb-4 text-xs leading-relaxed text-slate-400">15+ years in journalism. Passionate about amplifying local voices and preserving community heritage. Visionary leader driving Atannex's mission.</p>
                        <div class="flex gap-2">
                            <a href="https://linkedin.com/in/antenehnj" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs text-blue-400 transition rounded-full bg-blue-500/20 hover:bg-blue-500/40">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="https://twitter.com/antenehnj" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs text-blue-400 transition rounded-full bg-blue-500/20 hover:bg-blue-500/40">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Member 2 -->
            <div class="overflow-hidden delay-100 rounded-lg card-base scroll-reveal-bottom">
                <div class="flex items-start gap-4 p-6">
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=300&h=300&fit=crop" alt="Clement Jude" class="flex-shrink-0 object-cover w-24 h-24 border-2 rounded-full border-cyan-400/30">
                    <div class="flex-grow">
                        <h3 class="mb-1 text-lg font-bold text-white">Clement Jude</h3>
                        <p class="mb-3 text-sm font-semibold text-cyan-400">Senior Investigative Journalist</p>
                        <p class="mb-4 text-xs leading-relaxed text-slate-400">12 years covering community development. Expert in uncovering untold stories and connecting grassroots initiatives to broader narratives.</p>
                        <div class="flex gap-2">
                            <a href="https://linkedin.com/in/clementjude" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs transition rounded-full bg-cyan-500/20 text-cyan-400 hover:bg-cyan-500/40">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="https://twitter.com/clementjude" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs transition rounded-full bg-cyan-500/20 text-cyan-400 hover:bg-cyan-500/40">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Member 3 -->
            <div class="overflow-hidden delay-200 rounded-lg card-base scroll-reveal-bottom">
                <div class="flex items-start gap-4 p-6">
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=300&h=300&fit=crop" alt="Mirabel Ndambe" class="flex-shrink-0 object-cover w-24 h-24 border-2 rounded-full border-green-400/30">
                    <div class="flex-grow">
                        <h3 class="mb-1 text-lg font-bold text-white">Mirabel Ndambe</h3>
                        <p class="mb-3 text-sm font-semibold text-green-400">Features & Community Correspondent</p>
                        <p class="mb-4 text-xs leading-relaxed text-slate-400">8 years in feature writing. Specializes in human-interest stories that illuminate the resilience and progress of Lebialem communities.</p>
                        <div class="flex gap-2">
                            <a href="https://linkedin.com/in/mirabeln" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs text-green-400 transition rounded-full bg-green-500/20 hover:bg-green-500/40">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="https://twitter.com/mirabeln" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs text-green-400 transition rounded-full bg-green-500/20 hover:bg-green-500/40">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Member 4 -->
            <div class="overflow-hidden delay-300 rounded-lg card-base scroll-reveal-bottom">
                <div class="flex items-start gap-4 p-6">
                    <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=300&h=300&fit=crop" alt="Eveline Pare" class="flex-shrink-0 object-cover w-24 h-24 border-2 rounded-full border-yellow-400/30">
                    <div class="flex-grow">
                        <h3 class="mb-1 text-lg font-bold text-white">Eveline Pare</h3>
                        <p class="mb-3 text-sm font-semibold text-yellow-400">Digital & Diaspora Editor</p>
                        <p class="mb-4 text-xs leading-relaxed text-slate-400">6 years in digital journalism. Bridges diaspora communities with home through compelling multimedia storytelling and engagement strategies.</p>
                        <div class="flex gap-2">
                            <a href="https://linkedin.com/in/evelinepare" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs text-yellow-400 transition rounded-full bg-yellow-500/20 hover:bg-yellow-500/40">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="https://twitter.com/evelinepare" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs text-yellow-400 transition rounded-full bg-yellow-500/20 hover:bg-yellow-500/40">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Member 5 -->
            <div class="overflow-hidden delay-100 rounded-lg card-base scroll-reveal-bottom">
                <div class="flex items-start gap-4 p-6">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=300&fit=crop" alt="Nkeng Meboto" class="flex-shrink-0 object-cover w-24 h-24 border-2 rounded-full border-purple-400/30">
                    <div class="flex-grow">
                        <h3 class="mb-1 text-lg font-bold text-white">Nkeng Meboto</h3>
                        <p class="mb-3 text-sm font-semibold text-purple-400">Graphics & Multimedia Designer</p>
                        <p class="mb-4 text-xs leading-relaxed text-slate-400">7 years in visual storytelling. Creates compelling graphics and multimedia content that makes complex stories accessible and engaging.</p>
                        <div class="flex gap-2">
                            <a href="https://linkedin.com/in/nkengm" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs text-purple-400 transition rounded-full bg-purple-500/20 hover:bg-purple-500/40">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="https://twitter.com/nkengm" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs text-purple-400 transition rounded-full bg-purple-500/20 hover:bg-purple-500/40">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Member 6 -->
            <div class="overflow-hidden delay-200 rounded-lg card-base scroll-reveal-bottom">
                <div class="flex items-start gap-4 p-6">
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=300&h=300&fit=crop" alt="Tanoh Kefack" class="flex-shrink-0 object-cover w-24 h-24 border-2 rounded-full border-red-400/30">
                    <div class="flex-grow">
                        <h3 class="mb-1 text-lg font-bold text-white">Tanoh Kefack</h3>
                        <p class="mb-3 text-sm font-semibold text-red-400">Community Relations Manager</p>
                        <p class="mb-4 text-xs leading-relaxed text-slate-400">5 years in community engagement. Builds relationships with sources, organizations, and readers to ensure Atannex remains community-centered.</p>
                        <div class="flex gap-2">
                            <a href="https://linkedin.com/in/tanohk" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs text-red-400 transition rounded-full bg-red-500/20 hover:bg-red-500/40">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="https://twitter.com/tanohk" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-8 h-8 text-xs text-red-400 transition rounded-full bg-red-500/20 hover:bg-red-500/40">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== PARTNERS SECTION ==================== -->
<section class="px-6 py-24 md:px-8">
    <div class="mx-auto max-w-7xl">
        <!-- Section Header -->
        <div class="max-w-3xl mx-auto mb-16 text-center scroll-reveal">
            <div class="justify-center mb-4 badge-primary">
                <i class="fas fa-handshake"></i> Partners
            </div>
            <h2 class="mb-4 section-title">Partners & Supporters</h2>
            <p class="text-lg text-slate-400">Organizations supporting quality journalism in Lebialem</p>
        </div>

        <!-- Partners Grid -->
        <div class="grid gap-8 mb-12 md:grid-cols-2 lg:grid-cols-4">
            <div class="flex items-center justify-center h-32 p-8 transition rounded-lg card-base scroll-reveal hover:scale-105">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/ee/UN_emblem_blue.svg/1024px-UN_emblem_blue.svg.png" alt="United Nations" class="object-contain w-auto h-20">
            </div>

            <div class="flex items-center justify-center h-32 p-8 transition delay-100 rounded-lg card-base scroll-reveal hover:scale-105">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/UNICEF_Logo.svg/1024px-UNICEF_Logo.svg.png" alt="UNICEF" class="object-contain w-auto h-20">
            </div>

            <div class="flex items-center justify-center h-32 p-8 transition delay-200 rounded-lg card-base scroll-reveal hover:scale-105">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/84/World_Bank_logo.svg/1024px-World_Bank_logo.svg.png" alt="World Bank" class="object-contain w-auto h-20">
            </div>

            <div class="flex items-center justify-center h-32 p-8 transition delay-300 rounded-lg card-base scroll-reveal hover:scale-105">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9f/Oxfam_International_Logo.svg/1024px-Oxfam_International_Logo.svg.png" alt="Oxfam" class="object-contain w-auto h-20">
            </div>
        </div>

        <!-- Secondary Partners -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-5">
            <div class="flex items-center justify-center p-6 transition rounded-lg card-base h-28 scroll-reveal hover:scale-105">
                <p class="text-sm font-semibold text-center text-slate-400">Cameroonian Media Association</p>
            </div>
            <div class="flex items-center justify-center p-6 transition delay-100 rounded-lg card-base h-28 scroll-reveal hover:scale-105">
                <p class="text-sm font-semibold text-center text-slate-400">SW Regional Government</p>
            </div>
            <div class="flex items-center justify-center p-6 transition delay-200 rounded-lg card-base h-28 scroll-reveal hover:scale-105">
                <p class="text-sm font-semibold text-center text-slate-400">Local NGOs Coalition</p>
            </div>
            <div class="flex items-center justify-center p-6 transition delay-300 rounded-lg card-base h-28 scroll-reveal hover:scale-105">
                <p class="text-sm font-semibold text-center text-slate-400">Community Leaders Forum</p>
            </div>
            <div class="flex items-center justify-center p-6 transition delay-100 rounded-lg card-base h-28 scroll-reveal hover:scale-105">
                <p class="text-sm font-semibold text-center text-slate-400">Educational Institutions</p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== ENHANCED GALLERY SECTION ==================== -->
<section class="px-6 py-24 md:px-8 bg-slate-900/40">
    <div class="mx-auto max-w-7xl">
        <!-- Section Header -->
        <div class="max-w-3xl mx-auto mb-16 text-center scroll-reveal">
            <div class="justify-center mb-4 badge-primary">
                <i class="fas fa-images"></i> Gallery
            </div>
            <h2 class="mb-4 section-title">Visual Stories Gallery</h2>
            <p class="text-lg text-slate-400">Moments capturing the spirit and progress of Lebialem communities</p>
        </div>

        <!-- Enhanced Gallery Grid -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            <div class="relative overflow-hidden rounded-lg cursor-pointer group h-72 scroll-reveal">
                <img src="https://images.unsplash.com/photo-1427504494785-cdbed0c3675b?w=500&h=500&fit=crop" alt="Education" class="object-cover w-full h-full transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 flex items-center justify-center transition bg-black/30 group-hover:bg-black/50">
                    <div class="text-center transition opacity-0 group-hover:opacity-100">
                        <p class="font-semibold text-white">School Programs</p>
                        <p class="text-sm text-blue-300">Education Initiatives</p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden delay-100 rounded-lg cursor-pointer group h-72 scroll-reveal">
                <img src="https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?w=500&h=500&fit=crop" alt="Healthcare" class="object-cover w-full h-full transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 flex items-center justify-center transition bg-black/30 group-hover:bg-black/50">
                    <div class="text-center transition opacity-0 group-hover:opacity-100">
                        <p class="font-semibold text-white">Health Campaigns</p>
                        <p class="text-sm text-green-300">Community Wellness</p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden delay-200 rounded-lg cursor-pointer group h-72 scroll-reveal">
                <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=500&h=500&fit=crop" alt="Community" class="object-cover w-full h-full transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 flex items-center justify-center transition bg-black/30 group-hover:bg-black/50">
                    <div class="text-center transition opacity-0 group-hover:opacity-100">
                        <p class="font-semibold text-white">Community Events</p>
                        <p class="text-sm text-cyan-300">Local Gatherings</p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden delay-300 rounded-lg cursor-pointer group h-72 scroll-reveal">
                <img src="https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=500&h=500&fit=crop" alt="Agriculture" class="object-cover w-full h-full transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 flex items-center justify-center transition bg-black/30 group-hover:bg-black/50">
                    <div class="text-center transition opacity-0 group-hover:opacity-100">
                        <p class="font-semibold text-white">Agriculture</p>
                        <p class="text-sm text-yellow-300">Farming Progress</p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden delay-100 rounded-lg cursor-pointer group h-72 scroll-reveal">
                <img src="https://images.unsplash.com/photo-1511379938547-c1f69b13d835?w=500&h=500&fit=crop" alt="Culture" class="object-cover w-full h-full transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 flex items-center justify-center transition bg-black/30 group-hover:bg-black/50">
                    <div class="text-center transition opacity-0 group-hover:opacity-100">
                        <p class="font-semibold text-white">Cultural Heritage</p>
                        <p class="text-sm text-purple-300">Traditions & Celebrations</p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden delay-200 rounded-lg cursor-pointer group h-72 scroll-reveal">
                <img src="https://images.unsplash.com/photo-1590509780387-da94e08b3060?w=500&h=500&fit=crop" alt="Infrastructure" class="object-cover w-full h-full transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 flex items-center justify-center transition bg-black/30 group-hover:bg-black/50">
                    <div class="text-center transition opacity-0 group-hover:opacity-100">
                        <p class="font-semibold text-white">Infrastructure</p>
                        <p class="text-sm text-red-300">Development Projects</p>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden delay-300 rounded-lg cursor-pointer group h-72 scroll-reveal">
                <img src="https://images.unsplash.com/photo-1504711331512-be2a21fb4557?w=500&h=500&fit=crop" alt="Youth" class="object-cover w-full h-full transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 flex items-center justify-center transition bg-black/30 group-hover:bg-black/50">
                    <div class="text-center transition opacity-0 group-hover:opacity-100">
                        <p class="font-semibold text-white">Youth Initiatives</p>
                        <p class="text-sm text-blue-300">Future Leaders</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('landing.sections.faqs')

<!-- ==================== GRAND CTA SECTION ==================== -->
<section class="relative px-6 py-32 overflow-hidden md:px-8 bg-gradient-to-r from-blue-600 via-cyan-600 to-blue-600">
    <!-- Animated background -->
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-0 left-0 bg-white rounded-full w-96 h-96 mix-blend-screen blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 right-0 bg-white rounded-full w-96 h-96 mix-blend-screen blur-3xl animate-pulse"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto text-center">
        <h2 class="mb-8 text-5xl font-bold text-white md:text-6xl fade-up">Join Atannex Community</h2>

        <p class="max-w-3xl mx-auto mb-4 text-xl leading-relaxed text-blue-100 delay-100 fade-up">
            Be part of a movement preserving and amplifying Lebialem's stories. Stay informed, connected, and engaged with your community.
        </p>

        <p class="mb-12 text-lg delay-200 text-blue-50 fade-up">
            <i class="mr-2 fas fa-newspaper"></i>
            <span class="font-semibold">Quality local journalism you can trust</span>
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col justify-center gap-6 mb-16 delay-300 sm:flex-row fade-up">
            <a href="/subscribe" class="flex items-center justify-center gap-2 px-10 py-4 text-lg font-bold text-blue-600 transition transform bg-white rounded-lg hover:bg-blue-50 hover:scale-105">
                <i class="fas fa-bell"></i> Subscribe Now
            </a>
            <a href="mailto:hello@atannex.cm" class="flex items-center justify-center gap-2 px-10 py-4 text-lg font-bold text-white transition transform border-white rounded-lg border-3 hover:bg-white/10 hover:scale-105">
                <i class="fas fa-envelope"></i> Contact Us
            </a>
            <a href="/diaspora" class="flex items-center justify-center gap-2 px-10 py-4 text-lg font-bold text-white transition transform border-white rounded-lg border-3 hover:bg-white/10 hover:scale-105">
                <i class="fas fa-globe"></i> Diaspora Program
            </a>
        </div>

        <!-- Stats Highlight -->
        <div class="grid max-w-3xl gap-8 mx-auto mb-16 md:grid-cols-3 fade-up delay-400">
            <div>
                <div class="mb-2 text-4xl font-bold text-white">500+</div>
                <p class="text-sm text-blue-100">Stories Published</p>
            </div>
            <div>
                <div class="mb-2 text-4xl font-bold text-white">50+</div>
                <p class="text-sm text-blue-100">Countries Reached</p>
            </div>
            <div>
                <div class="mb-2 text-4xl font-bold text-white">100%</div>
                <p class="text-sm text-blue-100">Community-Focused</p>
            </div>
        </div>

        <!-- Social Links -->
        <div class="flex justify-center gap-8 delay-500 fade-up">
            <a href="https://facebook.com/atannex" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-12 h-12 text-white transition rounded-full bg-white/20 hover:bg-white/30 hover:scale-110">
                <i class="text-xl fab fa-facebook"></i>
            </a>
            <a href="https://twitter.com/atannex" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-12 h-12 text-white transition rounded-full bg-white/20 hover:bg-white/30 hover:scale-110">
                <i class="text-xl fab fa-twitter"></i>
            </a>
            <a href="https://instagram.com/atannex" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-12 h-12 text-white transition rounded-full bg-white/20 hover:bg-white/30 hover:scale-110">
                <i class="text-xl fab fa-instagram"></i>
            </a>
            <a href="https://youtube.com/atannex" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-12 h-12 text-white transition rounded-full bg-white/20 hover:bg-white/30 hover:scale-110">
                <i class="text-xl fab fa-youtube"></i>
            </a>
        </div>
    </div>
</section>

<section id="contact" class="px-6 py-24 md:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="max-w-3xl mx-auto mb-16 text-center scroll-reveal">
            <div class="justify-center mb-4 badge-primary">
                <i class="fas fa-envelope"></i> Get In Touch
            </div>
            <h2 class="mb-4 section-title">Contact Us</h2>
            <p class="text-lg text-slate-400">We'd love to hear from you. Share tips, stories, or feedback.</p>
        </div>

        <div class="grid gap-8 mb-12 md:grid-cols-3">
            <div class="p-6 text-center rounded-lg card-base scroll-reveal">
                <i class="mb-4 text-3xl text-blue-400 fas fa-envelope"></i>
                <h3 class="mb-2 font-bold text-white">Email</h3>
                <a href="mailto:hello@atannex.cm" class="text-blue-400 hover:text-blue-300">hello@atannex.cm</a>
            </div>

            <div class="p-6 text-center delay-100 rounded-lg card-base scroll-reveal">
                <i class="mb-4 text-3xl fas fa-phone text-cyan-400"></i>
                <h3 class="mb-2 font-bold text-white">Call</h3>
                <a href="tel:+237690000000" class="text-blue-400 hover:text-blue-300">+237 690 000 000</a>
            </div>

            <div class="p-6 text-center delay-200 rounded-lg card-base scroll-reveal">
                <i class="mb-4 text-3xl text-green-400 fas fa-map-marker-alt"></i>
                <h3 class="mb-2 font-bold text-white">Fontem, Lebialem</h3>
                <p class="text-sm text-slate-400">Lebialem Division, South-West Region, Cameroon</p>
            </div>
        </div>

        <!-- Social Links -->
        <div class="text-center scroll-reveal">
            <p class="mb-6 text-slate-400">Follow us on social media for daily updates</p>
            <div class="flex justify-center gap-6">
                <a href="https://facebook.com/atannex" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-12 h-12 text-blue-400 transition rounded-full bg-blue-500/20 hover:bg-blue-500/40 hover:scale-110">
                    <i class="text-xl fab fa-facebook"></i>
                </a>
                <a href="https://twitter.com/atannex" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-12 h-12 text-blue-400 transition rounded-full bg-blue-500/20 hover:bg-blue-500/40 hover:scale-110">
                    <i class="text-xl fab fa-twitter"></i>
                </a>
                <a href="https://instagram.com/atannex" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-12 h-12 text-blue-400 transition rounded-full bg-blue-500/20 hover:bg-blue-500/40 hover:scale-110">
                    <i class="text-xl fab fa-instagram"></i>
                </a>
                <a href="https://youtube.com/atannex" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-12 h-12 text-blue-400 transition rounded-full bg-blue-500/20 hover:bg-blue-500/40 hover:scale-110">
                    <i class="text-xl fab fa-youtube"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@extends('components.layouts.base')

@section('og:title', $module->post->title)
@section('og:description', $module->post->description)

@section('og:image', asset('storage/' . $module->post->image))

@section('og:publishedAt', $module->post->published_at)
@section('og:updatedAt', $module->post->updated_at)

@section('base')

<x-sections.preloader />

@livewire('search.web')

<x-sections.side-menu />

<x-sections.category.header />

<x-partials.breadcrumb />

{{-- <section class="th-blog-wrapper blog-details bg-smoke space-top space-extra-bottom">
    <div class="container">
        <div class="blog-style-bg">
            <div class="row">
                <div class="col-lg-8">
                    <div class="th-blog blog-single"><a data-theme-color="#4E4BD0" href="blog.html" class="category">Sports</a>
                        <h2 class="blog-title">From haute couture to street chic, Fashion Celebrates every</h2>
                        <div class="blog-meta"><a class="author" href="blog.html"><i class="far fa-user"></i>By -
                                Tnews</a> <a href="blog.html"><i class="fal fa-calendar-days"></i>21 June, 2023</a>
                            <a href="blog-details.html"><i class="far fa-comments"></i>Comments (3)</a> <span><i class="far fa-book-open"></i>5 Mins Read</span></div>
                        <div class="blog-audio"><audio controls>
                                <source src="audio.html" type="audio/ogg">
                                <source src="audio.mp3" type="audio/mpeg">Your browser does not support the audio
                                element.
                            </audio></div>
                        <div class="blog-content-wrap">
                            <div class="share-links-wrap">
                                <div class="share-links"><span class="share-links-title">Share Post:</span>
                                    <div class="multi-social"><a href="https://facebook.com/" target="_blank"><i class="fab fa-facebook-f"></i></a> <a href="https://twitter.com/" target="_blank"><i class="fab fa-twitter"></i></a> <a href="https://linkedin.com/" target="_blank"><i class="fab fa-linkedin-in"></i></a> <a href="https://pinterest.com/" target="_blank"><i class="fab fa-pinterest-p"></i></a> <a href="https://instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a></div>
                                </div>
                            </div>
                            <div class="blog-content">
                                <div class="blog-info-wrap"><button class="blog-info print_btn">Print : <i class="fas fa-print"></i></button> <a class="blog-info" href="mailto:">Email : <i class="fas fa-envelope"></i> </a><button class="blog-info ms-sm-auto">15k <i class="fas fa-thumbs-up"></i></button>
                                    <span class="blog-info">126k <i class="fas fa-eye"></i></span> <span class="blog-info">12k <i class="fas fa-share-nodes"></i></span></div>
                                <div class="content">
                                    <p>Fuel your competitive spirit, chase victory, and let sports be your legacy
                                        encapsulates the essence of embracing sports as a means to challenge
                                        oneself, strive for success, and leave a lasting impact. This phrase urges
                                        individuals to tap into their inner drive and motivation.</p>
                                    <p>Igniting their competitive spirit. It encourages them to set ambitious goals,
                                        not settling for mediocrity but pushing themselves to excel in their chosen
                                        sport or athletic endeavor. The pursuit of victory becomes the driving
                                        force, motivating athletes to give their all,</p>
                                    <p>Surpass their limitations, and achieve remarkable feats. It emphasizes the
                                        importance of hard work, dedication, and Perseverance in the face of
                                        challenges and obstacles that may arise along the way.</p>
                                    <div class="my-4 py-lg-2"><a href="https://themeforest.net/user/themeholy/portfolio"><img class="light-img w-100" src="assets/img/ads/ads_blog_1.jpg" alt="Ads"> <img class="dark-img w-100" src="assets/img/ads/ads_blog_1_dark.jpg" alt="Ads"></a></div>
                                    <p>The slogan reminds athletes that their participation in sports has the
                                        potential to leave a lasting legacy. It suggests that their accomplishments,
                                        records, and impact can inspire future generations, shaping the sport itself
                                        and influencing others to follow in their footsteps. By embracing sports and
                                        embracing the pursuit of victory, individuals have the opportunity to create
                                        a legacy that will be remembered and celebrated long after their own
                                        participation.</p>
                                    <div class="my-4 py-lg-2">
                                        <div class="th-video" data-overlay="black" data-opacity="5"><img class="w-100" src="assets/img/blog/blog_inner_8.jpg" alt="Blog Image"> <a href="https://www.youtube.com/watch?v=_sI_Ps7JSEk" class="play-btn style2 popup-video"><i class="fas fa-play"></i></a>
                                        </div>
                                    </div>
                                    <h3 class="h4">Achieve greatness, fueled by innovation</h3>
                                    <p>Achieve greatness, fueled by innovation" encapsulates the idea that by
                                        embracing and harnessing innovative approaches and technologies, individuals
                                        can reach extraordinary heights of success and achievement. It implies that
                                        the combination of pushing boundaries, thinking outside the box, and
                                        adopting cutting-edge advancements can Propel individuals to surpass their
                                        limitations and accomplish remarkable feats.</p>
                                    <p>This phrase suggests that innovation serves as the driving force behind
                                        progress and improvement in various fields, including sports, business,
                                        arts, and personal development. It conveys the message that by embracing new
                                        ideas.</p>
                                    <blockquote>
                                        <p>Fuel your competitive spirit, chase victory, and let sports be your
                                            legacy Encapsulates the essence.</p><cite>Robert Milton</cite>
                                    </blockquote>
                                    <div class="mb-4 row pb-lg-2 pt-xl-2 gy-4">
                                        <div class="col-md-6">
                                            <div class="th-video" data-overlay="black" data-opacity="6"><img class="w-100" src="assets/img/blog/blog_inner_9.jpg" alt="Blog Image"> <a href="https://www.youtube.com/watch?v=_sI_Ps7JSEk" class="play-btn style2 popup-video"><i class="fas fa-play"></i></a></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="th-video" data-overlay="black" data-opacity="6"><img class="w-100" src="assets/img/blog/blog_inner_10.jpg" alt="Blog Image"> <a href="https://www.youtube.com/watch?v=_sI_Ps7JSEk" class="play-btn style2 popup-video"><i class="fas fa-play"></i></a></div>
                                        </div>
                                    </div>
                                    <h3 class="h4">Power your game with innovative features.</h3>
                                    <p>Power your game with innovative features" signifies the utilization of
                                        advanced and cutting-edge elements to enhance your performance and elevate
                                        your gameplay. It suggests that by embracing these innovative features, you
                                        can tap into a new level of power, skill, and effectiveness in your chosen
                                        sport.</p>
                                    <p class="mb-n2">This phrase implies that the incorporation of groundbreaking
                                        technologies, techniques, and functionalities can provide you with a
                                        competitive edge and push the boundaries of what is achievable in your game.
                                    </p>
                                </div>
                                <div class="blog-tag">
                                    <h6 class="title">Related Tag :</h6>
                                    <div class="tagcloud"><a href="blog.html">Sports</a> <a href="blog.html">Politics</a> <a href="blog.html">Business</a></div>
                                </div>
                            </div>
                        </div>
                        <div class="blog-navigation">
                            <div class="nav-btn prev">
                                <div class="img"><img src="assets/img/blog/blog-nav-1.jpg" alt="blog img" class="nav-img"></div>
                                <div class="media-body">
                                    <h5 class="title"><a class="hover-line" href="blog-details.html">Game on!
                                            Embrace the spirit of sportsmanship</a></h5><a href="blog-details.html" class="nav-text"><i class="fas fa-arrow-left me-2"></i>Prev</a>
                                </div>
                            </div>
                            <div class="divider"></div>
                            <div class="nav-btn next">
                                <div class="media-body">
                                    <h5 class="title"><a class="hover-line" href="blog-details.html">Push your
                                            limits, redefine what's possible</a></h5><a href="blog-details.html" class="nav-text">Next<i class="fas fa-arrow-right ms-2"></i></a>
                                </div>
                                <div class="img"><img src="assets/img/blog/blog-nav-2.jpg" alt="blog img" class="nav-img"></div>
                            </div>
                        </div>
                        <div class="blog-author">
                            <div class="auhtor-img"><img src="assets/img/blog/blog-author.jpg" alt="Blog Author Image"></div>
                            <div class="media-body">
                                <div class="author-top">
                                    <div>
                                        <h3 class="author-name"><a class="text-inherit" href="team-details.html">Ronald Richards</a></h3><span class="author-desig">Founder & CEO</span>
                                    </div>
                                    <div class="social-links"><a href="https://facebook.com/" target="_blank"><i class="fab fa-facebook-f"></i></a> <a href="https://twitter.com/" target="_blank"><i class="fab fa-twitter"></i></a> <a href="https://linkedin.com/" target="_blank"><i class="fab fa-linkedin-in"></i></a> <a href="https://instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a></div>
                                </div>
                                <p class="author-text">Adventurer and passionate travel blogger. With a backpack
                                    full of stories and a camera in hand, she takes her readers on exhilarating
                                    journeys around the world.</p>
                            </div>
                        </div>
                        <div class="th-comments-wrap">
                            <h2 class="blog-inner-title h3">Comments (03)</h2>
                            <ul class="comment-list">
                                <li class="th-comment-item">
                                    <div class="th-post-comment">
                                        <div class="comment-avater"><img src="assets/img/blog/comment-author-1.jpg" alt="Comment Author"></div>
                                        <div class="comment-content"><span class="commented-on"><i class="fas fa-calendar-alt"></i>14 March, 2023</span>
                                            <h3 class="name">Brooklyn Simmons</h3>
                                            <p class="text">Your sport blog is simply fantastic! The in-depth
                                                analysis, engaging writing style, and up-to-date coverage of various
                                                sports events make it a must-visit for any sports enthusiast.</p>
                                            <div class="reply_and_edit"><a href="blog-details.html" class="reply-btn"><i class="fas fa-reply"></i>Reply</a></div>
                                        </div>
                                    </div>
                                    <ul class="children">
                                        <li class="th-comment-item">
                                            <div class="th-post-comment">
                                                <div class="comment-avater"><img src="assets/img/blog/comment-author-2.jpg" alt="Comment Author"></div>
                                                <div class="comment-content"><span class="commented-on"><i class="fas fa-calendar-alt"></i>15 March, 2023</span>
                                                    <h3 class="name">Marvin McKinney</h3>
                                                    <p class="text">Whether it's breaking news, expert opinions, or
                                                        inspiring athlete profiles, your blog delivers a winning
                                                        combination of excitement and information that keeps.</p>
                                                    <div class="reply_and_edit"><a href="blog-details.html" class="reply-btn"><i class="fas fa-reply"></i>Reply</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <li class="th-comment-item">
                                    <div class="th-post-comment">
                                        <div class="comment-avater"><img src="assets/img/blog/comment-author-3.jpg" alt="Comment Author"></div>
                                        <div class="comment-content"><span class="commented-on"><i class="fas fa-calendar-alt"></i>16 March, 2023</span>
                                            <h3 class="name">Ronald Richards</h3>
                                            <p class="text">The way you seamlessly blend statistical insights with
                                                compelling storytelling creates an immersive and captivating reading
                                                experience. Whether it's the latest match updates, behind-the-scenes
                                                glimpses.</p>
                                            <div class="reply_and_edit"><a href="blog-details.html" class="reply-btn"><i class="fas fa-reply"></i>Reply</a></div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="th-comment-form">
                            <div class="form-title">
                                <h3 class="mb-2 blog-inner-title">Leave a Comment</h3>
                                <p class="form-text">Your email address will not be published. Required fields are
                                    marked *</p>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group"><input type="text" placeholder="Your Name*" class="form-control"> <i class="far fa-user"></i></div>
                                <div class="col-md-6 form-group"><input type="text" placeholder="Your Email*" class="form-control"> <i class="far fa-envelope"></i></div>
                                <div class="col-12 form-group"><input type="text" placeholder="Website" class="form-control"> <i class="far fa-globe"></i></div>
                                <div class="col-12 form-group"><textarea placeholder="Write a Comment*" class="form-control"></textarea> <i class="far fa-pencil"></i></div>
                                <div class="mb-0 col-12 form-group"><button class="th-btn">Post Comment</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 sidebar-wrap">
                    <aside class="sidebar-area">
                        <div class="widget widget_search">
                            <form class="search-form"><input type="text" placeholder="Enter Keyword"> <button type="submit"><i class="far fa-search"></i></button></form>
                        </div>
                        <div class="widget widget_categories">
                            <h3 class="widget_title">Categories</h3>
                            <ul>
                                <li><a data-bg-src="assets/img/bg/category_bg_1_1.jpg" href="blog.html">Sports</a>
                                </li>
                                <li><a data-bg-src="assets/img/bg/category_bg_1_2.jpg" href="blog.html">Business</a>
                                </li>
                                <li><a data-bg-src="assets/img/bg/category_bg_1_3.jpg" href="blog.html">Politics</a>
                                </li>
                                <li><a data-bg-src="assets/img/bg/category_bg_1_4.jpg" href="blog.html">Health</a>
                                </li>
                                <li><a data-bg-src="assets/img/bg/category_bg_1_5.jpg" href="blog.html">Technology</a></li>
                                <li><a data-bg-src="assets/img/bg/category_bg_1_6.jpg" href="blog.html">Entertainment</a></li>
                            </ul>
                        </div>
                        <div class="widget">
                            <h3 class="widget_title">Recent Posts</h3>
                            <div class="recent-post-wrap">
                                <div class="recent-post">
                                    <div class="media-img"><a href="blog-details.html"><img src="assets/img/blog/recent-post-1-1.jpg" alt="Blog Image"></a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="post-title"><a class="hover-line" href="blog-details.html">Fitness: Your journey to Better, stronger
                                                you.</a></h4>
                                        <div class="recent-post-meta"><a href="blog.html"><i class="fal fa-calendar-days"></i>21 June, 2023</a></div>
                                    </div>
                                </div>
                                <div class="recent-post">
                                    <div class="media-img"><a href="blog-details.html"><img src="assets/img/blog/recent-post-1-2.jpg" alt="Blog Image"></a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="post-title"><a class="hover-line" href="blog-details.html">Embrace the game Ignite your sporting</a>
                                        </h4>
                                        <div class="recent-post-meta"><a href="blog.html"><i class="fal fa-calendar-days"></i>22 June, 2023</a></div>
                                    </div>
                                </div>
                                <div class="recent-post">
                                    <div class="media-img"><a href="blog-details.html"><img src="assets/img/blog/recent-post-1-3.jpg" alt="Blog Image"></a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="post-title"><a class="hover-line" href="blog-details.html">Revolutionizing lives Through
                                                technology</a></h4>
                                        <div class="recent-post-meta"><a href="blog.html"><i class="fal fa-calendar-days"></i>23 June, 2023</a></div>
                                    </div>
                                </div>
                                <div class="recent-post">
                                    <div class="media-img"><a href="blog-details.html"><img src="assets/img/blog/recent-post-1-4.jpg" alt="Blog Image"></a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="post-title"><a class="hover-line" href="blog-details.html">Enjoy
                                                the Virtual Reality embrace the</a></h4>
                                        <div class="recent-post-meta"><a href="blog.html"><i class="fal fa-calendar-days"></i>25 June, 2023</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget">
                            <div class="widget-ads"><a href="https://themeforest.net/user/themeholy/portfolio"><img class="w-100" src="assets/img/ads/siderbar_ads_1.jpg" alt="ads"></a></div>
                        </div>
                        <div class="widget widget_tag_cloud">
                            <h3 class="widget_title">Popular Tags</h3>
                            <div class="tagcloud"><a href="blog.html">Sports</a> <a href="blog.html">Politics</a> <a href="blog.html">Business</a> <a href="blog.html">Music</a> <a href="blog.html">Food</a> <a href="blog.html">Technology</a> <a href="blog.html">Travels</a> <a href="blog.html">Health</a> <a href="blog.html">Fashions</a> <a href="blog.html">Animal</a> <a href="blog.html">Weather</a> <a href="blog.html">Movies</a></div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
        <div class="related-post-wrapper pt-30 mb-30">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="sec-title has-line">Related Post</h2>
                </div>
                <div class="col-auto">
                    <div class="sec-btn">
                        <div class="icon-box"><button data-slick-prev="#related-post-slide" class="slick-arrow default"><i class="far fa-arrow-left"></i></button> <button data-slick-next="#related-post-slide" class="slick-arrow default"><i class="far fa-arrow-right"></i></button></div>
                    </div>
                </div>
            </div>
            <div class="row slider-shadow th-carousel" id="related-post-slide" data-slide-show="4" data-lg-slide-show="3" data-md-slide-show="2" data-sm-slide-show="2">
                <div class="col-sm-6 col-xl-4">
                    <div class="blog-style1">
                        <div class="blog-img"><img src="assets/img/blog/blog_1_1.jpg" alt="blog image"> <a data-theme-color="#00D084" href="blog.html" class="category">Lifestyle</a></div>
                        <h3 class="box-title-22"><a class="hover-line" href="blog-details.html">Balance harmony and
                                joy in Every lifestyle.</a></h3>
                        <div class="blog-meta"><a href="author.html"><i class="far fa-user"></i>By - Tnews</a> <a href="blog.html"><i class="fal fa-calendar-days"></i>12 Mar, 2023</a></div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <div class="blog-style1">
                        <div class="blog-img"><img src="assets/img/blog/blog_1_2.jpg" alt="blog image"> <a data-theme-color="#FF9500" href="blog.html" class="category">Politics</a></div>
                        <h3 class="box-title-22"><a class="hover-line" href="blog-details.html">Power to the people
                                for a Better future!</a></h3>
                        <div class="blog-meta"><a href="author.html"><i class="far fa-user"></i>By - Tnews</a> <a href="blog.html"><i class="fal fa-calendar-days"></i>14 Mar, 2023</a></div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <div class="blog-style1">
                        <div class="blog-img"><img src="assets/img/blog/blog_1_3.jpg" alt="blog image"> <a data-theme-color="#E7473C" href="blog.html" class="category">Fitness</a></div>
                        <h3 class="box-title-22"><a class="hover-line" href="blog-details.html">Fitness the key to
                                vitality and Well-being.</a></h3>
                        <div class="blog-meta"><a href="author.html"><i class="far fa-user"></i>By - Tnews</a> <a href="blog.html"><i class="fal fa-calendar-days"></i>26 Mar, 2023</a></div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <div class="blog-style1">
                        <div class="blog-img"><img src="assets/img/blog/blog_1_4.jpg" alt="blog image"> <a data-theme-color="#00D084" href="blog.html" class="category">Health</a></div>
                        <h3 class="box-title-22"><a class="hover-line" href="blog-details.html">Embrace bump and
                                Victory volleyball style.</a></h3>
                        <div class="blog-meta"><a href="author.html"><i class="far fa-user"></i>By - Tnews</a> <a href="blog.html"><i class="fal fa-calendar-days"></i>19 Mar, 2023</a></div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <div class="blog-style1">
                        <div class="blog-img"><img src="assets/img/blog/blog_1_5.jpg" alt="blog image"> <a data-theme-color="#FF9500" href="blog.html" class="category">Politics</a></div>
                        <h3 class="box-title-22"><a class="hover-line" href="blog-details.html">Leadership for the
                                People By the people</a></h3>
                        <div class="blog-meta"><a href="author.html"><i class="far fa-user"></i>By - Tnews</a> <a href="blog.html"><i class="fal fa-calendar-days"></i>15 Mar, 2023</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> --}}

<x-sections.pages.footer />

@endsection

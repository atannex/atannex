(function (e) {
    "use strict";

    // Preloader
    e(window).on("load", function () {
        e(".preloader").fadeOut();
    });

    // Refresh slick sliders on resize
    e(window).on("resize", function () {
        e(".slick-slider").slick("refresh");
    });

    // Preloader close buttons
    e(".preloader").length > 0 &&
        e(".preloaderCls").each(function () {
            e(this).on("click", function (t) {
                t.preventDefault();
                e(".preloader").css("display", "none");
            });
        });

    // Mobile menu plugin
    e.fn.thmobilemenu = function (t) {
        var s = e.extend(
            {
                menuToggleBtn: ".th-menu-toggle",
                bodyToggleClass: "th-body-visible",
                subMenuClass: "th-submenu",
                subMenuParent: "th-item-has-children",
                subMenuParentToggle: "th-active",
                meanExpandClass: "th-mean-expand",
                appendElement: '<span class="th-mean-expand"></span>',
                subMenuToggleClass: "th-open",
                toggleSpeed: 400,
            },
            t
        );

        return this.each(function () {
            var t = e(this);

            function a() {
                t.toggleClass(s.bodyToggleClass);
                t.find("." + s.subMenuClass).each(function () {
                    e(this).hasClass(s.subMenuToggleClass) &&
                        (e(this).removeClass(s.subMenuToggleClass),
                        e(this).css("display", "none"),
                        e(this).parent().removeClass(s.subMenuParentToggle));
                });
            }

            t.find("li").each(function () {
                var t = e(this).find("ul, div.mega-menu");
                t.addClass(s.subMenuClass).css("display", "none");
                t.parent().addClass(s.subMenuParent);
                t.prev("a").append(s.appendElement);
                t.next("a").append(s.appendElement);
            });

            var i = "." + s.meanExpandClass;
            e(i).each(function () {
                e(this).on("click", function (t) {
                    t.preventDefault();
                    var a = e(this).parent(),
                        i = a.next("ul, div.mega-menu");
                    if (i.length > 0) {
                        a.parent().toggleClass(s.subMenuParentToggle);
                        i.slideToggle(s.toggleSpeed).toggleClass(s.subMenuToggleClass);
                    }
                });
            });

            e(s.menuToggleBtn).each(function () {
                e(this).on("click", a);
            });

            t.on("click", function (e) {
                e.stopPropagation();
                a();
            });

            t.find("div").on("click", function (e) {
                e.stopPropagation();
            });
        });
    };

    e(".th-menu-wrapper").thmobilemenu();

    // Sticky header
    e(window).scroll(function () {
        e(this).scrollTop() > 500
            ? e(".sticky-wrapper").addClass("sticky")
            : e(".sticky-wrapper").removeClass("sticky");
    });

    // Scroll to top
    if (e(".scroll-top").length > 0) {
        var t = document.querySelector(".scroll-top"),
            s = document.querySelector(".scroll-top path"),
            a = s.getTotalLength();
        s.style.transition = s.style.WebkitTransition = "none";
        s.style.strokeDasharray = a + " " + a;
        s.style.strokeDashoffset = a;
        s.getBoundingClientRect();
        s.style.transition = s.style.WebkitTransition = "stroke-dashoffset 10ms linear";

        var i = function () {
            var t = e(window).scrollTop(),
                i = e(document).height() - e(window).height(),
                n = a - (t * a) / i;
            s.style.strokeDashoffset = n;
        };

        i();
        e(window).scroll(i);

        jQuery(window).on("scroll", function () {
            jQuery(this).scrollTop() > 50 ? jQuery(t).addClass("show") : jQuery(t).removeClass("show");
        });

        jQuery(t).on("click", function (e) {
            e.preventDefault();
            jQuery("html, body").animate({ scrollTop: 0 }, 750);
            return false;
        });
    }

    // Background images & colors
    e("[data-bg-src]").each(function () {
        var t = e(this).attr("data-bg-src");
        e(this).css("background-image", "url(" + t + ")").removeAttr("data-bg-src").addClass("background-image");
    });

    e("[data-bg-color]").each(function () {
        var t = e(this).attr("data-bg-color");
        e(this).css("background-color", t).removeAttr("data-bg-color");
    });

    e("[data-theme-color]").each(function () {
        var t = e(this).attr("data-theme-color");
        e(this).get(0).style.setProperty("--theme-color", t);
        e(this).removeAttr("data-theme-color");
    });

    e("[data-mask-src]").each(function () {
        var t = e(this).attr("data-mask-src");
        e(this)
            .css({ "mask-image": "url(" + t + ")", "-webkit-mask-image": "url(" + t + ")" })
            .addClass("bg-mask")
            .removeAttr("data-mask-src");
    });

    // Carousels
    e(".th-carousel").each(function () {
        var t = e(this);

        function s(e) {
            return t.data(e);
        }

        var a = '<button type="button" class="slick-prev"><i class="' + s("prev-arrow") + '"></i></button>',
            i = '<button type="button" class="slick-next"><i class="' + s("next-arrow") + '"></i></button>';

        t.slick({
            dots: !!s("dots"),
            fade: !!s("fade"),
            arrows: !!s("arrows"),
            speed: s("speed") ? s("speed") : 1000,
            asNavFor: !!s("asnavfor") && s("asnavfor"),
            autoplay: s("autoplay") != 0,
            infinite: s("infinite") != 0,
            slidesToShow: s("slide-show") ? s("slide-show") : 1,
            adaptiveHeight: !!s("adaptive-height"),
            centerMode: !!s("center-mode"),
            autoplaySpeed: s("autoplay-speed") ? s("autoplay-speed") : 8000,
            centerPadding: s("center-padding") ? s("center-padding") : "0",
            focusOnSelect: s("focuson-select") != 0,
            pauseOnFocus: !!s("pauseon-focus"),
            pauseOnHover: !!s("pauseon-hover"),
            variableWidth: !!s("variable-width"),
            vertical: !!s("vertical"),
            verticalSwiping: !!s("vertical"),
            swipeToSlide: !!s("swipetoslide"),
            prevArrow: s("prev-arrow") ? a : '<button type="button" class="slick-prev"><i class="fas fa-arrow-left"></i></button>',
            nextArrow: s("next-arrow") ? i : '<button type="button" class="slick-next"><i class="fas fa-arrow-right"></i></button>',
            rtl: e("html").attr("dir") === "rtl",
            responsive: [
                { breakpoint: 1600, settings: { arrows: !!s("xl-arrows"), dots: !!s("xl-dots"), slidesToShow: s("xl-slide-show") ? s("xl-slide-show") : s("slide-show"), centerMode: !!s("xl-center-mode"), centerPadding: 0 } },
                { breakpoint: 1400, settings: { arrows: !!s("ml-arrows"), dots: !!s("ml-dots"), slidesToShow: s("ml-slide-show") ? s("ml-slide-show") : s("slide-show"), centerMode: !!s("ml-center-mode"), centerPadding: 0 } },
                { breakpoint: 1200, settings: { arrows: !!s("lg-arrows"), dots: !!s("lg-dots"), slidesToShow: s("lg-slide-show") ? s("lg-slide-show") : s("slide-show"), centerMode: !!s("lg-center-mode"), centerPadding: 0 } },
                { breakpoint: 992, settings: { arrows: !!s("md-arrows"), dots: !!s("md-dots"), slidesToShow: s("md-slide-show") ? s("md-slide-show") : 1, centerMode: !!s("md-center-mode"), centerPadding: 0 } },
                { breakpoint: 768, settings: { arrows: !!s("sm-arrows"), dots: !!s("sm-dots"), slidesToShow: s("sm-slide-show") ? s("sm-slide-show") : 1, centerMode: !!s("sm-center-mode"), centerPadding: 0, variableWidth: !!s("sm-variable-width") } },
                { breakpoint: 576, settings: { arrows: !!s("xs-arrows"), dots: !!s("xs-dots"), slidesToShow: s("xs-slide-show") ? s("xs-slide-show") : 1, centerMode: !!s("xs-center-mode"), centerPadding: 0, variableWidth: !!s("xs-variable-width") } }
            ]
        });
    });

    // Force dark theme globally
    e("html").addClass("dark-theme").attr("data-theme", "dark");

    // Removed light theme and toggler logic completely

})(jQuery);

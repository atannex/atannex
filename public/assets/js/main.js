(function (e) {
    "use strict";

    /* =====================
       WINDOW EVENTS
    ====================== */
    e(window).on("load", function () {
        e(".preloader").fadeOut();
    });

    e(window).on("resize", function () {
        e(".slick-slider").not(".slick-marquee").slick("refresh");
    });

    /* =====================
       PRELOADER CLOSE
    ====================== */
    if (e(".preloader").length) {
        e(".preloaderCls").on("click", function (t) {
            t.preventDefault();
            e(".preloader").hide();
        });
    }

    /* =====================
       MOBILE MENU
    ====================== */
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

            function toggleMenu() {
                t.toggleClass(s.bodyToggleClass);
                t.find("." + s.subMenuClass)
                    .removeClass(s.subMenuToggleClass)
                    .hide()
                    .parent()
                    .removeClass(s.subMenuParentToggle);
            }

            t.find("li").each(function () {
                var a = e(this).find("ul, div.mega-menu");
                if (a.length) {
                    a.addClass(s.subMenuClass).hide();
                    a.parent().addClass(s.subMenuParent);
                    a.prev("a").append(s.appendElement);
                }
            });

            t.on("click", "." + s.meanExpandClass, function (a) {
                a.preventDefault();
                var i = e(this).parent().next("ul, div.mega-menu");
                i.slideToggle(s.toggleSpeed)
                    .toggleClass(s.subMenuToggleClass)
                    .parent()
                    .toggleClass(s.subMenuParentToggle);
            });

            e(s.menuToggleBtn).on("click", toggleMenu);
            t.on("click", function (e) {
                e.stopPropagation();
                toggleMenu();
            });
            t.find("div").on("click", function (e) {
                e.stopPropagation();
            });
        });
    };

    e(".th-menu-wrapper").thmobilemenu();

    /* =====================
       STICKY HEADER
    ====================== */
    e(window).on("scroll", function () {
        e(this).scrollTop() > 500
            ? e(".sticky-wrapper").addClass("sticky")
            : e(".sticky-wrapper").removeClass("sticky");
    });

    /* =====================
       SLICK CAROUSELS
    ====================== */
    e(".th-carousel").each(function () {
        var t = e(this);
        if (t.hasClass("slick-initialized")) return;

        t.slick({
            dots: !!t.data("dots"),
            arrows: !!t.data("arrows"),
            speed: t.data("speed") || 1000,
            autoplay: t.data("autoplay") !== 0,
            autoplaySpeed: t.data("autoplay-speed") || 8000,
            infinite: t.data("infinite") !== 0,
            slidesToShow: t.data("slide-show") || 1,
            adaptiveHeight: !!t.data("adaptive-height"),
            centerMode: !!t.data("center-mode"),
            variableWidth: !!t.data("variable-width"),
            pauseOnHover: true,
            pauseOnFocus: true,
            rtl: e("html").attr("dir") === "rtl",
        });
    });

    /* =====================
       🔥 FIXED MARQUEE (ONCE ONLY)
    ====================== */
    var $marquee = e(".slick-marquee");

    if ($marquee.length && !$marquee.hasClass("slick-initialized")) {
        $marquee.slick({
            speed: 18000, // smooth & readable
            autoplay: true,
            autoplaySpeed: 0,
            cssEase: "linear",
            slidesToShow: 1,
            slidesToScroll: 1,
            variableWidth: true,
            infinite: true,
            arrows: false,
            pauseOnHover: true,
            pauseOnFocus: true,
            swipeToSlide: false,
        });
    }

    /* =====================
       DATA ATTR HELPERS
    ====================== */
    e("[data-bg-src]").each(function () {
        e(this)
            .css("background-image", "url(" + e(this).data("bg-src") + ")")
            .removeAttr("data-bg-src")
            .addClass("background-image");
    });

    e("[data-bg-color]").each(function () {
        e(this)
            .css("background-color", e(this).data("bg-color"))
            .removeAttr("data-bg-color");
    });

    e("[data-theme-color]").each(function () {
        this.style.setProperty("--theme-color", e(this).data("theme-color"));
    });

    /* =====================
       DARK THEME FORCE
    ====================== */
    e("html").addClass("dark-theme").attr("data-theme", "dark");
    localStorage.setItem("themePreference", "dark");
    e(".theme-toggler, .theme-switcher").addClass("active").off("click");
})(jQuery);

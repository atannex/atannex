!(function (e) {
    "use strict";
    let t;
    if (
        (e(window).on("load", function () {
            e(".preloader").fadeOut();
        }),
        e(window).on("resize", function () {
            e(".slick-slider").slick("refresh");
        }),
        e(".preloader").length > 0 &&
            e(document).on("click", ".preloaderCls", function (t) {
                (t.preventDefault(), e(".preloader").css("display", "none"));
            }),
        (e.fn.thmobilemenu = function (t) {
            var n = e.extend(
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
                t,
            );
            return this.each(function () {
                var t = e(this);
                function s() {
                    (t.toggleClass(n.bodyToggleClass),
                        t.find("." + n.subMenuClass).each(function () {
                            e(this).hasClass(n.subMenuToggleClass) &&
                                e(this)
                                    .removeClass(n.subMenuToggleClass)
                                    .slideUp(n.toggleSpeed)
                                    .parent()
                                    .removeClass(n.subMenuParentToggle);
                        }));
                }
                (t.find("li").each(function () {
                    var t = e(this).find("ul, div.mega-menu");
                    t.length &&
                        (t.addClass(n.subMenuClass).hide(),
                        e(this).addClass(n.subMenuParent),
                        t.prev("a").append(n.appendElement),
                        t.next("a").append(n.appendElement));
                }),
                    e(n.menuToggleBtn).on("click", function (e) {
                        (e.stopPropagation(), s());
                    }),
                    t.on("click", "." + n.meanExpandClass, function (t) {
                        var s, a;
                        (t.preventDefault(),
                            t.stopPropagation(),
                            (s = e(this).parent()),
                            (a = s.next("ul, div.mega-menu")).length &&
                                (s.parent().toggleClass(n.subMenuParentToggle),
                                a.slideToggle(n.toggleSpeed),
                                a.toggleClass(n.subMenuToggleClass)));
                    }),
                    e(document).on("click", function () {
                        t.hasClass(n.bodyToggleClass) && s();
                    }),
                    t.on("click", function (e) {
                        e.stopPropagation();
                    }));
            });
        }),
        e(".th-menu-wrapper").thmobilemenu(),
        e(window).on("scroll", function () {
            t ||
                (t = setTimeout(function () {
                    t = null;
                    var n = e(window).scrollTop();
                    e(".sticky-wrapper").toggleClass("sticky", n > 500);
                }, 100));
        }),
        e(".scroll-top").length > 0)
    ) {
        const t = e(".scroll-top"),
            n = document.querySelector(".scroll-top path");
        if (n) {
            const s = n.getTotalLength();
            ((n.style.transition = "none"),
                (n.style.strokeDasharray = s + " " + s),
                (n.style.strokeDashoffset = s),
                n.getBoundingClientRect(),
                (n.style.transition = "stroke-dashoffset 10ms linear"));
            const a = () => {
                const t = e(window).scrollTop(),
                    a = e(document).height() - e(window).height(),
                    o = s - (t * s) / a;
                n.style.strokeDashoffset = o;
            };
            let o;
            (a(),
                e(window).on("scroll", function () {
                    o ||
                        (o = setTimeout(() => {
                            ((o = null),
                                a(),
                                e(this).scrollTop() > 50
                                    ? t.addClass("show")
                                    : t.removeClass("show"));
                        }, 50));
                }),
                e(document).on("click", ".scroll-top", function (t) {
                    (t.preventDefault(),
                        e("html, body").animate({ scrollTop: 0 }, 750));
                }));
        }
    }
    (e("[data-bg-src]").each(function () {
        const t = e(this).attr("data-bg-src");
        e(this)
            .css("background-image", "url(" + t + ")")
            .removeAttr("data-bg-src")
            .addClass("background-image");
    }),
        e("[data-bg-color]").each(function () {
            const t = e(this).attr("data-bg-color");
            e(this).css("background-color", t).removeAttr("data-bg-color");
        }),
        e("[data-theme-color]").each(function () {
            const t = e(this).attr("data-theme-color");
            (this.style.setProperty("--theme-color", t),
                e(this).removeAttr("data-theme-color"));
        }),
        e("[data-mask-src]").each(function () {
            const t = e(this).attr("data-mask-src");
            e(this)
                .css({
                    "mask-image": "url(" + t + ")",
                    "-webkit-mask-image": "url(" + t + ")",
                })
                .addClass("bg-mask")
                .removeAttr("data-mask-src");
        }));
    function n(e, t) {
        return {
            arrows: !!e(`${t}-arrows`),
            dots: !!e(`${t}-dots`),
            slidesToShow: e(`${t}-slide-show`) || e("slide-show") || 1,
            centerMode: !!e(`${t}-center-mode`),
            centerPadding: 0,
            variableWidth: !!e(`${t}-variable-width`),
        };
    }
    (e(".center-first").on("init reInit afterChange", function (e, t, n) {}),
        e(".th-carousel").each(function () {
            const t = e(this),
                s = (e) => t.data(e),
                a = s("prev-arrow")
                    ? `<button type="button" class="slick-prev"><i class="${s("prev-arrow")}"></i></button>`
                    : '<button type="button" class="slick-prev"><i class="fas fa-arrow-left"></i></button>',
                o = s("next-arrow")
                    ? `<button type="button" class="slick-next"><i class="${s("next-arrow")}"></i></button>`
                    : '<button type="button" class="slick-next"><i class="fas fa-arrow-right"></i></button>';
            (!0 !== s("arrows") ||
                t.closest(".arrow-wrap").length ||
                t.closest(".container").parent().addClass("arrow-wrap"),
                t.slick({
                    dots: !!s("dots"),
                    fade: !!s("fade"),
                    arrows: !!s("arrows"),
                    speed: s("speed") || 1e3,
                    asNavFor: s("asnavfor") || !1,
                    autoplay: !1 !== s("autoplay"),
                    infinite: !1 !== s("infinite"),
                    slidesToShow: s("slide-show") || 1,
                    adaptiveHeight: !!s("adaptive-height"),
                    centerMode: !!s("center-mode"),
                    centerPadding: s("center-padding") || "0",
                    autoplaySpeed: s("autoplay-speed") || 8e3,
                    focusOnSelect: !1 !== s("focuson-select"),
                    pauseOnFocus: !!s("pauseon-focus"),
                    pauseOnHover: !!s("pauseon-hover"),
                    variableWidth: !!s("variable-width"),
                    vertical: !!s("vertical"),
                    verticalSwiping: !!s("vertical"),
                    swipeToSlide: !!s("swipetoslide"),
                    rtl: "rtl" === e("html").attr("dir"),
                    prevArrow: a,
                    nextArrow: o,
                    responsive: [
                        { breakpoint: 1600, settings: n(s, "xl") },
                        { breakpoint: 1400, settings: n(s, "ml") },
                        { breakpoint: 1200, settings: n(s, "lg") },
                        { breakpoint: 992, settings: n(s, "md") },
                        { breakpoint: 768, settings: n(s, "sm") },
                        { breakpoint: 576, settings: n(s, "xs") },
                    ],
                }));
        }),
        e(document).on("click", "[data-slick-next]", function (t) {
            t.preventDefault();
            const n = e(this).data("slick-next");
            e(n).slick("slickNext");
        }),
        e(document).on("click", "[data-slick-prev]", function (t) {
            t.preventDefault();
            const n = e(this).data("slick-prev");
            e(n).slick("slickPrev");
        }),
        e(".slick-marquee").slick({
            speed: 5e3,
            autoplay: !0,
            autoplaySpeed: 0,
            cssEase: "linear",
            slidesToShow: 1,
            slidesToScroll: 1,
            variableWidth: !0,
            infinite: !0,
            arrows: !1,
            buttons: !1,
            pauseOnHover: !0,
            pauseOnFocus: !0,
            swipeToSlide: !0,
        }),
        e("[data-ani-duration]").each(function () {
            e(this).css("animation-duration", e(this).data("ani-duration"));
        }),
        e("[data-ani-delay]").each(function () {
            e(this).css("animation-delay", e(this).data("ani-delay"));
        }),
        e(".slick-current [data-ani]").each(function () {
            e(this).addClass("th-animated " + e(this).data("ani"));
        }),
        e(document).on("afterChange", ".th-carousel", function (t, n, s) {
            (e(n.$slides).find("[data-ani]").removeClass("th-animated"),
                e(n.$slides[s])
                    .find("[data-ani]")
                    .each(function () {
                        e(this).addClass("th-animated " + e(this).data("ani"));
                    }));
        }));
    const s = "is-invalid",
        a = e(".ajax-contact"),
        o = e(".form-messages"),
        i = () => {
            let t = !0;
            a.find(
                '[name="name"],[name="email"],[name="subject"],[name="number"],[name="message"]',
            ).each(function () {
                const n = e(this);
                n.val() ? n.removeClass(s) : (n.addClass(s), (t = !1));
            });
            const n = a.find('[name="email"]'),
                o = n.val();
            return (
                o && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(o)
                    ? n.removeClass(s)
                    : (n.addClass(s), (t = !1)),
                t
            );
        };
    var l, c, r, d, u, p;
    function h(t, n, s, a) {
        (e(document).on("click", n, function (n) {
            (n.preventDefault(), e(t).addClass(a));
        }),
            e(document).on("click", t, function () {
                e(t).removeClass(a);
            }),
            e(document).on("click", `${t} > div`, function (e) {
                e.stopPropagation();
            }),
            e(document).on("click", s, function (n) {
                (n.preventDefault(), n.stopPropagation(), e(t).removeClass(a));
            }));
    }
    (a.on("submit", function (t) {
        (t.preventDefault(),
            (() => {
                const t = a.serialize();
                i() &&
                    e
                        .ajax({ url: a.attr("action"), type: "POST", data: t })
                        .done((e) => {
                            (o.removeClass("error").addClass("success").text(e),
                                a
                                    .find(
                                        "input:not([type='submit']), textarea",
                                    )
                                    .val(""));
                        })
                        .fail((e) => {
                            o.removeClass("success")
                                .addClass("error")
                                .html(
                                    e.responseText ||
                                        "Oops! An error occurred and your message could not be sent.",
                                );
                        });
            })());
    }),
        (l = ".popup-search-box"),
        (c = ".searchBoxToggler"),
        (r = ".searchClose"),
        (d = "show"),
        e(document).on("click", c, function (t) {
            (t.preventDefault(), e(l).addClass(d));
        }),
        e(document).on("click", r, function (t) {
            (t.preventDefault(), e(l).removeClass(d));
        }),
        e(document).on("click", l, function () {
            e(this).removeClass(d);
        }),
        e(document).on("click", `${l} form`, function (e) {
            e.stopPropagation();
        }),
        (u = ".popup-subscribe-area"),
        (p = ".popupClose"),
        e(document).on("click", p, function () {
            e(u).addClass("hide");
        }),
        e("#destroyPopup").on("click", function () {
            (e(".popup-subscribe-area").addClass("hide"),
                localStorage.setItem("popupDestroyed", "true"));
        }),
        "true" === localStorage.getItem("popupDestroyed") &&
            e(".popup-subscribe-area").hide(),
        h(".sidemenu-1", ".sideMenuToggler", ".sideMenuCls", "show"),
        h(".cart-side-menu", ".cartToggler", ".sideMenuCls", "show"),
        e(".popup-image").magnificPopup({
            type: "image",
            mainClass: "mfp-zoom-in",
            removalDelay: 260,
            gallery: { enabled: !0 },
        }),
        e(".popup-video").magnificPopup({ type: "iframe" }),
        e(".popup-content").magnificPopup({
            type: "inline",
            midClick: !0,
            callbacks: {
                open: function () {
                    e(".slick-slider").slick("refresh");
                },
            },
        }));
    const f = e("html"),
        m = e(".theme-switcher");
    const g = localStorage.getItem("themePreference");
    function v(t, n, s = "*") {
        e(t).imagesLoaded(function () {
            const a = e(t).isotope({
                itemSelector: ".filter-item",
                filter: s,
                masonry: { columnWidth: 1 },
            });
            e(n).on("click", "button", function (t) {
                t.preventDefault();
                const n = e(this).attr("data-filter");
                (a.isotope({ filter: n }),
                    e(this)
                        .addClass("active")
                        .siblings()
                        .removeClass("active"));
            });
        });
    }
    ("dark" === g
        ? (f.addClass("dark-theme").attr("data-theme", "dark"),
          m.addClass("active"))
        : "light" === g &&
          (f.addClass("light-theme").attr("data-theme", "light"),
          m.removeClass("active")),
        e(document).on(
            "click",
            ".theme-toggler, .theme-switcher",
            function (e) {
                (e.preventDefault(),
                    f.hasClass("dark-theme")
                        ? (f
                              .removeClass("dark-theme")
                              .addClass("light-theme")
                              .attr("data-theme", "light"),
                          m.removeClass("active"),
                          localStorage.setItem("themePreference", "light"))
                        : (f
                              .removeClass("light-theme")
                              .addClass("dark-theme")
                              .attr("data-theme", "dark"),
                          m.addClass("active"),
                          localStorage.setItem("themePreference", "dark")));
            },
        ),
        e(document).on("click", ".print_btn", function () {
            window.print();
        }),
        (e.fn.indicator = function () {
            return this.each(function () {
                const t = e(this);
                if (0 === t.find("a, button").length) return;
                t.append('<span class="indicator"></span>');
                const n = t.find(".indicator");
                function s() {
                    const e = t.find(".active");
                    if (!e.length) return;
                    const s = e.outerHeight() + "px",
                        a = e.outerWidth() + "px",
                        o = e.position().top + "px",
                        i = e.position().left + "px";
                    n.css({
                        "--height-set": s,
                        "--width-set": a,
                        "--pos-y": o,
                        "--pos-x": i,
                    });
                }
                (t.on("click", "a, button", function (t) {
                    (t.preventDefault(),
                        e(this)
                            .addClass("active")
                            .siblings()
                            .removeClass("active"),
                        s());
                }),
                    s(),
                    e(window).on("resize", s));
            });
        }),
        e(".indicator-active").length && e(".indicator-active").indicator(),
        (e.fn.thTab = function (t) {
            const n = e.extend({ sliderTab: !1, tabButton: "button" }, t);
            return this.each(function () {
                const t = e(this),
                    s = t.find(n.tabButton),
                    a = t.data("asnavfor"),
                    o = a ? e(a) : null;
                t.append('<span class="indicator"></span>');
                const i = t.find(".indicator");
                function l() {
                    const e = t.find(n.tabButton + ".active");
                    if (!e.length) return;
                    const a = e.outerHeight() + "px",
                        o = e.outerWidth() + "px",
                        l = e.position().top + "px",
                        c = e.position().left + "px";
                    i.css({
                        "--height-set": a,
                        "--width-set": o,
                        "--pos-y": l,
                        "--pos-x": c,
                    });
                    const r = s.first().position().left,
                        d = s.last().position().left,
                        u = e.position().left;
                    i.toggleClass("start", u === r)
                        .toggleClass("end", u === d)
                        .toggleClass("center", u !== r && u !== d);
                }
                if (
                    (t.on("click", n.tabButton, function (t) {
                        t.preventDefault();
                        const s = e(this);
                        (s.addClass("active").siblings().removeClass("active"),
                            n.sliderTab &&
                                o &&
                                o.length &&
                                o.slick("slickGoTo", s.data("slide-go-to")),
                            l());
                    }),
                    n.sliderTab && o && o.length)
                ) {
                    (s.each(function (t) {
                        e(this).attr("data-slide-go-to", t);
                    }),
                        o.on("beforeChange", function (e, t, n, a) {
                            (s
                                .removeClass("active")
                                .filter(`[data-slide-go-to="${a}"]`)
                                .addClass("active"),
                                l());
                        }));
                    const t = s.filter(".active");
                    t.length && o.slick("slickGoTo", t.data("slide-go-to"));
                }
                (l(), e(window).on("resize", l));
            });
        }),
        e(".hero-tab").length &&
            e(".hero-tab").thTab({ sliderTab: !0, tabButton: ".tab-btn" }),
        e(".blog-tab").length &&
            e(".blog-tab").thTab({ sliderTab: !0, tabButton: ".tab-btn" }),
        v(".filter-active", ".filter-menu-active"),
        v(".filter-active-cat1", ".filter-menu-active1", ".active-filter"),
        e.fn.counterUp &&
            e(".counter-number").counterUp({ delay: 5, time: 600 }),
        e.fn.slider &&
            e(".price_slider").length &&
            (e(".price_slider").slider({
                range: !0,
                min: 10,
                max: 100,
                values: [10, 75],
                slide: function (t, n) {
                    (e(".from").text("$" + n.values[0]),
                        e(".to").text("$" + n.values[1]));
                },
            }),
            e(".from").text("$" + e(".price_slider").slider("values", 0)),
            e(".to").text("$" + e(".price_slider").slider("values", 1))),
        e("#ship-to-different-address-checkbox").on("change", function () {
            const t = e("#ship-to-different-address").next(".shipping_address");
            e(this).is(":checked") ? t.slideDown() : t.slideUp();
        }),
        e(".woocommerce-form-login-toggle").on("click", "a", function (t) {
            (t.preventDefault(), e(".woocommerce-form-login").slideToggle());
        }),
        e(".woocommerce-form-coupon-toggle").on("click", "a", function (t) {
            (t.preventDefault(), e(".woocommerce-form-coupon").slideToggle());
        }),
        e(".shipping-calculator-button").on("click", function (t) {
            (t.preventDefault(),
                e(this).next(".shipping-calculator-form").slideToggle());
        }));
    const b = e(".wc_payment_methods");
    (b.find('input[type="radio"]').each(function () {
        this.checked && e(this).siblings(".payment_box").show();
    }),
        b.on("change", 'input[type="radio"]', function () {
            (e(".payment_box").slideUp(),
                e(this).siblings(".payment_box").slideDown());
        }),
        e(".rating-select .stars").on("click", "a", function (t) {
            t.preventDefault();
            const n = e(this);
            (n.addClass("active").siblings().removeClass("active"),
                n.closest(".stars").parent().addClass("selected"));
        }),
        e(".quantity-plus").on("click", function (t) {
            t.preventDefault();
            const n = e(this).siblings(".qty-input"),
                s = parseInt(n.val(), 10) || 0;
            n.val(s + 1);
        }),
        e(".quantity-minus").on("click", function (t) {
            t.preventDefault();
            const n = e(this).siblings(".qty-input"),
                s = parseInt(n.val(), 10) || 0;
            s > 1 && n.val(s - 1);
        }),
        window.addEventListener(
            "contextmenu",
            function (e) {
                e.preventDefault();
            },
            !1,
        ),
        (document.onkeydown = function (e) {
            return (
                123 != event.keyCode &&
                (!e.ctrlKey || !e.shiftKey || e.keyCode != "I".charCodeAt(0)) &&
                (!e.ctrlKey || !e.shiftKey || e.keyCode != "C".charCodeAt(0)) &&
                (!e.ctrlKey || !e.shiftKey || e.keyCode != "J".charCodeAt(0)) &&
                (!e.ctrlKey || e.keyCode != "U".charCodeAt(0)) &&
                void 0
            );
        }));
})(jQuery);

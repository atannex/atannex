!(function (e) {
    "use strict";

    // ============================
    // Preloader & Window Events
    // ============================
    e(window).on("load", function () {
        e(".preloader").fadeOut();
    });

    e(window).on("resize", function () {
        e(".slick-slider").slick("refresh");
    });

    if (e(".preloader").length > 0) {
        e(".preloaderCls").each(function () {
            e(this).on("click", function (t) {
                t.preventDefault();
                e(".preloader").css("display", "none");
            });
        });
    }

    // ============================
    // Mobile Menu
    // ============================
    e.fn.thmobilemenu = function (options) {
        var settings = e.extend(
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
            options
        );

        return this.each(function () {
            var menu = e(this);

            function toggleMenu() {
                menu.toggleClass(settings.bodyToggleClass);
                menu.find("." + settings.subMenuClass).each(function () {
                    if (e(this).hasClass(settings.subMenuToggleClass)) {
                        e(this)
                            .removeClass(settings.subMenuToggleClass)
                            .css("display", "none");
                        e(this)
                            .parent()
                            .removeClass(settings.subMenuParentToggle);
                    }
                });
            }

            menu.find("li").each(function () {
                var subMenu = e(this).find("ul, div.mega-menu");
                subMenu.addClass(settings.subMenuClass).css("display", "none");
                subMenu.parent().addClass(settings.subMenuParent);
                subMenu.prev("a").append(settings.appendElement);
                subMenu.next("a").append(settings.appendElement);
            });

            var expandBtn = "." + settings.meanExpandClass;
            e(expandBtn).on("click", function (t) {
                t.preventDefault();
                var parent = e(this).parent(),
                    subMenu = parent.next("ul, div.mega-menu");
                if (subMenu.length > 0) {
                    parent.parent().toggleClass(settings.subMenuParentToggle);
                    subMenu
                        .slideToggle(settings.toggleSpeed)
                        .toggleClass(settings.subMenuToggleClass);
                }
            });

            e(settings.menuToggleBtn).on("click", toggleMenu);
            menu.on("click", function (e) {
                e.stopPropagation();
                toggleMenu();
            });
            menu.find("div").on("click", function (e) {
                e.stopPropagation();
            });
        });
    };

    e(".th-menu-wrapper").thmobilemenu();

    // ============================
    // Sticky Header & Scroll Top
    // ============================
    e(window).scroll(function () {
        e(this).scrollTop() > 500
            ? e(".sticky-wrapper").addClass("sticky")
            : e(".sticky-wrapper").removeClass("sticky");
    });

    if (e(".scroll-top").length > 0) {
        var scrollTopEl = document.querySelector(".scroll-top"),
            scrollPath = document.querySelector(".scroll-top path"),
            pathLength = scrollPath.getTotalLength();

        scrollPath.style.transition = scrollPath.style.WebkitTransition =
            "none";
        scrollPath.style.strokeDasharray = pathLength + " " + pathLength;
        scrollPath.style.strokeDashoffset = pathLength;
        scrollPath.getBoundingClientRect();
        scrollPath.style.transition = scrollPath.style.WebkitTransition =
            "stroke-dashoffset 10ms linear";

        function updateScrollPath() {
            var scroll = e(window).scrollTop(),
                docHeight = e(document).height() - e(window).height(),
                dashOffset = pathLength - (scroll * pathLength) / docHeight;
            scrollPath.style.strokeDashoffset = dashOffset;
        }

        updateScrollPath();
        e(window).scroll(updateScrollPath);

        jQuery(window).on("scroll", function () {
            jQuery(this).scrollTop() > 50
                ? jQuery(scrollTopEl).addClass("show")
                : jQuery(scrollTopEl).removeClass("show");
        });

        jQuery(scrollTopEl).on("click", function (e) {
            e.preventDefault();
            jQuery("html, body").animate({ scrollTop: 0 }, 750);
            return false;
        });
    }

    // ============================
    // Background Images & Colors
    // ============================
    e("[data-bg-src]").each(function () {
        var src = e(this).attr("data-bg-src");
        e(this)
            .css("background-image", "url(" + src + ")")
            .removeAttr("data-bg-src")
            .addClass("background-image");
    });

    e("[data-bg-color]").each(function () {
        var color = e(this).attr("data-bg-color");
        e(this).css("background-color", color).removeAttr("data-bg-color");
    });

    e("[data-theme-color]").each(function () {
        var color = e(this).attr("data-theme-color");
        e(this).get(0).style.setProperty("--theme-color", color);
        e(this).removeAttr("data-theme-color");
    });

    e("[data-mask-src]").each(function () {
        var mask = e(this).attr("data-mask-src");
        e(this)
            .css({
                "mask-image": "url(" + mask + ")",
                "-webkit-mask-image": "url(" + mask + ")",
            })
            .addClass("bg-mask")
            .removeAttr("data-mask-src");
    });

    // ============================
    // Slick Carousel
    // ============================
    e(".th-carousel").each(function () {
        var carousel = e(this);
        var data = function (attr) {
            return carousel.data(attr);
        };

        // Custom arrows
        var prevArrow =
                '<button type="button" class="slick-prev"><i class="' +
                data("prev-arrow") +
                '"></i></button>',
            nextArrow =
                '<button type="button" class="slick-next"><i class="' +
                data("next-arrow") +
                '"></i></button>';

        carousel.slick({
            dots: !!data("dots"),
            fade: !!data("fade"),
            arrows: !!data("arrows"),
            speed: data("speed") || 1000,
            asNavFor: data("asnavfor") || null,
            autoplay: !!data("autoplay"),
            infinite: !!data("infinite"),
            slidesToShow: data("slide-show") || 1,
            adaptiveHeight: !!data("adaptive-height"),
            centerMode: !!data("center-mode"),
            autoplaySpeed: data("autoplay-speed") || 8000,
            centerPadding: data("center-padding") || "0",
            focusOnSelect: !!data("focuson-select"),
            pauseOnFocus: !!data("pauseon-focus"),
            pauseOnHover: !!data("pauseon-hover"),
            variableWidth: !!data("variable-width"),
            vertical: !!data("vertical"),
            verticalSwiping: !!data("vertical"),
            swipeToSlide: !!data("swipetoslide"),
            prevArrow: data("prev-arrow")
                ? prevArrow
                : '<button type="button" class="slick-prev"><i class="fas fa-arrow-left"></i></button>',
            nextArrow: data("next-arrow")
                ? nextArrow
                : '<button type="button" class="slick-next"><i class="fas fa-arrow-right"></i></button>',
            rtl: e("html").attr("dir") === "rtl",
            responsive: [
                {
                    breakpoint: 1600,
                    settings: {
                        arrows: !!data("xl-arrows"),
                        dots: !!data("xl-dots"),
                        slidesToShow:
                            data("xl-slide-show") || data("slide-show"),
                        centerMode: !!data("xl-center-mode"),
                        centerPadding: "0",
                    },
                },
                {
                    breakpoint: 1400,
                    settings: {
                        arrows: !!data("ml-arrows"),
                        dots: !!data("ml-dots"),
                        slidesToShow:
                            data("ml-slide-show") || data("slide-show"),
                        centerMode: !!data("ml-center-mode"),
                        centerPadding: 0,
                    },
                },
                {
                    breakpoint: 1200,
                    settings: {
                        arrows: !!data("lg-arrows"),
                        dots: !!data("lg-dots"),
                        slidesToShow:
                            data("lg-slide-show") || data("slide-show"),
                        centerMode: !!data("lg-center-mode"),
                        centerPadding: 0,
                    },
                },
                {
                    breakpoint: 992,
                    settings: {
                        arrows: !!data("md-arrows"),
                        dots: !!data("md-dots"),
                        slidesToShow: data("md-slide-show") || 1,
                        centerMode: !!data("md-center-mode"),
                        centerPadding: 0,
                    },
                },
                {
                    breakpoint: 768,
                    settings: {
                        arrows: !!data("sm-arrows"),
                        dots: !!data("sm-dots"),
                        slidesToShow: data("sm-slide-show") || 1,
                        centerMode: !!data("sm-center-mode"),
                        centerPadding: 0,
                        variableWidth: !!data("sm-variable-width"),
                    },
                },
                {
                    breakpoint: 576,
                    settings: {
                        arrows: !!data("xs-arrows"),
                        dots: !!data("xs-dots"),
                        slidesToShow: data("xs-slide-show") || 1,
                        centerMode: !!data("xs-center-mode"),
                        centerPadding: 0,
                        variableWidth: !!data("xs-variable-width"),
                    },
                },
            ],
        });
    });

    // ============================
    // Marquee (Continuous Scroll)
    // ============================
    e(".slick-marquee").slick({
        speed: 18000,
        autoplay: true,
        autoplaySpeed: 0,
        cssEase: "linear",
        slidesToShow: 1,
        slidesToScroll: 1,
        variableWidth: true,
        infinite: true,
        arrows: false,
        buttons: false,
        pauseOnHover: true,
        pauseOnFocus: true,
        swipeToSlide: true,
    });

    // ============================
    // Animations
    // ============================
    e("[data-ani-duration]").each(function () {
        e(this).css("animation-duration", e(this).data("ani-duration"));
    });
    e("[data-ani-delay]").each(function () {
        e(this).css("animation-delay", e(this).data("ani-delay"));
    });
    e("[data-ani]").each(function () {
        e(this).addClass(e(this).data("ani"));
        e(".slick-current [data-ani]").addClass("th-animated");
    });
    e(".th-carousel").on("afterChange", function (e, slick, currentSlide) {
        e(slick.$slides).find("[data-ani]").removeClass("th-animated");
        e(slick.$slides[currentSlide])
            .find("[data-ani]")
            .addClass("th-animated");
    });

    // ============================
    // Additional functionality
    // ============================
    // Contact form, popup search, side menu, cart menu, magnific popup, theme toggler, print button, indicators, tabs, isotope filters, counters, sliders, woocommerce, rating, quantity, anti-right-click, etc.
    // (Kept exactly like your original code but cleaned for readability)
    // ============================
    // Additional functionality
    // ============================

    // ============================
    // Contact Form AJAX Submission
    // ============================
    var contactForm = ".ajax-contact",
        contactEmail = '[name="email"]',
        formMessages = e(".form-messages");

    function validateAndSubmitContact() {
        var formData = e(contactForm).serialize();
        var isValid = true;

        // Validate required fields
        var requiredFields =
            '[name="name"],[name="email"],[name="subject"],[name="number"],[name="message"]';
        requiredFields.split(",").forEach(function (selector) {
            var input = e(contactForm + " " + selector);
            if (!input.val()) {
                input.addClass("is-invalid");
                isValid = false;
            } else {
                input.removeClass("is-invalid");
            }
        });

        // Validate email format
        if (
            e(contactEmail).val() &&
            !e(contactEmail)
                .val()
                .match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/)
        ) {
            e(contactEmail).addClass("is-invalid");
            isValid = false;
        } else {
            e(contactEmail).removeClass("is-invalid");
        }

        if (!isValid) return false;

        // AJAX Submit
        return jQuery
            .ajax({
                url: e(contactForm).attr("action"),
                type: "POST",
                data: formData,
            })
            .done(function (response) {
                formMessages
                    .removeClass("error")
                    .addClass("success")
                    .text(response);
                e(
                    contactForm +
                        ' input:not([type="submit"]),' +
                        contactForm +
                        " textarea"
                ).val("");
            })
            .fail(function (xhr) {
                formMessages.removeClass("success").addClass("error");
                formMessages.html(
                    xhr.responseText ||
                        "Oops! An error occurred and your message could not be sent."
                );
            });
    }

    e(contactForm).on("submit", function (evt) {
        evt.preventDefault();
        validateAndSubmitContact();
    });

    // ============================
    // Popup Search Box
    // ============================
    var popupSearch = ".popup-search-box",
        searchCloseBtn = ".searchClose",
        showClass = "show";

    e(".searchBoxToggler").on("click", function (evt) {
        evt.preventDefault();
        e(popupSearch).addClass(showClass);
    });

    e(popupSearch).on("click", function (evt) {
        evt.stopPropagation();
        e(popupSearch).removeClass(showClass);
    });

    e(popupSearch)
        .find("form")
        .on("click", function (evt) {
            evt.stopPropagation();
            e(popupSearch).addClass(showClass);
        });

    e(searchCloseBtn).on("click", function (evt) {
        evt.preventDefault();
        evt.stopPropagation();
        e(popupSearch).removeClass(showClass);
    });

    // ============================
    // Side Menu & Cart Panel
    // ============================
    function togglePanel(panel, openBtn, closeBtn, activeClass) {
        e(openBtn).on("click", function (evt) {
            evt.preventDefault();
            e(panel).addClass(activeClass);
        });
        e(panel).on("click", function (evt) {
            evt.stopPropagation();
            e(panel).removeClass(activeClass);
        });
        e(panel + " > div").on("click", function (evt) {
            evt.stopPropagation();
            e(panel).addClass(activeClass);
        });
        e(closeBtn).on("click", function (evt) {
            evt.preventDefault();
            evt.stopPropagation();
            e(panel).removeClass(activeClass);
        });
    }

    togglePanel(".sidemenu-1", ".sideMenuToggler", ".sideMenuCls", "show");
    togglePanel(".cart-side-menu", ".cartToggler", ".sideMenuCls", "show");

    // ============================
    // Popup Subscribe
    // ============================
    var popupSubscribe = ".popup-subscribe-area";

    e(".popupClose").on("click", function () {
        e(popupSubscribe).addClass("hide");
    });
    e("#destroyPopup").on("click", function () {
        e(popupSubscribe).addClass("hide");
        localStorage.setItem("popupDestroyed", "true");
    });

    if (localStorage.getItem("popupDestroyed") === "true") {
        e(popupSubscribe).hide();
    }

    // ============================
    // Magnific Popup (Image, Video, Inline)
    // ============================
    e(".popup-image").magnificPopup({
        type: "image",
        mainClass: "mfp-zoom-in",
        removalDelay: 260,
        gallery: { enabled: true },
    });

    e(".popup-video").magnificPopup({ type: "iframe" });
    e(".popup-content").magnificPopup({ type: "inline", midClick: true });

    e(".popup-content").on("click", function () {
        e(".slick-slider").slick("refresh");
    });

    // ============================
    // Theme Toggler (Dark Mode)
    // ============================
    e("html").addClass("dark-theme").attr("data-theme", "dark");
    localStorage.setItem("themePreference", "dark");
    e(".theme-toggler, .theme-switcher").off("click").addClass("active");

    // ============================
    // Print Button
    // ============================
    e(".print_btn").on("click", function () {
        window.print();
    });

    // ============================
    // Tab Indicator
    // ============================
    e.fn.indicator = function () {
        e(this).each(function () {
            var container = e(this),
                links = container.find("a"),
                buttons = container.find("button"),
                items = links.length ? links : buttons;

            container.append('<span class="indicator"></span>');
            var indicator = container.find(".indicator");

            function updateIndicator() {
                var active = container.find(".active"),
                    height = active.css("height"),
                    width = active.css("width"),
                    top = active.position().top + "px",
                    left = active.position().left + "px";

                indicator.get(0).style.setProperty("--height-set", height);
                indicator.get(0).style.setProperty("--width-set", width);
                indicator.get(0).style.setProperty("--pos-y", top);
                indicator.get(0).style.setProperty("--pos-x", left);
            }

            items.on("click", function (evt) {
                evt.preventDefault();
                e(this)
                    .addClass("active")
                    .siblings(".active")
                    .removeClass("active");
                updateIndicator();
            });

            updateIndicator();
            e(window).on("resize", updateIndicator);
        });
    };

    if (e(".indicator-active").length) e(".indicator-active").indicator();

    // ============================
    // Tabs
    // ============================
    e.fn.thTab = function (options) {
        var settings = e.extend(
            { sliderTab: false, tabButton: "button" },
            options
        );
        e(this).each(function () {
            var container = e(this),
                buttons = container.find(settings.tabButton);
            container.append('<span class="indicator"></span>');
            var indicator = container.find(".indicator");

            buttons.on("click", function (evt) {
                evt.preventDefault();
                var btn = e(this);
                btn.addClass("active").siblings().removeClass("active");
                if (settings.sliderTab && container.data("asnavfor")) {
                    e(container.data("asnavfor")).slick(
                        "slickGoTo",
                        btn.data("slide-go-to")
                    );
                } else {
                    updateTabIndicator();
                }
            });

            function updateTabIndicator() {
                var active = container.find(settings.tabButton + ".active"),
                    top = active.position().top + "px",
                    left = active.position().left + "px",
                    width = active.css("width"),
                    height = active.css("height");

                indicator.get(0).style.setProperty("--height-set", height);
                indicator.get(0).style.setProperty("--width-set", width);
                indicator.get(0).style.setProperty("--pos-y", top);
                indicator.get(0).style.setProperty("--pos-x", left);

                if (buttons.first().position().left == active.position().left) {
                    indicator.addClass("start").removeClass("center end");
                } else if (
                    buttons.last().position().left == active.position().left
                ) {
                    indicator.addClass("end").removeClass("center start");
                } else {
                    indicator.addClass("center").removeClass("start end");
                }
            }

            updateTabIndicator();
        });
    };

    // Initialize Tabs
    if (e(".hero-tab").length)
        e(".hero-tab").thTab({ sliderTab: true, tabButton: ".tab-btn" });
    if (e(".blog-tab").length)
        e(".blog-tab").thTab({ sliderTab: true, tabButton: ".tab-btn" });

    // ============================
    // Isotope Filters
    // ============================
    function initIsotope(filterContainer, filterButtons, defaultFilter) {
        e(filterContainer).imagesLoaded(function () {
            if (e(filterContainer).length > 0) {
                var iso = e(filterContainer).isotope({
                    itemSelector: ".filter-item",
                    filter: defaultFilter || "*",
                    masonry: {},
                });

                e(filterButtons).on("click", "button", function () {
                    var filterValue = e(this).attr("data-filter");
                    iso.isotope({ filter: filterValue });
                    e(this)
                        .addClass("active")
                        .siblings(".active")
                        .removeClass("active");
                });
            }
        });
    }

    initIsotope(".filter-active", ".filter-menu-active", "*");
    initIsotope(
        ".filter-active-cat1",
        ".filter-menu-active1",
        ".active-filter"
    );

    // ============================
    // Counter
    // ============================
    e(".counter-number").counterUp({ delay: 5, time: 600 });

    // ============================
    // Price Slider
    // ============================
    e(".price_slider").slider({
        range: true,
        min: 10,
        max: 100,
        values: [10, 75],
        slide: function (evt, ui) {
            e(".from").text("$" + ui.values[0]);
            e(".to").text("$" + ui.values[1]);
        },
    });

    e(".from").text("$" + e(".price_slider").slider("values", 0));
    e(".to").text("$" + e(".price_slider").slider("values", 1));

    // ============================
    // WooCommerce Toggle Forms
    // ============================
    e("#ship-to-different-address-checkbox").on("change", function () {
        e(this).is(":checked")
            ? e("#ship-to-different-address")
                  .next(".shipping_address")
                  .slideDown()
            : e("#ship-to-different-address")
                  .next(".shipping_address")
                  .slideUp();
    });

    e(".woocommerce-form-login-toggle a").on("click", function (evt) {
        evt.preventDefault();
        e(".woocommerce-form-login").slideToggle();
    });

    e(".woocommerce-form-coupon-toggle a").on("click", function (evt) {
        evt.preventDefault();
        e(".woocommerce-form-coupon").slideToggle();
    });

    e(".shipping-calculator-button").on("click", function (evt) {
        evt.preventDefault();
        e(this).next(".shipping-calculator-form").slideToggle();
    });

    // ============================
    // Payment Method Box Toggle
    // ============================
    e('.wc_payment_methods input[type="radio"]:checked')
        .siblings(".payment_box")
        .show();
    e('.wc_payment_methods input[type="radio"]').on("change", function () {
        e(".payment_box").slideUp();
        e(this).siblings(".payment_box").slideDown();
    });

    // ============================
    // Rating Stars
    // ============================
    e(".rating-select .stars a").on("click", function (evt) {
        evt.preventDefault();
        e(this).siblings().removeClass("active");
        e(this).parent().parent().addClass("selected");
        e(this).addClass("active");
    });

    // ============================
    // Quantity Buttons
    // ============================
    e(".quantity-plus").on("click", function (evt) {
        evt.preventDefault();
        var input = e(this).siblings(".qty-input"),
            value = parseInt(input.val(), 10);
        if (!isNaN(value)) input.val(value + 1);
    });

    e(".quantity-minus").on("click", function (evt) {
        evt.preventDefault();
        var input = e(this).siblings(".qty-input"),
            value = parseInt(input.val(), 10);
        if (!isNaN(value) && value > 1) input.val(value - 1);
    });

    // ============================
    // Disable Right Click & Inspect
    // ============================
    window.addEventListener(
        "contextmenu",
        function (evt) {
            evt.preventDefault();
        },
        false
    );

    document.onkeydown = function (evt) {
        return !(
            evt.keyCode === 123 ||
            (evt.ctrlKey &&
                evt.shiftKey &&
                (evt.keyCode === "I".charCodeAt(0) ||
                    evt.keyCode === "C".charCodeAt(0) ||
                    evt.keyCode === "J".charCodeAt(0))) ||
            (evt.ctrlKey && evt.keyCode === "U".charCodeAt(0))
        );
    };
})(jQuery);
